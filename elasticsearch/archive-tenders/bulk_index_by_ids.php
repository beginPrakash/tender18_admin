<?php
require_once __DIR__ . '/../elastic_client.php';
require_once __DIR__ . '/../../admin/includes/connection.php'; // defines $con

if (!$con) {
    die('MySQL connect error: ' . mysqli_connect_error());
}

/**
 * Bulk index tender records into Elasticsearch by MySQL IDs
 *
 * @param mysqli $con
 * @param array  $ids          Array of MySQL IDs
 * @param string $index      Elasticsearch index name (or alias)
 * @param int    $chunkSize    Bulk chunk size (default 1000)
 *
 * @return array
 */
function bulk_archive_tenders_by_ids($ids, $index, $chunkSize = 1000): array
{
    global $con;
    if (empty($ids)) {
        return ['indexed' => 0, 'message' => 'No IDs to index'];
    }

    $totalIndexed = 0;

    // Chunk IDs (important for large sets)
    foreach (array_chunk($ids, $chunkSize) as $chunk) {

        $placeholders = implode(',', array_fill(0, count($chunk), '?'));
        $types = str_repeat('i', count($chunk));

        $sql = "SELECT id, title, tender_id, ref_no, agency_type, due_date, tender_value,
                   pincode, publish_date, tender_fee, tender_emd, documents,
                   city, state, department, description, tender_type, opening_date
            FROM tenders_archive
            WHERE id IN ($placeholders)";

        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, $types, ...$chunk);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        $bulk = [];

        while ($row = mysqli_fetch_assoc($res)) {

            $docId = (string)$row['id'];

            // Bulk metadata
            $bulk[] = json_encode([
                'index' => [
                    '_index' => $index,
                    '_id'    => $docId
                ]
            ]);

            // Normalize dates
            $bulk[] = json_encode([
                'title'         => $row['title'],
                'tender_id'     => $row['tender_id'],
                'ref_no'        => $row['ref_no'],
                'agency_type'   => $row['agency_type'],
                'due_date'      => !empty($row['due_date']) ? substr($row['due_date'], 0, 10) : null,
                'publish_date'  => !empty($row['publish_date']) ? substr($row['publish_date'], 0, 10) : null,
                'opening_date'  => !empty($row['opening_date']) ? substr($row['opening_date'], 0, 10) : null,
                'tender_value'  => (float)$row['tender_value'],
                'tender_fee'    => (float)$row['tender_fee'],
                'tender_emd'    => (float)$row['tender_emd'],
                'pincode'       => $row['pincode'],
                'documents'     => $row['documents'],
                'city'          => $row['city'],
                'state'         => $row['state'],
                'department'    => $row['department'],
                'description'   => $row['description'],
                'tender_type'   => $row['tender_type']
            ]);

            $totalIndexed++;
        }

        mysqli_stmt_close($stmt);

        if (!empty($bulk)) {
            es_request('POST', '_bulk', implode("\n", $bulk) . "\n");
        }
    }

    return [
        'indexed' => $totalIndexed,
        'index'   => $index
    ];
}
