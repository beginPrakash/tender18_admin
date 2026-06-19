<?php
/**
 * delete_orphan_tenders.php
 *
 * Deletes all records from `tenders_all` that have NO matching ref_no
 * in tenders_posts, tenders_live, or tenders_archive.
 *
 * Deletion order:
 *   1. Elasticsearch (bulk delete by MySQL `id` as doc _id)
 *   2. MySQL (DELETE from tenders_all)
 *
 * Run from CLI:
 *   php delete_orphan_tenders.php
 *
 * Dry-run (show count only, no deletions):
 *   php delete_orphan_tenders.php --dry-run
 */

if (php_sapi_name() !== 'cli') {
    die("This script can only be run from the command line.\n");
}

// ─── Bootstrap ────────────────────────────────────────────────────────────────
set_time_limit(0);
ini_set('memory_limit', '-1');

// Resolve paths relative to this file's location (admin/tenders/)
$rootDir = realpath(__DIR__ . '/../../');

require_once $rootDir . '/elasticsearch/elastic_client.php'; // es_request(), ES_INDEXES
require_once $rootDir . '/admin/includes/connection.php';    // $con

if (!$con) {
    die("MySQL connect error: " . mysqli_connect_error() . "\n");
}

$index    = ES_INDEXES['ALL'];
$dryRun   = in_array('--dry-run', $argv, true);
$batchSize = 1000;

// ─── Step 1: Count orphan records ────────────────────────────────────────────
$count_sql = "
    SELECT COUNT(ta.id) AS total
    FROM tenders_all ta
    LEFT JOIN tenders_posts tp
        ON tp.ref_no = ta.ref_no
    LEFT JOIN tenders_live tl
        ON tl.ref_no = ta.ref_no
    LEFT JOIN tenders_archive tar
        ON tar.ref_no = ta.ref_no
    WHERE tp.ref_no  IS NULL
      AND tl.ref_no  IS NULL
      AND tar.ref_no IS NULL
";

$count_result = mysqli_query($con, $count_sql);
if (!$count_result) {
    die("Count query error: " . mysqli_error($con) . "\n");
}

$total_found = (int)mysqli_fetch_assoc($count_result)['total'];

echo "=====================================\n";
echo "     DELETE ORPHAN TENDERS\n";
echo "=====================================\n";
echo "Elasticsearch Index : $index\n";
echo "Orphan records found: $total_found\n";

if ($total_found === 0) {
    echo "Nothing to delete. Exiting.\n";
    exit(0);
}

if ($dryRun) {
    echo "\n[DRY-RUN] No deletions performed.\n";
    echo "Run without --dry-run to execute.\n";
    exit(0);
}

// ─── Step 2: Confirmation prompt ─────────────────────────────────────────────
echo "\nAre you sure you want to delete $total_found orphan records? (yes/no): ";
$stdin = fopen("php://stdin", "r");
$answer = strtolower(trim(fgets($stdin)));
fclose($stdin);

if ($answer !== 'yes') {
    echo "Aborted.\n";
    exit(0);
}

// ─── Step 3: Batch delete ─────────────────────────────────────────────────────
$totalEsDeleted  = 0;
$totalDbDeleted  = 0;
$failedEsIds     = [];
$failedDbIds     = [];
$batchNum        = 0;

echo "\nStarting deletion in batches of $batchSize...\n\n";

while (true) {

    // Fetch next batch of orphan IDs from MySQL
    // We always fetch the first $batchSize rows — as rows are deleted, the
    // next loop iteration naturally picks up the following batch.
    $sql = "
        SELECT ta.id
        FROM tenders_all ta
        LEFT JOIN tenders_posts tp
            ON tp.ref_no = ta.ref_no
        LEFT JOIN tenders_live tl
            ON tl.ref_no = ta.ref_no
        LEFT JOIN tenders_archive tar
            ON tar.ref_no = ta.ref_no
        WHERE tp.ref_no  IS NULL
          AND tl.ref_no  IS NULL
          AND tar.ref_no IS NULL
        ORDER BY ta.id ASC
        LIMIT $batchSize
    ";

    $res = mysqli_query($con, $sql);
    if (!$res) {
        echo "Batch fetch error: " . mysqli_error($con) . "\n";
        break;
    }

    if (mysqli_num_rows($res) === 0) {
        break; // All done
    }

    $ids  = [];
    $bulk = [];

    while ($row = mysqli_fetch_assoc($res)) {
        $id     = (int)$row['id'];
        $ids[]  = $id;

        // Build Elasticsearch bulk delete action
        $bulk[] = json_encode([
            'delete' => [
                '_index' => $index,
                '_id'    => (string)$id,
            ]
        ]);
    }
    mysqli_free_result($res);

    $batchNum++;
    $batchCount = count($ids);
    echo "Batch #$batchNum — $batchCount records ...\n";

    // ── 3a. Delete from Elasticsearch first ───────────────────────────────────
    $esDeletedInBatch = 0;

    if (!empty($bulk)) {
        $body = implode("\n", $bulk) . "\n";

        try {
            $resp    = es_request('POST', '_bulk', $body);
            $bodyObj = is_array($resp['body']) ? $resp['body'] : json_decode($resp['body'], true);

            if (isset($bodyObj['items'])) {
                foreach ($bodyObj['items'] as $item) {
                    $status = $item['delete']['status'] ?? 0;
                    if (in_array($status, [200, 404], true)) {
                        // 200 = deleted  |  404 = already absent — both are acceptable
                        $esDeletedInBatch++;
                    } else {
                        $failedEsIds[] = $item['delete']['_id'] ?? 'unknown';
                    }
                }
                $totalEsDeleted += $esDeletedInBatch;
            } else {
                echo "  Warning: ES bulk response was unparsable — skipping ES count.\n";
            }

        } catch (Exception $e) {
            echo "  ERROR communicating with Elasticsearch: " . $e->getMessage() . "\n";
            echo "  Aborting to prevent data inconsistency.\n";
            $failedEsIds = array_merge($failedEsIds, $ids);
            break;
        }
    }

    echo "  ES  deleted : $esDeletedInBatch\n";

    // ── 3b. Delete from MySQL (inside a transaction) ───────────────────────────
    $dbDeletedInBatch = 0;

    if (!empty($ids)) {
        mysqli_begin_transaction($con);
        try {
            $idsStr     = implode(',', $ids);
            $delete_sql = "DELETE FROM tenders_all WHERE id IN ($idsStr)";
            $del_res    = mysqli_query($con, $delete_sql);

            if (!$del_res) {
                throw new Exception(mysqli_error($con));
            }

            $dbDeletedInBatch  = mysqli_affected_rows($con);
            $totalDbDeleted   += $dbDeletedInBatch;
            mysqli_commit($con);

        } catch (Exception $e) {
            mysqli_rollback($con);
            echo "  ERROR deleting from MySQL: " . $e->getMessage() . "\n";
            echo "  Aborting to prevent further data inconsistency.\n";
            $failedDbIds = array_merge($failedDbIds, $ids);
            break;
        }
    }

    echo "  MySQL deleted : $dbDeletedInBatch\n";
}

// ─── Step 4: Summary ─────────────────────────────────────────────────────────
echo "\n=====================================\n";
echo "         EXECUTION SUMMARY\n";
echo "=====================================\n";
echo "Total Orphans Found   : $total_found\n";
echo "ES Deleted Count      : $totalEsDeleted\n";
echo "MySQL Deleted Count   : $totalDbDeleted\n";
echo "Batches Processed     : $batchNum\n";

$hasErrors = !empty($failedEsIds) || !empty($failedDbIds);

if (!empty($failedEsIds)) {
    echo "ES Failed IDs count   : " . count($failedEsIds) . "\n";
    echo "First 10 ES Failed    : " . implode(', ', array_slice($failedEsIds, 0, 10)) . "\n";
}

if (!empty($failedDbIds)) {
    echo "DB Failed IDs count   : " . count($failedDbIds) . "\n";
    echo "First 10 DB Failed    : " . implode(', ', array_slice($failedDbIds, 0, 10)) . "\n";
}

echo "Status                : " . ($hasErrors ? "COMPLETED WITH ERRORS" : "SUCCESS") . "\n";
echo "=====================================\n";
