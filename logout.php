<?php
session_start();
$conn = new mysqli("localhost", "root", "", "u755652361_thesis");

if (isset($_SESSION['log_id'])) {
    $log_id = $_SESSION['log_id'];
    $stmt = $conn->prepare("UPDATE activity_log SET logout_time = NOW() WHERE id = ?");
    $stmt->bind_param("i", $log_id);
    $stmt->execute();
}

session_destroy();
header("Location: index.php");
$conn->close();
?>