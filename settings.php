<?php
session_start();
require 'phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$host         = "localhost";
$username     = "root";
$password     = "";
$database     = "u755652361_thesis";

$success = "";
$error = "";
if (isset($_POST['backup'])) {
    $backupDir = 'backups/';
    $fileName = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
    $filePath = $backupDir . $fileName;

    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0777, true);
    }

    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $tables = [];
    $result = $conn->query("SHOW TABLES");
    while ($row = $result->fetch_row()) {
        $tables[] = $row[0];
    }

    $sqlScript = "";
    foreach ($tables as $table) {
        // Table structure
        $createTable = $conn->query("SHOW CREATE TABLE `$table`")->fetch_row();
        $sqlScript .= "\n\n" . $createTable[1] . ";\n\n";

        // Table data
        $rows = $conn->query("SELECT * FROM `$table`");
        $columns = $rows->field_count;

        while ($row = $rows->fetch_row()) {
            $sqlScript .= "INSERT INTO `$table` VALUES(";
            for ($j = 0; $j < $columns; $j++) {
                $row[$j] = $row[$j] === null ? "NULL" : $conn->real_escape_string($row[$j]);
                $sqlScript .= '"' . $row[$j] . '"';
                if ($j < $columns - 1) $sqlScript .= ', ';
            }
            $sqlScript .= ");\n";
        }
        $sqlScript .= "\n";
    }

    file_put_contents($filePath, $sqlScript);
    $conn->close();

    if (file_exists($filePath)) {
        $success = "Backup created successfully: <strong>$fileName</strong>";
    } else {
        $error = "Backup failed. File could not be saved.";
    }
}

// Handle Restore
if (isset($_POST['restore'])) {
    if ($_FILES['sql_file']['error'] === 0) {
        $sqlFile = $_FILES['sql_file']['tmp_name'];
        $command = "mysql --user=$username --password=$password --host=$host $database < $sqlFile";
        system($command, $output);
        $success = "Database restored successfully.";
    } else {
        $error = "Failed to upload SQL file.";
    }
}

?>

<?php

// Only allow logged-in users
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Only admins can create new users
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
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

    .btn {
      padding: 12px 24px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn-primary {
      background-color: #003366;
      color: #fff;
    }

    .btn-primary:hover {
      background-color: #002244;
    }

    .btn-danger {
      background-color: #cc0000;
      color: #fff;
    }

    .btn-danger:hover {
      background-color: #990000;
    }

    .alert-message {
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 500;
      text-align: center;
    }

    .alert-success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .alert-error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .backup-list ul {
      margin-top: 10px;
      padding-left: 20px;
    }
    .user-section {
      background-color: #f4f8fc;
      border: 2px solid black;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
      padding: 20px;
      border-radius: 8px;
      flex: 1 1 100%; /* default: full width */
      margin-right: 20px;
    }
    .user-section table {
      background-color: #ffffff;
      border: 1px solid #dee2e6;
      border-radius: 8px;
      overflow: hidden;
    }

    .user-section thead {
      background-color: #003366;
      color: white;
    }

    .user-section tbody tr:nth-child(odd) {
      background-color: #f2f6fa;
    }

    .user-section tbody tr:nth-child(even) {
      background-color: #ffffff;
    }

    .user-section td, .user-section th {
      vertical-align: middle;
      text-align: center;
    }
    select.form-select, button.btn {
      font-size: 16px;
      padding: 10px 12px;
    }
   .export-section {
  background-color: #f4f8fc;
  border: 2px solid black;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
  padding: 20px;
  border-radius: 8px;
  margin-bottom: 20px;
  margin-right: 20px;
}

.export-section h4 {
  font-weight: bold;
  margin-bottom: 20px;
}
.export-section select {
  min-height: 38px;
}

#filter_months {
  height: auto;
}

    @media (min-width: 768px) {
      .user-section {
        flex: 1 1 100%; /* side by side at medium+ screens */
      }
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
  <?php if ($success): ?>
    <div class="alert-message alert-success"><?= $success ?></div>
  <?php elseif ($error): ?>
    <div class="alert-message alert-error"><?= $error ?></div>
  <?php endif; ?>

<?php if ($isAdmin): ?>
<div class="d-flex flex-wrap gap-4 mb-4">

  <!-- Create New User Section -->
  <div class="user-section p-4 rounded flex-fill" style="min-width: 300px; max-width: 48%;">
    <h4><i class="fas fa-user-plus me-2 text-primary"></i> Create New User</h4>
    <form action="create_user_process.php" method="POST" id="createUserForm">
      <div class="mb-3">
        <label for="new_username" class="form-label">Username</label>
        <input type="text" class="form-control" id="new_username" name="username" required>
      </div>
      <div class="mb-3 position-relative">
        <label for="new_password" class="form-label">Password</label>
        <input type="password" class="form-control" id="new_password" name="password" required>
        <i class="fa-solid fa-eye-slash toggle-password"
           toggle="#new_password"
           style="position:absolute; top:70%; right:15px; transform:translateY(-50%); cursor:pointer; color: #555;"></i>
      </div>
      <div class="mb-3">
        <label for="new_role" class="form-label">Role</label>
        <select class="form-control" id="new_role" name="role" required>
          <option value="staff">Staff</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmCreateModal">
        Create User
      </button>

      <!-- Confirmation Modal -->
      <div class="modal fade" id="confirmCreateModal" tabindex="-1" aria-labelledby="confirmCreateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="confirmCreateModalLabel">Confirm Create User</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
              <p>Are you sure you want to create this user?</p>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <!-- Actual submit button inside modal -->
              <button type="submit" class="btn btn-primary" form="createUserForm">Yes, Create</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

  <!-- Manage Staff Accounts Section -->
  <div class="user-section p-4 rounded flex-fill" style="min-width: 300px; max-width: 48%;">
    <h4><i class="fas fa-users-cog me-2 text-primary"></i> Manage Staff Accounts</h4>
    <table class="table">
      <thead>
        <tr>
          <th>Username</th>
          <th>Role</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $conn = new mysqli("localhost", "root", "", "u755652361_thesis");
        $result = $conn->query("SELECT id, username, role, is_active FROM users WHERE role = 'staff'");
        while($row = $result->fetch_assoc()):
        ?>
          <tr>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['role']) ?></td>
            <td><?= $row['is_active'] ? 'Active' : 'Disabled' ?></td>
            <td>
              <form action="toggle_user_status.php" method="POST" style="display:inline;">
                <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                <input type="hidden" name="new_status" value="<?= $row['is_active'] ? 0 : 1 ?>">
                <button type="submit" class="btn btn-sm btn-<?= $row['is_active'] ? 'danger' : 'success' ?>">
                  <?= $row['is_active'] ? 'Disable' : 'Enable' ?>
                </button>
              </form>

              <!-- Delete Button triggers modal -->
              <button class="btn btn-sm btn-outline-danger ms-2" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['id'] ?>">
                <i class="fas fa-trash-alt"></i>
              </button>

              <!-- Modal -->
              <div class="modal fade" id="deleteModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $row['id'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                      <h5 class="modal-title" id="deleteModalLabel<?= $row['id'] ?>">Confirm Delete</h5>
                      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                      <p>Are you sure you want to delete the account <strong><?= htmlspecialchars($row['username']) ?></strong>?</p>
                      <p class="text-danger mb-0"><small>This action cannot be undone.</small></p>
                    </div>
                    <div class="modal-footer justify-content-center">
                      <form action="delete_user.php" method="POST">
                        <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

</div>
    
      <hr>
        <div class="user-section p-4 rounded mb-4"  >
          <h4><i class="fas fa-database me-2 text-primary"></i> Backup & Restore Database</h4>

          <form method="post" class="mb-4">
            <button type="submit" name="backup" class="btn btn-primary">Create Backup</button>
          </form>

          <div class="backup-list mb-4">
            <h5>Available Backups:</h5>
            <?php
            $backupDir = 'backups/';
            if (is_dir($backupDir)) {
              $files = array_reverse(glob($backupDir . '*.sql'));
              if ($files) {
                echo "<ul>";
                foreach ($files as $file) {
                  echo "<li><a href='$file' download>" . basename($file) . "</a></li>";
                }
                echo "</ul>";
              } else {
                echo "<p>No backups found.</p>";
              }
            }
            ?>
          </div>

          <form method="post" enctype="multipart/form-data" class="mb-4">
            <div class="mb-3">
              <label for="sql_file" class="form-label">Restore from SQL File</label>
              <input type="file" class="form-control" name="sql_file" accept=".sql" required>
            </div>
            <button type="submit" name="restore" class="btn btn-danger">Restore Database</button>
          </form>
        </div>
    <hr>
    <?php endif; ?>

<div class="export-section">
  <h4><i class="fas fa-file-export me-2 text-success"></i>Export Clients to Excel</h4>
  <form action="export_clients.php" method="post" class="row g-3">

    <!-- Year Filter -->
    <div class="col-md-3">
      <label for="filter_year" class="form-label">Year</label>
      <select name="filter_year" id="filter_year" class="form-select">
        <option value="">All Years</option>
        <?php
        $currentYear = date("Y");
        for ($y = $currentYear; $y >= $currentYear - 10; $y--) {
            echo "<option value=\"$y\">$y</option>";
        }
        ?>
      </select>
    </div>

    <!-- Status Filter -->
    <div class="col-md-3">
      <label for="filter_status" class="form-label">Status</label>
      <select name="filter_status" id="filter_status" class="form-select">
        <option value="">All Statuses</option>
        <option value="Active">Active</option>
        <option value="Expired">Expired</option>
        <option value="Cancelled">Cancelled</option>
        <!-- Add more statuses as needed -->
      </select>
    </div>

    <!-- Provider Filter -->
    <div class="col-md-3">
      <label for="filter_provider" class="form-label">Insurance Provider</label>
      <select name="filter_provider" id="filter_provider" class="form-select">
        <option value="">All Providers</option>
        <?php
        // Dynamically fetch unique providers from database
        $providerResult = $conn->query("SELECT DISTINCT provider FROM cliente WHERE provider IS NOT NULL AND provider != ''");
        while ($prov = $providerResult->fetch_assoc()) {
            $provider = htmlspecialchars($prov['provider']);
            echo "<option value=\"$provider\">$provider</option>";
        }
        ?>
      </select>
    </div>

    <!-- Month Filter -->
    <div class="col-md-9">
      <label for="filter_months" class="form-label">Select Months</label>
      <select name="filter_months[]" id="filter_months" class="form-select" multiple size="6">
        <?php
        $monthNames = [
          1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
          5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
          9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        foreach ($monthNames as $num => $name) {
            echo "<option value=\"$num\">$name</option>";
        }
        ?>
      </select>
      <small class="text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple.</small>
    </div>

    <!-- Export Button -->
    <div class="col-12">
      <button type="submit" class="btn btn-success">Export</button>
    </div>
  </form>
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
  document.addEventListener("DOMContentLoaded", function () {
    const toggleIcon = document.querySelector(".toggle-password");
    const passwordInput = document.querySelector(toggleIcon.getAttribute("toggle"));

    toggleIcon.addEventListener("click", function () {
      const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
      passwordInput.setAttribute("type", type);
      toggleIcon.classList.toggle("fa-eye");
      toggleIcon.classList.toggle("fa-eye-slash");
    });
  });
</script>
</body>
</html>
