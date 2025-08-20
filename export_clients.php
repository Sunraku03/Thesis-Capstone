<?php
require 'phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$host         = "localhost";
$username     = "root";
$password     = "";
$database     = "u755652361_thesis";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$selectedMonths = isset($_POST['filter_months']) ? $_POST['filter_months'] : [];
$selectedYear = isset($_POST['filter_year']) ? $_POST['filter_year'] : '';
$status = isset($_POST['filter_status']) ? $_POST['filter_status'] : '';
$provider = isset($_POST['filter_provider']) ? $_POST['filter_provider'] : '';


$conditions = [];

if ($selectedYear !== '') {
    $conditions[] = "YEAR(start_date) = " . intval($selectedYear);
}

if (!empty($selectedMonths)) {
    $monthsIn = implode(",", array_map('intval', $selectedMonths));
    $conditions[] = "MONTH(start_date) IN ($monthsIn)";
}
if ($status !== '') {
    $conditions[] = "status = '" . $conn->real_escape_string($status) . "'";
}
if ($provider !== '') {
    $conditions[] = "provider = '" . $conn->real_escape_string($provider) . "'";
}

$where = count($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
$query = "SELECT * FROM cliente $where"; 
$result = $conn->query($query);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Column headers
$headers = [
    'ID', 'Policy Number', 'Name', 'Insurance Provider', 'Facebook Name', 'Contact Number', 'Email', 'Address',
    'Start Date', 'End Date', 'Vehicle Unit', 'Chasis No.', 'Motor No.', 'Plate No.', 'Vehicle Color',
    'Amount Insured', 'BIPD', 'PA', 'AON', 'Net Remitting', 'HKBC Net', 'Mark Up', 'Late Charges', 'Cancelled Income',
    'Reinstatement Fee', 'Make Up Agent', 'Comission', 'Source', 'Mortgage', 'Status', 'OR Number',
    'Payment 1', 'Payment 2', 'Payment 3', 'Payment 4', 'Payment 5', 'Payment 6',
    'Payment Status', 'Date Remittance', 'CTPL', 'Bank Status', 'Remarks'
];

$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $col++;
}

// Data rows
$rowNum = 2;
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue("A$rowNum", 'HKBC_202500' . $row['id']);
    $sheet->setCellValue("B$rowNum", $row['policy_number']);
    $sheet->setCellValue("C$rowNum", $row['name']);
    $sheet->setCellValue("D$rowNum", $row['provider']);
    $sheet->setCellValue("E$rowNum", $row['facebook']);
    $sheet->setCellValue("F$rowNum", $row['contact_number']);
    $sheet->setCellValue("G$rowNum", $row['email']);
    $sheet->setCellValue("H$rowNum", $row['address']);
    $sheet->setCellValue("I$rowNum", $row['start_date']);
    $sheet->setCellValue("J$rowNum", $row['end_date']);
    $sheet->setCellValue("K$rowNum", $row['vehicle_unit']);
    $sheet->setCellValue("L$rowNum", $row['chasis_no']);
    $sheet->setCellValue("M$rowNum", $row['motor_no']);
    $sheet->setCellValue("N$rowNum", $row['plate_no']);
    $sheet->setCellValue("O$rowNum", $row['vehicle_color']);
    $sheet->setCellValue("P$rowNum", $row['amount_insured']);
    $sheet->setCellValue("Q$rowNum", $row['bipd']);
    $sheet->setCellValue("R$rowNum", $row['pa']);
    $sheet->setCellValue("S$rowNum", $row['aon']);
    $sheet->setCellValue("T$rowNum", $row['net_remitting']);
    $sheet->setCellValue("U$rowNum", $row['hkbc_net']);
    $sheet->setCellValue("V$rowNum", $row['mark_up']);
    $sheet->setCellValue("W$rowNum", $row['late_charges']);
    $sheet->setCellValue("X$rowNum", $row['cancelled_income']);
    $sheet->setCellValue("Y$rowNum", $row['reinstatement_fee']);
    $sheet->setCellValue("Z$rowNum", $row['make_up_agent']);
    $sheet->setCellValue("AA$rowNum", $row['comission']);
    $sheet->setCellValue("AB$rowNum", $row['source']);
    $sheet->setCellValue("AC$rowNum", $row['mortgage']);
    $sheet->setCellValue("AD$rowNum", $row['status']);
    $sheet->setCellValue("AE$rowNum", $row['or_number']);
    $sheet->setCellValue("AF$rowNum", $row['payment_1']);
    $sheet->setCellValue("AG$rowNum", $row['payment_2']);
    $sheet->setCellValue("AH$rowNum", $row['payment_3']);
    $sheet->setCellValue("AI$rowNum", $row['payment_4']);
    $sheet->setCellValue("AJ$rowNum", $row['payment_5']);
    $sheet->setCellValue("AK$rowNum", $row['payment_6']);
    $sheet->setCellValue("AL$rowNum", $row['payment_status']);
    $sheet->setCellValue("AM$rowNum", $row['date_remittance']);
    $sheet->setCellValue("AN$rowNum", $row['ctpl']);
    $sheet->setCellValue("AO$rowNum", $row['bank_status']);
    $sheet->setCellValue("AP$rowNum", $row['remarks']);
    $rowNum++;
}

$filename = 'clients_' . date('Y-m-d_H-i-s') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");

$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
exit;
?>