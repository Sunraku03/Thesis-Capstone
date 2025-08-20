<?php
require 'phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$servername = "localhost";
$username = "root";
$password = "";
$database = "u755652361_thesis";
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get filters
$status = $_GET['status'] ?? '';
$provider = $_GET['provider'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

$where = [];
if ($status) $where[] = "status = '" . $conn->real_escape_string($status) . "'";
if ($provider) $where[] = "provider = '" . $conn->real_escape_string($provider) . "'";
if ($start_date && $end_date) $where[] = "start_date BETWEEN '$start_date' AND '$end_date'";
$whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

$query = "SELECT policy_number, name, provider, start_date, mortgage, amount_insured FROM cliente $whereSQL ORDER BY id DESC";
$result = $conn->query($query);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Policy Number');
$sheet->setCellValue('B1', 'Name');
$sheet->setCellValue('C1', 'Provider');
$sheet->setCellValue('D1', 'Start Date');
$sheet->setCellValue('E1', 'Mortgage');
$sheet->setCellValue('F1', 'Amount Insured');

$rowNum = 2;
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue("A$rowNum", $row['policy_number']);
    $sheet->setCellValue("B$rowNum", $row['name']);
    $sheet->setCellValue("C$rowNum", $row['provider']);
    $sheet->setCellValue("D$rowNum", $row['start_date']);
    $sheet->setCellValue("E$rowNum", $row['mortgage']);
    $sheet->setCellValue("F$rowNum", $row['amount_insured']);
    $rowNum++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Client_Report.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>