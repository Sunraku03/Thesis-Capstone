<?php
session_start();

$conn = new mysqli("localhost", "root", "", "u755652361_thesis");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all unique months for dropdown
$monthResult = $conn->query("
    SELECT DISTINCT DATE_FORMAT(login_time, '%Y-%m') AS month FROM activity_log WHERE login_time IS NOT NULL AND login_time > '2000-01-01'
    UNION
    SELECT DISTINCT DATE_FORMAT(logout_time, '%Y-%m') FROM activity_log WHERE logout_time IS NOT NULL AND logout_time > '2000-01-01'
    UNION
    SELECT DISTINCT DATE_FORMAT(timestamp, '%Y-%m') FROM client_activity_log WHERE timestamp IS NOT NULL AND timestamp > '2000-01-01'
    ORDER BY month DESC
");

$months = [];
while ($row = $monthResult->fetch_assoc()) {
    $months[] = $row['month'];
}

// Get all users
$userResult = $conn->query("SELECT id, username FROM users ORDER BY username ASC");
$users = [];
while ($row = $userResult->fetch_assoc()) {
    $users[] = $row;
}

// Filter inputs
$filterMonth = $_GET['filter_month'] ?? '';
$filterUser = $_GET['filter_user'] ?? '';
$filterAction = $_GET['filter_action'] ?? '';

$escapedMonth = $conn->real_escape_string($filterMonth);
$escapedUser = $conn->real_escape_string($filterUser);
$escapedAction = $conn->real_escape_string($filterAction);

$where1 = [];
$where2 = [];
$where3 = [];

if (!empty($escapedMonth)) {
    $where1[] = "DATE_FORMAT(al.login_time, '%Y-%m') = '$escapedMonth'";
    $where2[] = "DATE_FORMAT(al.logout_time, '%Y-%m') = '$escapedMonth'";
    $where3[] = "DATE_FORMAT(cal.timestamp, '%Y-%m') = '$escapedMonth'";
}
if (!empty($escapedUser)) {
    $where1[] = "u.username LIKE '%$escapedUser%'";
    $where2[] = "u.username LIKE '%$escapedUser%'";
    $where3[] = "u.username LIKE '%$escapedUser%'";
}
if (!empty($escapedAction)) {
    if (in_array($escapedAction, ['Created', 'Edited', 'Notified'])) {
        $where3[] = "cal.action = '$escapedAction'";
    }
} else {
    // No action filter: show all login/logout
    $where1[] = "al.login_time IS NOT NULL";
    $where2[] = "al.logout_time IS NOT NULL";
}

$sqlParts = [];

if (empty($escapedAction)) {
    // Only include these when no specific action is filtered
    $sqlParts[] = "
        SELECT al.user_id, u.username, 'Logged In' AS event, al.login_time AS timestamp
        FROM activity_log al
        JOIN users u ON al.user_id = u.id
        " . (count($where1) ? "WHERE " . implode(" AND ", $where1) : "") . "
    ";

    $sqlParts[] = "
        SELECT al.user_id, u.username, 'Logged Out' AS event, al.logout_time AS timestamp
        FROM activity_log al
        JOIN users u ON al.user_id = u.id
        " . (count($where2) ? "WHERE " . implode(" AND ", $where2) : "") . "
    ";
}

// Always include client_activity_log entries
$sqlParts[] = "
    SELECT
      cal.user_id,
      u.username,
      CASE
        WHEN cal.action = 'Notified' AND cal.client_id IS NULL THEN
          CONCAT('<strong>', u.username, '</strong> Notified all Clients')
        WHEN cal.action = 'Notified' AND cal.client_id IS NOT NULL THEN
          CONCAT('<strong>', u.username, '</strong> Notified <strong>', c.name, '</strong>')
        ELSE
          CONCAT('<strong>', u.username, '</strong> ', cal.action, ' <strong>', c.name, '</strong>')
      END AS event,
      cal.timestamp
    FROM client_activity_log cal
    JOIN users u ON cal.user_id = u.id
    LEFT JOIN cliente c ON cal.client_id = c.id
    " . (count($where3) ? "WHERE " . implode(" AND ", $where3) : "") . "
";

$sql = implode(" UNION ALL ", $sqlParts) . " ORDER BY timestamp DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Activity Log</title>
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
  />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  />
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
      margin-left: 250px;
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

    /* Main Container */
    .container-main {
      padding: 20px;
      box-sizing: border-box;
      transition: margin-left 0.3s ease, width 0.3s ease;
      margin-left: 250px;
      width: calc(100% - 250px);
    }

    /* When sidebar is collapsed */
    .sidebar.collapsed ~ .container-main {
      margin-left: 60px;
      width: calc(100% - 60px);
    }
    /* Filter Box Card */
    .card {
      background-color: #fec053;
      border: 2px solid black;
      border-radius: 8px;
      margin-top: 60px;
    }

    /* Filter Labels */
    .card .form-label {
      font-weight: 600;
      color: #002244;
    }  

    /* Filter Dropdowns and Inputs */
    .card .form-select,
    .card .form-control {
      border-radius: 6px;
      box-shadow: none;
      transition: border-color 0.2s ease-in-out;
    }

    .card .form-select:focus,
    .card .form-control:focus {
      border-color: #002244;
      box-shadow: 0 0 0 0.1rem rgba(0, 34, 68, 0.25);
    }

    /* Section Header */
    .card h5 {
      color: #002244;
      font-weight: 700;
      border-bottom: 1px solid #ccc;
      padding-bottom: 10px;
    }

    /* Calendar Icon */
    .card .form-label i {
      color: #002244;
    }
    /* Dropdown options styling */
    .card .form-select option {
      background-color: #002244;
      color: white;
    }

    /* On focus */
    .card .form-control:focus,
    .card .form-select:focus {
      background-color: #002244;
      color: white;
      border-color: #ffffff;
      box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.2);
    }
    .card .form-select:focus,
    .card .form-select option:checked,
    .card .form-select option:hover,
    .card .form-select option:focus {
      background-color: #002244 !important;
      color: white !important;
    }

    /* Also for consistent input field focus */
    .card .form-control:focus {
      background-color: #002244 !important;
      color: white !important;
      border-color: #ffffff;
      box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.2);
    }

    /* Style filter inputs */
    .filter-box .form-control,
    .filter-box .form-select {
      background-color: #002244; /* very light blue */
      border: 1px solid black;
      color: white;
    }

    /* On focus */
    .filter-box .form-control:focus,
    .filter-box .form-select:focus {
      background-color: #fff;
      border-color: #002244;
      box-shadow: 0 0 0 0.2rem rgba(0, 34, 68, 0.2);
    }
    /* Keep search bar background white on focus */
    #filter_user_search {
      background-color: white;
      color: black;
      border: 1px solid black;
    }

    #filter_user_search:focus {
      background-color: white !important;
      color: black !important;
      border: 1px solid #002244;
      box-shadow: 0 0 0 0.2rem rgba(0, 34, 68, 0.2);
    }

    /* 1) Wrap the table so its body scrolls */
    .table-wrapper {
      max-height: 400px;    /* adjust height as needed */
      overflow-y: auto;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      border: 2px solid black;
    }

    /* 2) Base table styling */
    table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
    }

    /* 3) Make the thead row sticky */
    thead th {
      position: sticky;
      top: 0;
      background-color: #002244;
      color: white;
      z-index: 2;
      padding: 12px;
      border: 1px solid #ddd;
      text-align: left;
    }

    /* 4) Body cells */
    tbody td {
      border: 1px solid #ddd;
      padding: 12px;
      text-align: left;
      background-color: white;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    tr:hover td {
      background-color: #f1f1f1;
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

<!-- Main Content -->
<div class="container-main">

<!-- Filter by Month and Staff -->
<form method="GET" class="mb-3">
  <div class="card p-3 shadow-sm filter-box">
    <h5 class="mb-3"> Activity Log</h5>

    <div class="row g-3">
      <!-- User Search -->
      <div class="col-md-4">
        <label for="filter_user_search" class="form-label"><strong>User</strong> <i class="fas fa-user me-2"></i></label>
          <input type="text" name="filter_user" id="filter_user_search" class="form-control" placeholder="Enter username..." value="<?= htmlspecialchars($filterUser) ?>">
      </div>

      <!-- Action Filter -->
      <div class="col-md-4">
        <label for="filter_action" class="form-label"><strong>Action</strong> <i class="fas fa-tasks me-2"></i></label>
        <select name="filter_action" id="filter_action" class="form-select" onchange="this.form.submit()">
          <option value="">All Actions</option>
          <option value="Created" <?= isset($_GET['filter_action']) && $_GET['filter_action'] === 'Created' ? 'selected' : '' ?>>Created</option>
          <option value="Edited" <?= isset($_GET['filter_action']) && $_GET['filter_action'] === 'Edited' ? 'selected' : '' ?>>Edited</option>
          <option value="Notified" <?= isset($_GET['filter_action']) && $_GET['filter_action'] === 'Notified' ? 'selected' : '' ?>>Notified</option>
        </select>
      </div>

      <!-- Month Filter -->
      <div class="col-md-4">
        <label for="filter_month" class="form-label"><strong>Month</strong> <i class="far fa-calendar-alt ms-1"></i></label>
        <select name="filter_month" id="filter_month" class="form-select" onchange="this.form.submit()">
          <option value="">All Months</option>
          <?php foreach ($months as $month): ?>
            <option value="<?= htmlspecialchars($month) ?>" <?= $filterMonth === $month ? 'selected' : '' ?>>
              <?= date('F Y', strtotime($month)) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>
</form>

  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th><i class="fas fa-user me-2"></i>User</th>
          <th><i class="fas fa-tasks me-2"></i>Action</th>
          <th><i class="far fa-clock me-2"></i>Timestamp</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td><?= $row['event'] ?></td>
              <td><?= htmlspecialchars($row['timestamp']) ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="3" class="text-center">No activity found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
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
document.getElementById('filter_user_search').addEventListener('input', function () {
  if (this.value.length >= 10 || this.value.length === 0) {
    this.form.submit();
  }
});
</script>

</body>
</html>

<?php
$conn->close();
?>
