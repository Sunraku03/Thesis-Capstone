<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "u755652361_thesis";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($_POST['user_id']);
    $newStatus = intval($_POST['new_status']);

    // Prevent user from disabling themselves
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $userId) {
        die("You cannot change your own account status.");
    }

    $stmt = $conn->prepare("UPDATE users SET is_active = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $userId);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: settings.php");
exit;