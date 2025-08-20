<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
    
}
?>
<?php
if (isset($_GET['chart'])) {
    $chartType = $_GET['chart'];

    $conn = new mysqli("localhost", "root", "", "u755652361_thesis");
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }

    switch ($chartType) {
        case "total":
            $sql = "SELECT DATE_FORMAT(end_date, '%Y-%m') AS month, COUNT(*) AS total
                    FROM cliente
                    WHERE end_date IS NOT NULL
                    GROUP BY month ORDER BY month ASC";
            break;
        case "active":
            $sql = "SELECT DATE_FORMAT(end_date, '%Y-%m') AS month, COUNT(*) AS total
                    FROM cliente
                    WHERE is_active = 1 AND end_date IS NOT NULL
                    GROUP BY month ORDER BY month ASC";
            break;
        case "inactive":
            $sql = "SELECT DATE_FORMAT(end_date, '%Y-%m') AS month, COUNT(*) AS total
                    FROM cliente
                    WHERE is_active = 0 AND end_date IS NOT NULL
                    GROUP BY month ORDER BY month ASC";
            break;
        default:
            echo json_encode([]);
            exit;
    }

    $result = $conn->query($sql);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = ['month' => $row['month'], 'total' => $row['total']];
    }

    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
?>

<?php
// Fetch recent admin activities
$activityConn = new mysqli("localhost", "root", "", "u755652361_thesis");
$recentLogs = [];

if (!$activityConn->connect_error) {
    $logQuery = "
    SELECT 
      u.username, 
      cal.action, 
      cal.timestamp,
      CASE
        WHEN cal.action = 'Notified' AND cal.client_id IS NULL THEN 'all clients'
        WHEN cal.action = 'Notified' AND c.name IS NOT NULL THEN c.name
        ELSE IFNULL(c.name, '')
      END AS client_name
    FROM client_activity_log cal
    JOIN users u ON cal.user_id = u.id
    LEFT JOIN cliente c ON cal.client_id = c.id
    ORDER BY cal.timestamp DESC
    LIMIT 5
    ";
    $activityResult = $activityConn->query($logQuery);
    while ($row = $activityResult->fetch_assoc()) {
        $recentLogs[] = $row;
    }
    $activityConn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> 
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
    .content {
      margin-top: 60px;
      margin-left: 250px;
      width: calc(100% - 250px);
      box-sizing: border-box;
    }

    .sidebar.collapsed ~ .content {
      margin-left: 60px;
      width: calc(100% - 60px);
    }

    .dashboard-content {
      width: 100%;
      padding: 30px;
      box-sizing: border-box;
    }
    .sidebar.collapsed ~ .dashboard-content {
      margin-left: 60px;
      width: calc(100% - 60px);
    }
    .stats-row {
      margin: 0;
      padding: 0;
      width: 100%;
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }
    .stats-row .count-box:nth-child(1) {
      flex: 1 1 220px; /* Total Clients */
    }

    .stats-row .count-box:nth-child(2) {
      flex: 1 1 220px; /* Active Clients */
    }

    .stats-row .count-box:nth-child(3) {
      flex: 1 1 220px; /* Inactive Clients */
    }

    .stats-row .count-box:nth-child(4) {
      flex: 1 1 220px; /* Notify Clients */
    }
      .count-box {
      background-color: #ffffff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
      flex: 1 1 220px;
      text-align: center;
      transition: 0.3s;
      border: 2px solid black; /* 👈 added stroke */
    }

    .count-box:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 14px rgba(0, 0, 0, 0.1);
    }

    .count-box h4 {
      font-size: 1.3rem;
      margin-bottom: 1px;
      font-weight: bold;
      display: flex;
      text-align:  center;
      gap: 7px;
    }
    .count-box h4::before {
      content: '';
      display: inline-block;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      border: 1px solid black;
      background-color: currentColor;
    }
    .total-box h4::before {
      background-color: #ffffff; /* White on blue */
    }

    .active-box h4::before {
      background-color: #008000; /* Darker orange on yellow */
    }

    .inactive-box h4::before {
      background-color: #d40303ff; /* Dark red on orange */
    }

    .notify-box h4::before {
      background-color: #004080; /* Blue circle on light blue */
    }
    .count {
      font-size: 3.2rem;
      font-weight: bold;  
      padding: 8px 16px;
      border-radius: 12px; 
      display: inline-block;
    }
    .total-box {
      background-color: #4f46c7; /* Light blue */
      color: #ffffff;
      font-weight: bold;
    }

    .active-box {
      background-color: #00db25ff; /* Light green */
      font-weight: bold;
    }

    .inactive-box {
      background-color: #ff914d; /* Light red */
      font-weight: bold;
    }

    .notify-box {
      background-color: #92defe; /* Light yellow */
      font-weight: bold;
    }

    .client-alerts {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      width: 100%;
      margin: 30px 0;
      
    }

    .client-box {
      flex: 1;
      background-color: #E0E0E0;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      min-width: 0;
      border: 2px solid black; /* 👈 added stroke */
    }

    .client-box h4 {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 15px;
      color: #002244;
    }

    .client-box ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .client-box li {
      display: flex;
      align-items: center;
      gap: 15px;
      padding: 10px 0;
      border-bottom: 1px solid #eee;
    }

    .client-box .form-check-input {
      transform: scale(1.2);
    }

    .client-name {
      flex: 1;
      font-weight: 600;
      color: #333;
    }

    .due-date,
    .client-box .text-muted {
      font-size: 0.85rem;
      color: #e74c3c;
    }

    .client-box .no-due {
      font-style: italic;
      color: #888;
    }

    .client-box .btn-sm {
      font-size: 0.8rem;
      padding: 4px 8px;
    }
    .flash-message {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
      padding: 10px 15px;
      margin-bottom: 20px;
      border-radius: 5px;
      font-weight: 500;
    }
    .box-header {
      position: relative;
      margin-bottom: 10px;
    }

    .minimize-btn {
      position: absolute;
      top: 0;
      right: 0;
      padding: 0;
      font-size: 14px;
      line-height: 1;
      color: #333;
      background: none;
      border: none;
      z-index: 10;
    }

    .minimize-btn:hover {
      color: #007bff;
    }
    .form-check-input {
      border: 1px solid #555 !important;
      width: 1.1em;
      height: 1.1em;
      box-shadow: none;
      outline: none;
    }

    .form-check-input:checked {
      background-color: #007bff;
      border-color: #007bff;
    }

    .graph-section,
    .recent-activity {
      flex: 1;
      background-color: white;
      border-radius: 10px;
      padding: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      max-height: 420px;
      overflow-y: auto;
      border: 2px solid black; /* 👈 added stroke */
    }
    .recent-activity {
      background-color: #073c63ff;
    }
    .recent-activity h4 {
      font-size: 1.2rem;
      margin-bottom: 15px;
      color: white;
      display: flex;
      align-items: center;
    }
    .activity-list {
      list-style: none;
    }

    .activity-item {
      padding: 10px 0;
      border-bottom: 1px solid #eee;
    }

    .activity-details {
      display: flex;
      align-items: center;
      font-size: 0.95rem;
      color:  white;
    }

    .timestamp {
      font-size: 0.8rem;
      color: white;
      margin-left: 26px;
    }

    .no-activity {
      font-style: italic;
      color: white;
    }

    .graph-section {
      flex: 2;
      max-width: 100%;
      min-width: 350px;
    }

    .graph-section .btn-group {
      margin-bottom: 15px;
      display: flex;
      justify-content: center; /* 👈 centers the buttons */
    }

    .recent-activity h4, 
    .graph-section h4 {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 15px;
      text-align: center; 
    }
    .btn-group button {
      font-size: 0.9rem;
      padding: 6px 12px;
    }

    canvas {
      max-width: 100%;
      height: 300px !important;
    }

    #clientList ul {
      margin: 0;
      padding: 0;
    }

    #clientList li {
      font-size: 0.85rem;
      padding: 6px 10px;
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
<?php
$conn = new mysqli("localhost", "root", "", "u755652361_thesis");

// Get stats
$total = $active = $inactive = 0;
$total = $conn->query("SELECT COUNT(*) AS total FROM cliente")->fetch_assoc()['total'];
$active = $conn->query("SELECT COUNT(*) AS active FROM cliente WHERE is_active = 1")->fetch_assoc()['active'];
$inactive = $conn->query("SELECT COUNT(*) AS inactive FROM cliente WHERE is_active = 0")->fetch_assoc()['inactive'];

// Email stats
$today = date('Y-m-d');
$stmt = $conn->prepare("SELECT COUNT(*) AS sent_today FROM email_log WHERE DATE(sent_at) = ?");
$stmt->bind_param("s", $today);
$stmt->execute();
$sentToday = $stmt->get_result()->fetch_assoc()['sent_today'] ?? 0;
$stmt->close();

// Who received
$stmt = $conn->prepare("SELECT sent_to FROM email_log WHERE DATE(sent_at) = ?");
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();
$emails = [];
while ($row = $result->fetch_assoc()) {
    $emails = array_merge($emails, explode(',', $row['sent_to']));
}
$emails = array_unique($emails); // avoid duplicates
$stmt->close();

$clientNames = [];
if (!empty($emails)) {
    $placeholders = implode(',', array_fill(0, count($emails), '?'));
    $types = str_repeat('s', count($emails));
    $stmt = $conn->prepare("SELECT name FROM cliente WHERE email IN ($placeholders)");
    $stmt->bind_param($types, ...$emails);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $clientNames[] = $row['name'];
    }
    $stmt->close();
}

// Dates
$today = date('Y-m-d');
$sevenDaysFromNow = date('Y-m-d', strtotime('+7 days'));

// Due Soon Clients (today to 7 days from now)
$dueSoonClients = [];
$dueQuery = $conn->prepare("
    SELECT id, name, end_date 
    FROM cliente 
    WHERE DATE(end_date) BETWEEN ? AND ? 
    ORDER BY end_date ASC
");
$dueQuery->bind_param("ss", $today, $sevenDaysFromNow);
$dueQuery->execute();
$dueResult = $dueQuery->get_result();
while ($row = $dueResult->fetch_assoc()) {
    $dueSoonClients[] = $row;
}
$dueQuery->close();

// Overdue Clients (before today)
$overdueClients = [];
$overdueQuery = $conn->prepare("
    SELECT id, name, end_date 
    FROM cliente 
    WHERE DATE(end_date) < ? 
    ORDER BY end_date ASC
");
$overdueQuery->bind_param("s", $today);
$overdueQuery->execute();
$overdueResult = $overdueQuery->get_result();
while ($row = $overdueResult->fetch_assoc()) {
    $overdueClients[] = $row;
}
$overdueQuery->close();

$today = date('Y-m-d');

// Count how many unique clients were notified today
$notifQuery = $conn->prepare("
  SELECT COUNT(DISTINCT client_id) AS sentToday
  FROM daily_notification_log
  WHERE DATE(date_sent) = ?
");
$notifQuery->bind_param("s", $today);
$notifQuery->execute();
$notifResult = $notifQuery->get_result();
$notifRow = $notifResult->fetch_assoc();
$sentToday = $notifRow['sentToday'] ?? 0;
?> 

<!-- Dashboard Body -->
<div class="content">
  <div class="dashboard-content">
    <div class="stats-row">
  <div class="count-box total-box">
    <h4>Total Clients</h4>
    <div class="count"><?= $total ?></div>
  </div>
  <div class="count-box active-box">
    <h4>Active Clients</h4>
    <div class="count"><?= $active ?></div>
  </div>
  <div class="count-box inactive-box">
    <h4>Inactive Clients</h4>
    <div class="count"><?= $inactive ?></div>
  </div>
  <div class="count-box notify-box">
  <h4>Notified Clients</h4>
  <div class="count"><?= $sentToday ?></div>
  </div>
  </div>

  
<!-- Notification Section -->
<form action="notify_bulk.php" method="POST" id="bulkNotifyForm">
  <div class="client-alerts">

    <!-- Due Soon Clients -->
  <div class="client-box">
    <div class="box-header position-relative">
      <h4 class="mb-3">
        <i class="fas fa-calendar-alt me-2 text-primary"></i>
        Clients with Due Dates (Within 7 Days)
      </h4>
      <button type="button" class="minimize-btn btn btn-sm btn-link" data-bs-toggle="collapse" data-bs-target="#dueClients" aria-expanded="true">
        <i class="fas fa-minus"></i>
      </button>
    </div>

    <div id="dueClients" class="collapse show">
        <?php if (!empty($dueSoonClients)): ?>
          <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="form-check">
              <input type="checkbox" class="form-check-input select-all" data-target="due-soon" id="select-all-due">
              <label class="form-check-label" for="select-all-due">Select All</label>
            </div>
            <button type="submit" name="notify_both_due" value="1" class="btn btn-sm btn-warning">Notify (Email + SMS)</button>
          </div>
        <?php endif; ?>

        <hr>

        <ul class="client-list due-soon">
          <?php if (empty($dueSoonClients)): ?>
            <li class="no-due">No clients due in the next 7 days.</li>
          <?php else: ?>
            <?php foreach ($dueSoonClients as $client): ?>
              <li>
                <input class="form-check-input client-checkbox due-soon" type="checkbox" name="client_ids[]" value="<?= $client['id'] ?>">
                <div class="client-name"><?= htmlspecialchars($client['name']) ?></div>
                <span class="due-date"><i class="fas fa-clock me-1 text-warning"></i><?= htmlspecialchars(date('M j, Y', strtotime($client['end_date']))) ?></span>
                <a href="view_client.php?id=<?= $client['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>
        </ul>
      </div>
    </div>

    <!-- Overdue Clients -->
  <div class="client-box">
    <div class="box-header position-relative">
      <h4 class="mb-3">
        <i class="fas fa-calendar-alt me-2 text-primary"></i>
        Clients with Overdue
      </h4>
      <button type="button" class="minimize-btn btn btn-sm btn-link" data-bs-toggle="collapse" data-bs-target="#dueClients" aria-expanded="true">
        <i class="fas fa-minus"></i>
      </button>
    </div>

    <div id="dueClients" class="collapse show">
        <?php if (!empty($overdueClients)): ?>
          <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="form-check">
              <input type="checkbox" class="form-check-input select-all" data-target="overdue" id="select-all-overdue">
              <label class="form-check-label" for="select-all-overdue">Select All</label>
            </div>
            <button type="submit" name="notify_both_overdue" value="1" class="btn btn-sm btn-warning">Notify (Email + SMS)</button>
          </div>
        <?php endif; ?>

        <hr>

        <ul class="client-list overdue">
          <?php if (empty($overdueClients)): ?>
            <li class="no-due">No overdue clients.</li>
          <?php else: ?>
            <?php foreach ($overdueClients as $client): ?>
              <li>
                <input class="form-check-input client-checkbox overdue" type="checkbox" name="client_ids[]" value="<?= $client['id'] ?>">
                <div class="client-name"><?= htmlspecialchars($client['name']) ?></div>
                <span class="due-date"><i class="fas fa-clock me-1 text-danger"></i><?= htmlspecialchars(date('M j, Y', strtotime($client['end_date']))) ?></span>
                <a href="view_client.php?id=<?= $client['id'] ?>" class="btn btn-sm btn-outline-secondary">View</a>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>
        </ul>
      </div>
    </div>

  </div>
</form>
 
<!-- Graph and Recent Activity -->
<div class="stats-row">
  <div class="graph-section" style="flex: 2;">
      <h4>Graph of Clients</h4>
    <div class="btn-group mb-3" role="group">
        <button class="btn btn-outline-primary" onclick="loadChart('total')">Total</button>
        <button class="btn btn-outline-success" onclick="loadChart('active')">Active</button>
        <button class="btn btn-outline-danger" onclick="loadChart('inactive')">Inactive</button>
      </div>
      <canvas id="clientsChart"></canvas>
    </div>

    <div class="recent-activity">
      <h4><i class="fas fa-history me-2"></i>Recent Staff Activity</h4>
      <ul class="activity-list">
        <?php if (empty($recentLogs)): ?>
          <li class="no-activity">No recent activity.</li>
        <?php else: ?>
          <?php foreach ($recentLogs as $log): ?>
            <li class="activity-item">
              <div class="activity-details">
                <i class="fas fa-user-circle text-primary me-2"></i>
                <strong><?= htmlspecialchars($log['username']) ?></strong>&nbsp;
                <?= htmlspecialchars($log['action']) ?>&nbsp;
                <strong><?= htmlspecialchars($log['client_name']) ?></strong>
              </div>
              <div class="timestamp">
                <i class="fas fa-clock me-1 text-muted"></i><?= htmlspecialchars(date('M j, Y h:i A', strtotime($log['timestamp']))) ?>
              </div>
            </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</div>

<script>
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  sidebar.classList.toggle('collapsed');
  document.body.classList.toggle('sidebar-collapsed');
}
</script>

<script>
  document.querySelectorAll('.select-all').forEach(selectAll => {
    selectAll.addEventListener('change', function () {
      const targetClass = this.dataset.target;
      const checkboxes = document.querySelectorAll('.client-checkbox.' + targetClass);
      checkboxes.forEach(cb => cb.checked = this.checked);
    });
  });
</script>

<script>
let chart;
function loadChart(type) {
  fetch(`dashboard.php?chart=${type}`)
    .then(res => res.json())
    .then(data => {
      const labels = data.map(d => d.month);
      const values = data.map(d => d.total);
      if (chart) chart.destroy();
      const ctx = document.getElementById('clientsChart').getContext('2d');
      chart = new Chart(ctx, {
        type: 'line',
        data: {
          labels,
          datasets: [{
            label: `Clients (${type})`,
            data: values,
            backgroundColor: 'rgba(0, 123, 255, 0.2)',
            borderColor: '#007bff',
            borderWidth: 2,
            fill: true,
            tension: 0.4
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: { beginAtZero: true }
          }
        }
      });
    });
}
window.onload = () => loadChart('total');
</script>
 
</div>
<?php if (isset($_SESSION['notify_modal_message'])): ?>
  <!-- Modal HTML -->
  <div class="modal fade" id="notifySuccessModal" tabindex="-1" aria-labelledby="notifySuccessLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="notifySuccessLabel">Notification Sent</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <?= $_SESSION['notify_modal_message']; ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Show the modal once the page has loaded
    window.addEventListener('DOMContentLoaded', () => {
      const notifyModal = new bootstrap.Modal(document.getElementById('notifySuccessModal'));
      notifyModal.show();
    });
  </script>

  <?php unset($_SESSION['notify_modal_message']); ?>
<?php endif; ?>
</body>
</html>
