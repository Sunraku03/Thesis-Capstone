<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "u755652361_thesis";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/vendor/autoload.php';
require 'phpmailer/vendor/phpmailer/phpmailer/src/SMTP.php';
require 'phpmailer/vendor/phpmailer/phpmailer/src/Exception.php';



// Validate checkbox
if (!isset($_POST['client_ids']) || empty($_POST['client_ids'])) {
    die("No clients selected.");
}

$clientIds = $_POST['client_ids'];
$emailCount = 0;
$smsCount = 0;
$adminId = $_SESSION['user_id']; // Get admin ID from session

// Check for unified notify button
if (isset($_POST['notify_both_due']) || isset($_POST['notify_both_overdue'])) {
    foreach ($clientIds as $id) {
        $id = (int)$id;

        $sql = "SELECT name, email, contact_number FROM cliente WHERE id = $id";
        $result = $conn->query($sql);

        if ($result && $row = $result->fetch_assoc()) {
            $name = $row['name'];
            $email = $row['email'];
            $contact = $row['contact_number'];

            // 📧 Send Email
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'changmakasiar@gmail.com';
                    $mail->Password = 'wiza ziug znkv eand';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom('changmakasiar@gmail.com', 'Insurance Admin');
                    $mail->addAddress($email, $name);
                    $mail->Subject = 'Insurance Due Reminder';
                    $mail->Body = "Dear $name,\n\nThis is a reminder that your insurance policy is due. Please contact us soon.\n\nThank you!";

                    $mail->send();
                    $emailCount++;
                } catch (Exception $e) {
                    // Optional: Log error
                }
            }

            // 📱 Send SMS
            if (!empty($contact)) {
                $API_KEY = '0kF3G_bMiCcsdIr5ibrYJPEYlCnpQVaHrZD5';
                $PROJECT_ID = 'PJ4408f0122a8f0b22';
                $message = "Hi $name, reminder: your insurance policy is due. Contact us for assistance.";

                $data = [
                    'to_number' => $contact,
                    'content' => $message
                ];

                $ch = curl_init("https://api.telerivet.com/v1/projects/$PROJECT_ID/messages/send");
                curl_setopt($ch, CURLOPT_USERPWD, "$API_KEY:");
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);

                $smsCount++;
            }
            // ✅ Log to daily_notification_log
            $stmt = $conn->prepare("INSERT INTO daily_notification_log (admin_id, client_id, date_sent) VALUES (?, ?, CURRENT_DATE)");
            $stmt->bind_param("ii", $adminId, $id);
            $stmt->execute();
        }
    }

    // After sending is done
    $_SESSION['notify_modal_message'] = "$emailCount Email(s) and $smsCount SMS sent successfully.";
    header("Location: dashboard.php"); // Or wherever your dashboard is
    exit;
    }

$conn->close();
?>
