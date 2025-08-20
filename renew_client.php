<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "u755652361_thesis";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$client = null;
$error = '';
$success = '';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM cliente WHERE id = $id");
    $client = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['renew'])) {
    $old_id = intval($_POST['old_id']);
    $new_policy_number = trim($_POST['policy_number']);
    $new_start_date = $_POST['start_date'];
    $new_date_remittance = $_POST['date_remittance'];

    $result = $conn->query("SELECT * FROM cliente WHERE id = $old_id");
    $old_client = $result->fetch_assoc();

if ($old_client) {
    unset($old_client['id']); // remove auto-increment ID

    // ✅ Replace key fields
    $old_client['policy_number'] = $new_policy_number;
    $old_client['start_date'] = $new_start_date;
    $old_client['date_remittance'] = $new_date_remittance;

    // ✅ Clear collection/payment fields (and trim keys in case of spaces)
    $fieldsToClear = [
        'payment_1', 'payment_2', 'payment_3', 'payment_4', 'payment_5', 'payment_6',
        'payment_1_date', 'payment_2_date', 'payment_3_date',
        'payment_4_date', 'payment_5_date', 'payment_6_date',
        'payment_1_method', 'payment_2_method', 'payment_3_method',
        'payment_4_method', 'payment_5_method', 'payment_6_method',
        'ctpl', 'bank_status', 'remarks'
    ];

    foreach ($fieldsToClear as $field) {
        $field = trim($field); // just in case of whitespace errors in DB
        if (array_key_exists($field, $old_client)) {
            $old_client[$field] = '';
        }
    }

    // ✅ Prepare INSERT
    $columns = implode(", ", array_keys($old_client));
    $placeholders = implode(", ", array_fill(0, count($old_client), "?"));
    $types = str_repeat("s", count($old_client));
    $values = array_values($old_client);

    $stmt = $conn->prepare("INSERT INTO cliente ($columns) VALUES ($placeholders)");
    $stmt->bind_param($types, ...$values);

    if ($stmt->execute()) { 
    // ✅ Step 1: Check old is_active status
    $status_result = $conn->query("SELECT is_active FROM cliente WHERE id = $old_id");
    if ($status_result && $status_result->num_rows > 0) {
        $status_row = $status_result->fetch_assoc();
        $is_active = $status_row['is_active'];

        // ✅ Step 2: Set status and is_active to inactive only if it was active
        if ((int)$is_active === 1) {
            $conn->query("UPDATE cliente SET status = 'Inactive', is_active = 0 WHERE id = $old_id");
        }
    }
        $success = "Client renewed successfully with policy number <strong>$new_policy_number</strong>.";
    } else {
        $error = "Failed to renew client: " . $stmt->error;
    }
} else {
    $error = "Original client not found.";
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>HKBC Admin System</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
    }

    header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 60px;
      background-color: white;
      color: black;
      padding: 10px 20px;
      z-index: 1000;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .header-left {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .header-right {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .title {
      font-size: 1.2rem;
      font-weight: bold;
    } 
    .admin-profile {
      font-weight: 500;
      font-size: 0.95rem;
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 20px;
      text-align: center;
      width: 100%;              /* ensure full width */
      box-sizing: border-box;   /* prevent overflow */
      align-self: center;
      align-items: center;
    }

    .admin-name {
      font-weight: 500;
      margin-top: 8px;
    }

    .admin-icon {
      font-size: 80px;
      color: white;
      margin-left: -25%;
    }

    .sidebar {
      position: fixed;
      left: 0;
      width: 250px;
      height: 100%;
      background-color: #002244;
      color: white;
      transition: width 0.3s ease;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      z-index: 1100;
    }


    .sidebar.collapsed {
      width: 60px;
    }
    .sidebar.collapsed .admin-profile {
      display: none;
    }

    .sidebar ul {
      list-style: none;
      padding: 0;
      margin: 0;
      width: 100%;
    }

    .sidebar li a {
      display: flex;
      align-items: center;
      padding: 15px 20px;
      color: white;
      text-decoration: none;
      transition: background 0.3s;
    }

    .sidebar li a:hover {
      background-color: #003366;
    }

    .sidebar i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    }

    .sidebar.collapsed .text {
      display: none;
    }

    .logout-btn {
      margin-top: auto;
      width: 100%;
    }
    .content {
      margin-top: 60px;
      margin-left: 270px;
      margin-right: 20px;
      width: 100%;
    }

    .sidebar.collapsed ~ .content {
      margin-left: 60px;
    }

    .toggle-btn {
      background: none;
      border: none;
      color: white;
      font-size: 2rem;         /* bigger icon */
      padding: 10px 12px;      /* space around */
      text-align: left;
      width: 100%;             /* full width so it hugs the left */
      cursor: pointer;
    }
    a:hover .border {
      background-color: #f7f7f7;
    }

  </style>
</head>
<body>

<!-- Header -->
<header>
  <div class="header-left">
    <h2>h</h2>
  </div>
  <div class="header-right">
    <div class="title">HKBC INSURANCE SERVICES ADMIN SYSTEM</div>
    <img src="logo.png" alt="HKBC Logo" style="height: 60px; width: 60px; border-radius: 50%; background-color: white;">
  </div>
</header>
<!-- Sidebar -->
<div class="sidebar d-flex flex-column" id="sidebar">
  <button class="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
  <div class="admin-profile">
    <i class="fas fa-user-circle admin-icon"></i>
    <div class="admin-name"><?= htmlspecialchars($_SESSION['admin_name']) ?></div>
  </div>
  <ul class="flex-grow-1 w-100">
    <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> <span class="text">Dashboard</span></a></li>
    <li><a href="client.php"><i class="fas fa-users"></i> <span class="text">Client List</span></a></li>
    <li><a href="report.php "><i class="fas fa-archive"></i> <span class="text">Reports</span></a></li>
    <li><a href="activitylog.php"><i class="fas fa-clipboard-list"></i> <span class="text">Activity Log</span></a></li>
    <li><a href="settings.php"><i class="fas fa-cog"></i> <span class="text">Settings</span></a></li>
  </ul>
  <form action="logout.php" method="POST" class="logout-btn">
    <button type="submit" class="btn w-100 text-start text-white" style="border-radius: 0; padding: 15px 20px; border: none;">
      <i class="fas fa-sign-out-alt me-2"></i> <span class="text">Logout</span>
    </button>
  </form>
</div>

<div class="content">

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
    <a href="client.php" class="btn btn-primary mt-3">Back to Client List</a>
  <?php elseif ($client): ?>
    <form method="POST" class="card p-4 shadow-sm">
      <input type="hidden" name="old_id" value="<?= htmlspecialchars($client['id']) ?>">

      <h2 class="mb-4">
        <?= $client ? "Renewal for <strong>" . htmlspecialchars($client['name']) . "</strong>" : "Renew Client" ?>
      </h2>

      <div class="mb-3">
        <label for="policy_number" class="form-label">New Policy Number</label>
        <input type="text" class="form-control" name="policy_number" id="policy_number" required value="<?= htmlspecialchars($client['policy_number'] . '-R') ?>">
      </div>

      <div class="mb-3">
        <label for="start_date" class="form-label">Issued Date</label>
        <input type="date" class="form-control" name="start_date" id="start_date" required value="<?= date('Y-m-d') ?>">
      </div>

      <div class="mb-3">
        <label for="date-remittance" class="form-label">Effectivity</label>
        <input type="date" class="form-control" name="date-remittance" id="date-remittance" required value="<?= date('Y-m-d', strtotime('+1 year')) ?>">
      </div>

      <div class="mt-3">
        <button type="submit" name="renew" class="btn btn-success me-2">Confirm & Renew</button>
        <a href="client.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  <?php else: ?>
    <div class="alert alert-warning">No client found to renew.</div>
    <a href="client.php" class="btn btn-primary mt-3">Back to Client List</a>
  <?php endif; ?>
</div>


<!-- Script -->
<script>
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  sidebar.classList.toggle('collapsed');
}
</script>

</body>
</html>
