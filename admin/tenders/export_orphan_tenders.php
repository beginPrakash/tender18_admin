<?php
/**
 * export_orphan_tenders.php
 *
 * Exports all records from tenders_all that have NO matching ref_no
 * in tenders_posts, tenders_live, or tenders_archive.
 *
 * Run via browser or CLI:
 *   php export_orphan_tenders.php
 *
 * Output: orphan_tenders_<timestamp>.xlsx  (downloaded or saved in same dir)
 */

set_time_limit(0);
ignore_user_abort(true);
ini_set('memory_limit', '-1');

// ─── DB Connection ────────────────────────────────────────────────────────────
require_once __DIR__ . '/../../admin/includes/connection.php'; // defines $con

if (!$con) {
    die('MySQL connect error: ' . mysqli_connect_error());
}

// ─── PHPExcel ────────────────────────────────────────────────────────────────
require_once __DIR__ . '/../../admin/PHPExcel/Classes/PHPExcel.php';

// ─── Query ───────────────────────────────────────────────────────────────────
$sql = "
    SELECT
        ta.*
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
    ORDER BY ta.created_at DESC
";

$result = mysqli_query($con, $sql);

if (!$result) {
    die('Query error: ' . mysqli_error($con));
}

$totalRows = mysqli_num_rows($result);

// ─── Build Excel ──────────────────────────────────────────────────────────────
$objPHPExcel = new PHPExcel();

// ── Metadata ─────────────────────────────────────────────────────────────────
$objPHPExcel->getProperties()
    ->setCreator('SpotArrow System')
    ->setLastModifiedBy('SpotArrow System')
    ->setTitle('Orphan Tenders Export')
    ->setSubject('Tenders in tenders_all not linked to posts / live / archive')
    ->setDescription('Generated on ' . date('Y-m-d H:i:s'))
    ->setKeywords('tenders export')
    ->setCategory('Tenders');

$sheet = $objPHPExcel->getActiveSheet();
$sheet->setTitle('Orphan Tenders');

// ── Header row styling ────────────────────────────────────────────────────────
$headerStyle = [
    'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
    'fill'      => [
        'type'       => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => ['argb' => 'FF2D5986'],
    ],
    'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
    'borders'   => [
        'allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN],
    ],
];

// ── Column headers ─────────────────────────────────────────────────────────────
// Fetch one row to dynamically get column names, then rewind
$firstRow = mysqli_fetch_assoc($result);
if (!$firstRow) {
    die('No orphan tenders found. Nothing to export.');
}

$columns = array_keys($firstRow);
$colCount = count($columns);

// Write header
$colLetter = 'A';
foreach ($columns as $colName) {
    $sheet->setCellValue($colLetter . '1', strtoupper($colName));
    $sheet->getColumnDimension($colLetter)->setAutoSize(true);
    $colLetter++;
}

// Apply header style
$lastColLetter = chr(ord('A') + $colCount - 1);
$sheet->getStyle('A1:' . $lastColLetter . '1')->applyFromArray($headerStyle);

// Freeze header row
$sheet->freezePane('A2');

// ── Data rows ─────────────────────────────────────────────────────────────────
$rowNum = 2;

// Write the first row we already fetched
$colLetter = 'A';
foreach ($firstRow as $value) {
    $sheet->setCellValue($colLetter . $rowNum, $value);
    $colLetter++;
}
$rowNum++;

// Write remaining rows
while ($row = mysqli_fetch_assoc($result)) {
    $colLetter = 'A';
    foreach ($row as $value) {
        $sheet->setCellValue($colLetter . $rowNum, $value);
        $colLetter++;
    }
    $rowNum++;
}

mysqli_free_result($result);

// ── Alternating row colors ────────────────────────────────────────────────────
$evenStyle = [
    'fill' => [
        'type'       => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => ['argb' => 'FFF0F4FA'],
    ],
];

for ($r = 2; $r < $rowNum; $r++) {
    if ($r % 2 === 0) {
        $sheet->getStyle('A' . $r . ':' . $lastColLetter . $r)
              ->applyFromArray($evenStyle);
    }
}

// ── Output / Download ─────────────────────────────────────────────────────────
$filename = 'orphan_tenders_' . date('Ymd_His') . '.xlsx';

$isCli = (php_sapi_name() === 'cli');

if ($isCli) {
    // Save to disk next to this script
    $savePath = __DIR__ . '/' . $filename;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($savePath);
    echo "✅ Export complete!\n";
    echo "   Total rows  : {$totalRows}\n";
    echo "   File saved  : {$savePath}\n";
} else {
    // Force browser download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}
