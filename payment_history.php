<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "u755652361_thesis";

$connection = new mysqli($servername, $username, $password, $database);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$clientId = $_GET['id']; // this is the current client being viewed

// Get the current client's name
$stmt = $connection->prepare("SELECT name FROM cliente WHERE id = ?");
$stmt->bind_param("i", $clientId);
$stmt->execute();
$result = $stmt->get_result();
$currentClient = $result->fetch_assoc();

$clientName = $currentClient['name'];

// Get full history by name
$stmt = $connection->prepare("SELECT * FROM cliente WHERE name = ? ORDER BY start_date ASC");
$stmt->bind_param("s", $clientName);
$stmt->execute();
$historyResult = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>HKBC Admin System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
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
      width: 100%;
      margin-top: 60px;
      margin-left: 260px;
    }

    .sidebar.collapsed ~ .content {
      margin-left: 80px;
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
    .content h3 {
      margin-top: 30px;
      margin-bottom: 20px;
      font-weight: 600;
    }

    hr {
      border-top: 1px solid #ccc;
      margin: 40px 0 20px;
    }
    .table-container {
    padding: 20px;
    }

    table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    thead {
    background-color: #002244;
    color: white;
    }

    th, td {
    padding: 12px 15px;
    text-align: left;
    border: 1px solid #ddd;
    }

    tbody tr:nth-child(even) {
    background-color: #f9f9f9;
    }

    tbody tr:hover {
    background-color: #f1f1f1;
    }

    .sidebar.collapsed ~ .content .table-container {
    margin-left: 80px;
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
  <div class="table-container">
    <h3>Client History</h3>
    <table>
      <thead>
        <tr>
          <th>Policy #</th>
          <th>Name</th>
          <th>Insurance Provider</th>
          <th>Start Date</th>
          <th>Policy Status</th>
          <th>Operation</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $historyResult->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['policy_number']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['provider']) ?></td>
            <td><?= date('F j, Y', strtotime($row['start_date'])) ?></td>
            <td><?= $row['is_active'] ? 'Active' : 'Inactive' ?></td>
            <td>
              <a href="view_client.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary" title="View Profile">
                <i class="fas fa-user"></i> 
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
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

</body>
</html>

