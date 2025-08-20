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

$today = new DateTime();
$sevenDaysLater = (clone $today)->modify('+7 days');

// Fetch clients due in the next 7 days
$sql = "SELECT name, email, end_date FROM cliente 
        WHERE email IS NOT NULL AND email != '' 
        AND end_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
$result = $conn->query($sql);

$sentEmails = [];
$clientEmails = [];

while ($row = $result->fetch_assoc()) {
    $email = trim($row['email']);
    $endDate = new DateTime($row['end_date']);
    $daysLeft = (int)$today->diff($endDate)->format('%a');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) continue;

    // Send individualized email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'changmakasiar@gmail.com';
        $mail->Password = 'wiza ziug znkv eand'; // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('changmakasiar@gmail.com', 'HKBC Admin');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Reminder: Policy Due in ' . $daysLeft . ' Day' . ($daysLeft > 1 ? 's' : '');
        $mail->Body = "
            Dear {$row['name']},<br><br>
            This is a friendly reminder that your insurance payment is due in <strong>{$daysLeft} day" . ($daysLeft > 1 ? 's' : '') . "</strong> (by <strong>{$endDate->format('F j, Y')}</strong>).<br>
            Kindly settle the payment by the end of the day to keep your policy active and avoid any late fees.<br>
            If you have already made the payment, please disregard this message. <br> <br>
            Best regards, <br>
            HKBC Admin Team
        ";

        $mail->send();
        $sentEmails[] = $email;

    } catch (Exception $e) {
        // Log or ignore individual email failures
        continue;
    }
}

// Log sent emails
if (!empty($sentEmails)) {
    $sentList = implode(',', $clientEmails);

    // Insert to email_log (make sure sent_at column exists)
    $stmt = $conn->prepare("INSERT INTO email_log (sent_to, sent_at) VALUES (?, NOW())");
    $stmt->bind_param("s", $sentList);
    $stmt->execute();
    $stmt->close();

    // Log to client_activity_log
    if (isset($_SESSION['user_id'])) {
        $adminId = $_SESSION['user_id'];
        $action = 'Notified';

        $stmt = $conn->prepare("INSERT INTO client_activity_log (user_id, client_id, action, timestamp) VALUES (?, NULL, ?, NOW())");
        $stmt->bind_param("is", $adminId, $action);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->close();

// Redirect to dashboard
header("Location: dashboard.php");
exit;
?>
