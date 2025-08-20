<?php
session_start();
$conn = new mysqli("localhost", "root", "", "u755652361_thesis");

// Only allow admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$username = $_POST['username'];
$password = $_POST['password']; // stored as plain text
$role     = $_POST['role'];

$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password, $role);

if ($stmt->execute()) {
    header("Location: settings.php?success=1");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
