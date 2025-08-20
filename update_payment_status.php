<?php
$connection = new mysqli("localhost", "root", "", "u755652361_thesis");

$client_id = $_POST['client_id'];
$payment_number = $_POST['payment_number'];
$set_paid = $_POST['set_paid'];

$status_column = "payment_" . $payment_number . "_status";

// Use prepared statement to avoid SQL injection
$query = "UPDATE cliente SET $status_column = ? WHERE id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("ii", $set_paid, $client_id);
$stmt->execute();
$stmt->close();

header("Location: view_client.php?id=" . $client_id);
exit;
?>
