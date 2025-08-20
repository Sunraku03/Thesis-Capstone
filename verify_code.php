<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['pending_user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $inputCode = $_POST['code'];

    if (
        isset($_SESSION['auth_code']) &&
        $inputCode == $_SESSION['auth_code'] &&
        (time() - $_SESSION['auth_code_time']) <= 300 // 5 minutes
    ) {
        // ✅ Code is correct and not expired
        $_SESSION['user_id'] = $_SESSION['pending_user_id'];
        $_SESSION['admin_name'] = $_SESSION['pending_username'];
        $_SESSION['role'] = $_SESSION['pending_role'];

        // Clear temporary session
        unset($_SESSION['pending_user_id'], $_SESSION['pending_username'], $_SESSION['pending_role'], $_SESSION['auth_code'], $_SESSION['auth_code_time']);

        // Log successful login
        $conn = new mysqli("localhost", "root", "", "u755652361_thesis");
        $userId = $_SESSION['user_id'];
        $log_stmt = $conn->prepare("INSERT INTO activity_log (user_id, login_time) VALUES (?, NOW())");
        $log_stmt->bind_param("i", $userId);
        $log_stmt->execute();
        $_SESSION['log_id'] = $conn->insert_id;
        $conn->close();

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid or expired code.";
    }
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Verify Access Code</title>
  <style>
    body {
      margin: 0;
      font-family: "Segoe UI", sans-serif;
      background: #f0f2f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .verify-box {
      background: #fff;
      padding: 40px;
      width: 400px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.15);
      text-align: center;
    }

    .verify-box h2 {
      margin-bottom: 20px;
      color: #333;
    }

    .verify-box input[type="text"] {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 16px;
    }

    .verify-box button {
      width: 100%;
      padding: 12px;
      background-color: #007BFF;
      color: white;
      border: none;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
    }

    .verify-box button:hover {
      background-color: #0056b3;
    }

    .error {
      color: red;
      margin-top: 15px;
    }
  </style>
</head>
<body>
  <div class="verify-box">
    <h2>Enter Verification Code</h2>
    <p>A code has been sent to the owner's email. Please enter it below:</p>
    <form method="POST">
      <input type="text" name="code" placeholder="6-digit code" maxlength="6" required>
      <button type="submit">Verify</button>
      <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
    </form>
  </div>
</body>
</html>