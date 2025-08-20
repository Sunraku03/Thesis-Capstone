<?php
require 'phpword/vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;

$servername = "localhost";
$username = "root";
$password = "";
$database = "u755652361_thesis";
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional filters (from GET)
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

$phpWord = new PhpWord();
$section = $phpWord->addSection();

// Add a table for header with logo and title aligned
$headerTable = $section->addTable();
$headerTable->addRow();
$headerTable->addCell(1000)->addImage('logo.png', ['width' => 60, 'height' => 60, 'alignment' => Jc::LEFT]);
$headerTable->addCell(8000)->addText('HKBC INSURANCE SERVICES', ['bold' => true, 'size' => 16], ['alignment' => Jc::LEFT]);

$section->addTextBreak(1);
$section->addText('Client Report', ['bold' => true, 'size' => 14]);
$section->addTextBreak(1);

$table = $section->addTable(['borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 50]);
$table->addRow();
$table->addCell(2500)->addText("Policy Number", ['bold' => true]);
$table->addCell(2500)->addText("Name", ['bold' => true]);
$table->addCell(2500)->addText("Provider", ['bold' => true]);
$table->addCell(2500)->addText("Start Date", ['bold' => true]);
$table->addCell(2500)->addText("Mortgage", ['bold' => true]);
$table->addCell(2500)->addText("Amount Insured", ['bold' => true]);

while ($row = $result->fetch_assoc()) {
    $table->addRow();
    $table->addCell(2500)->addText($row['policy_number']);
    $table->addCell(2500)->addText($row['name']);
    $table->addCell(2500)->addText($row['provider']);
    $table->addCell(2500)->addText($row['start_date']);
    $table->addCell(2500)->addText($row['mortgage']);
    $table->addCell(2500)->addText("\u{20B1}" . number_format((float)$row['amount_insured'], 2));
}

header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="Client_Report.docx"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');

$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save("php://output");
exit;
?>