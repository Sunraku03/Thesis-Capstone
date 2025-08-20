<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['user_id'])) {
    $userId = intval($_POST['user_id']);

    $conn = new mysqli("localhost", "root", "", "u755652361_thesis");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prevent deleting your own account or super admin
    if ($userId !== $_SESSION['user_id']) {
        $conn->query("DELETE FROM users WHERE id = $userId AND role = 'staff'");
    }

    $conn->close();
}

header("Location: settings.php");
exit;
?>