<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/vendor/autoload.php';
session_start();

// DB connection
$conn = new mysqli("localhost", "root", "", "u755652361_thesis");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['client_id'])) {
    $clientId = (int) $_POST['client_id'];

    // Fetch client data
    $stmt = $conn->prepare("SELECT name, email, end_date FROM cliente WHERE id = ?");
    $stmt->bind_param("i", $clientId);
    $stmt->execute();
    $result = $stmt->get_result();
    $client = $result->fetch_assoc();
    $stmt->close();

    if ($client && filter_var($client['email'], FILTER_VALIDATE_EMAIL)) {
        $today = new DateTime();
        $endDate = new DateTime($client['end_date']);
        $daysLeft = (int)$today->diff($endDate)->format('%a');

        // Send email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'changmakasiar@gmail.com';
            $mail->Password = 'wiza ziug znkv eand'; // Use app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('changmakasiar@gmail.com', 'HKBC Admin');
            $mail->addAddress($client['email']);
            $mail->isHTML(true);
            $mail->Subject = 'Reminder: Policy Due in ' . $daysLeft . ' Day' . ($daysLeft > 1 ? 's' : '');
            $mail->Body = "
                Dear {$client['name']},<br><br>
                This is a friendly reminder that your insurance payment is due in <strong>{$daysLeft} day" . ($daysLeft > 1 ? 's' : '') . "</strong> (by <strong>{$endDate->format('F j, Y')}</strong>).<br>
                Kindly settle the payment by the due date to keep your policy active and avoid any late fees.<br>
                If you have already made the payment, please disregard this message.<br><br>
                Best regards,<br>
                HKBC Admin Team
            ";

            $mail->send();

            // Log to activity
            if (isset($_SESSION['user_id'])) {
                $adminId = $_SESSION['user_id'];
                $action = 'Notified';

                $logStmt = $conn->prepare("INSERT INTO client_activity_log (user_id, client_id, action, timestamp) VALUES (?, ?, ?, NOW())");
                $logStmt->bind_param("iis", $adminId, $clientId, $action);
                $logStmt->execute();
                $logStmt->close();
            }

        } catch (Exception $e) {
            // You could log $mail->ErrorInfo here
            // echo "Mailer Error: " . $mail->ErrorInfo;
        }
    }
}

// Redirect back
header("Location: dashboard.php");
exit;
?>
