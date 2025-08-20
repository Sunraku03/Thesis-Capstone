<?php
session_start(); 
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'phpmailer/vendor/autoload.php'; // Adjust the path if needed
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$conn = new mysqli("localhost", "root", "", "u755652361_thesis");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if ($user['password'] === $password) { // 🔒 Use password_verify() if you hash passwords

        if ($user['is_active'] == 0) {
            echo "Your account has been disabled.";
            exit;
        }

        // Store partial login in session (but not fully authorized yet)
        $_SESSION['pending_user_id'] = $user['id'];
        $_SESSION['pending_username'] = $user['username'];
        $_SESSION['pending_role'] = $user['role'];

        // Generate 6-digit code
        $code = rand(100000, 999999);
        $_SESSION['auth_code'] = $code;
        $_SESSION['auth_code_time'] = time();

        // Send email to owner
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'changmakasiar@gmail.com'; // your Gmail
        $mail->Password = 'wiza ziug znkv eand';    // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('changmakasiar@gmail.com', 'HKBC System');
        $mail->addAddress('changmakasiar@gmail.com'); // Owner’s email

        $mail->Subject = 'Staff Login Authentication Code';
        $mail->Body = "Login attempt detected.\n\nStaff: $username\nCode: $code\n\nThis code is valid for 5 minutes.";

        if ($mail->send()) {
            header("Location: verify_code.php");
            exit;
        } else {
            echo "Failed to send code to the owner.";
            exit;
        }

    } else {
        echo "Invalid password.";
    }
} else {
    echo "User not found.";
}

$conn->close();