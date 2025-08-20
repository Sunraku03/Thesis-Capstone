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

// Pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Filters
$status = $_GET['status'] ?? '';
$provider = $_GET['provider'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

$where = [];
if ($status) $where[] = "status = '" . $conn->real_escape_string($status) . "'";
if ($provider) $where[] = "provider = '" . $conn->real_escape_string($provider) . "'";
if ($start_date && $end_date) $where[] = "start_date BETWEEN '$start_date' AND '$end_date'";
$whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

// Total count
$totalRes = $conn->query("SELECT COUNT(*) as total FROM cliente $whereSQL");
$totalRows = $totalRes->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Data
$result = $conn->query("SELECT * FROM cliente $whereSQL ORDER BY id DESC LIMIT $limit OFFSET $offset");
$providerResult = $conn->query("SELECT DISTINCT provider FROM cliente WHERE provider IS NOT NULL AND provider != ''");
$providers = [];
while ($row = $providerResult->fetch_assoc()) {
    $providers[] = $row['provider'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>HKBC Reports</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Same sidebar/header styles as before... */
    body { margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; display: flex; }
    header { position: fixed; top: 0; left: 0; right: 0; height: 60px; background-color: white; color: black; padding: 10px 20px; z-index: 1000; display: flex; justify-content: space-between; align-items: center; }
    .header-left { display: flex; align-items: center; gap: 15px; }
    .header-right { display: flex; align-items: center; gap: 10px; }
    .title { font-size: 1.2rem; font-weight: bold; }
    .admin-profile { font-weight: 500; font-size: 0.95rem; color: white; display: flex; flex-direction: column; justify-content: center; padding: 20px; text-align: center; width: 100%; box-sizing: border-box; align-items: center; }
    .admin-name { font-weight: 500; margin-top: 8px; }
    .admin-icon { font-size: 80px; color: white; margin-left: -25%; }
    .sidebar { position: fixed; left: 0; width: 250px; height: 100%; background-color: #002244; color: white; transition: width 0.3s ease; overflow: hidden; display: flex; flex-direction: column; z-index: 1100; }
    .sidebar.collapsed { width: 60px; }
    .sidebar.collapsed .admin-profile { display: none; }
    .sidebar ul { list-style: none; padding: 0; margin: 0; width: 100%; }
    .sidebar li a { display: flex; align-items: center; padding: 15px 20px; color: white; text-decoration: none; transition: background 0.3s; }
    .sidebar li a:hover { background-color: #003366; }
    .sidebar i { margin-right: 10px; width: 20px; text-align: center; }
    .sidebar.collapsed .text { display: none; }
    .logout-btn { margin-top: auto; width: 100%; }
    .content { margin-top: 60px; margin-left: 250px; padding: 20px; width: 100%; }
    .sidebar.collapsed ~ .content { margin-left: 60px; }
    .toggle-btn { background: none; border: none; color: white; font-size: 2rem; padding: 10px 12px; text-align: left; width: 100%; cursor: pointer; }
  </style>
</head>
<body>

<header>
  <div class="header-left">
    <h2>h</h2>
  </div>
  <div class="header-right">
    <div class="title">HKBC INSURANCE SERVICES ADMIN SYSTEM</div>
    <img src="logo.png" alt="HKBC Logo" style="height: 60px; width: 60px; border-radius: 50%; background-color: white;">
  </div>
</header>

<div class="sidebar d-flex flex-column" id="sidebar">
  <button class="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
  <div class="admin-profile">
    <i class="fas fa-user-circle admin-icon"></i>
    <div class="admin-name"><?= htmlspecialchars($_SESSION['admin_name']) ?></div>
  </div>
  <ul class="flex-grow-1 w-100">
    <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> <span class="text">Dashboard</span></a></li>
    <li><a href="client.php"><i class="fas fa-users"></i> <span class="text">Client List</span></a></li>
    <li><a href="report.php"><i class="fas fa-archive"></i> <span class="text">Reports</span></a></li>
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
  <h3 class="mb-3">Client Reports</h3>
<div class="row align-items-end mb-3">
  <div class="col-md-2">
    <label>Status</label>
    <select id="status" class="form-select">
      <option value="">All</option>
      <option value="Active" <?= $status == 'Active' ? 'selected' : '' ?>>Active</option>
      <option value="Inactive" <?= $status == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
    </select>
  </div>

  <div class="col-md-3">
    <label>Provider</label>
    <select id="provider" class="form-select">
      <option value="">All</option>
      <?php foreach ($providers as $prov): ?>
        <option value="<?= $prov ?>" <?= $provider == $prov ? 'selected' : '' ?>><?= $prov ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="col-md-2">
    <label>Issued Date (From)</label>
    <input type="date" id="start_date" class="form-control" value="<?= $start_date ?>">
  </div>

  <div class="col-md-2">
    <label>Issued Date (To)</label>
    <input type="date" id="end_date" class="form-control" value="<?= $end_date ?>">
  </div>

  <div class="col-md-2 d-flex gap-2">
    <div class="w-100">
      <label class="invisible">Export</label>
      <a href="export_excel.php?<?= $_SERVER['QUERY_STRING'] ?>" class="btn btn-success w-100">Excel</a>
    </div>
    <div class="w-100">
      <label class="invisible">Export</label>
      <a href="export_word.php?<?= $_SERVER['QUERY_STRING'] ?>" class="btn btn-primary w-100">Word</a>
    </div>
    <div class="w-100">
      <label class="invisible">Print</label>
      <button class="btn btn-secondary w-100" onclick="printReport()">Print</button>
    </div>
  </div>
</div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Policy Number</th>
          <th>Name</th>
          <th>Provider</th>
          <th>Start Date</th>
          <th>Mortgage</th>
          <th>Amount Insured</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['policy_number']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['provider']) ?></td>
            <td><?= htmlspecialchars($row['start_date']) ?></td>
            <td><?= htmlspecialchars($row['mortgage']) ?></td>
            <td>₱<?= number_format((float)$row['amount_insured'], 2) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <nav>
    <ul class="pagination">
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
          <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
</div>
<script>
const filterIds = ['status', 'provider', 'start_date', 'end_date'];
filterIds.forEach(id => {
  document.getElementById(id).addEventListener('change', () => {
    const params = new URLSearchParams(window.location.search);
    filterIds.forEach(f => {
      params.set(f, document.getElementById(f).value);
    });
    window.location.search = params.toString();
  });
});
</script>

<script>
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  sidebar.classList.toggle('collapsed');
}
</script>

<script>
function printReport() {
  const printWindow = window.open('', '_blank');
  const tableHTML = document.querySelector('.table-responsive').innerHTML;
  const styles = `
    <style>
      body { font-family: 'Segoe UI', sans-serif; padding: 20px; }
      table { width: 100%; border-collapse: collapse; }
      th, td { border: 1px solid #000; padding: 8px; text-align: left; }
      th { background-color: #f2f2f2; }
      h2 { text-align: center; margin-bottom: 20px; }
    </style>
  `;
  const logo = `<div style="text-align:center;"><img src="logo.png" style="height: 60px; border-radius: 50%; background-color:white;"><h2>HKBC Client Report</h2></div>`;

  printWindow.document.write(`
    <html>
      <head>
        <title>Print Report</title>
        ${styles}
      </head>
      <body>
        ${logo}
        ${tableHTML}
      </body>
    </html>
  `);
  printWindow.document.close();
  printWindow.focus();
  printWindow.print();
}
</script>
</body>
</html>
