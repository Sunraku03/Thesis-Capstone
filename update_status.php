<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientId = $_POST['client_id'];
    $newStatus = $_POST['new_status'];
    $newIsActive = $_POST['new_is_active'];

    $conn = new mysqli("localhost", "root", "", "u755652361_thesis");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update both `status` and `is_active`
    $stmt = $conn->prepare("UPDATE cliente SET status = ?, is_active = ? WHERE id = ?");
    $stmt->bind_param("sii", $newStatus, $newIsActive, $clientId);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Client status successfully updated to $newStatus.";
    } else {
        $_SESSION['error'] = "Failed to update client status.";
    }

    $stmt->close();
    $conn->close();

    header("Location: view_client.php?id=" . $clientId);
    exit;
} else {
    header("Location: client.php");
    exit;
}
?>