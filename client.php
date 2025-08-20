<?php
session_start();
require 'phpspreadsheet/vendor/autoload.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
} 
$servername = "localhost";
$username = "root";
$password = "";
$database = "u755652361_thesis";

$connection = new mysqli($servername, $username, $password, $database);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

if (isset($_POST['import_excel'])) {
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
        $file = $_FILES['excel_file']['tmp_name'];
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $rowCount = 0;

        foreach ($rows as $index => $row) {
            if ($index === 0 || empty(trim($row[0]))) continue; // Skip header/blank

            // Convert Excel dates
            $start_date = is_numeric($row[7]) ? Date::excelToDateTimeObject($row[7])->format('Y-m-d') : trim($row[7]);
            $end_date = is_numeric($row[8]) ? Date::excelToDateTimeObject($row[8])->format('Y-m-d') : trim($row[8]);
            $date_remittance = is_numeric($row[37]) ? Date::excelToDateTimeObject($row[37])->format('Y-m-d') : trim($row[37]);

            $status = trim($row[28]);
            $is_active = strtolower($status) === 'active' ? 1 : 0;

            // Escape all values
            for ($i = 0; $i <= 40; $i++) {
                $row[$i] = $connection->real_escape_string(trim($row[$i]));
            }

            $date_remittance = $connection->real_escape_string($date_remittance);

            // SQL Insert
            $sql = "
                INSERT INTO cliente (
                    policy_number, name, provider, facebook, contact_number, email, address,
                    start_date, end_date, vehicle_unit, chasis_no, motor_no, plate_no, vehicle_color,
                    amount_insured, bipd, pa, aon, net_remitting, hkbc_net, mark_up, late_charges,
                    cancelled_income, reinstatement_fee, make_up_agent, comission, source, mortgage,
                    status, or_number, payment_1, payment_2, payment_3, payment_4, payment_5, payment_6,
                    payment_status, date_remittance, ctpl, bank_status, remarks, is_active
                ) VALUES (
                    '$row[0]', '$row[1]', '$row[2]', '$row[3]', '$row[4]', '$row[5]', '$row[6]',
                    '$start_date', '$end_date', '$row[9]', '$row[10]', '$row[11]', '$row[12]', '$row[13]',
                    '$row[14]', '$row[15]', '$row[16]', '$row[17]', '$row[18]', '$row[19]', '$row[20]', '$row[21]',
                    '$row[22]', '$row[23]', '$row[24]', '$row[25]', '$row[26]', '$row[27]',
                    '$status', '$row[29]', '$row[30]', '$row[31]', '$row[32]', '$row[33]', '$row[34]', '$row[35]',
                    '$row[36]', '$date_remittance', '$row[38]', '$row[39]', '$row[40]', $is_active
                )
            ";

            $result = $connection->query("SELECT * FROM cliente"); 

            if ($result) {
                $newClientId = $connection->insert_id;
                $currentUserId = $_SESSION['user_id'] ?? 0;

                $logSql = "INSERT INTO client_activity_log (client_id, user_id, action) VALUES ($newClientId, $currentUserId, 'Created')";
                $connection->query($logSql);

                $rowCount++;
            } else {
                echo "<div class='alert alert-danger'>Error on row $index: {$connection->error}</div>";
            }
        }

}
}
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>HKBC Admin System</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
      margin-top: 60px;
      margin-left: 250px;
    }

    /* Main Container */
    .container-main {
      margin-left: 250px;
      width: calc(100% - 250px);
      padding: 20px;
      transition: margin-left 0.3s ease, width 0.3s ease;
    }

    /* When sidebar is collapsed */
    .sidebar.collapsed ~ .container-main {
      margin-left: 60px;
      width: calc(100% - 60px);
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
    h2 {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* modern clean font */
      font-size: 1.50rem; /* smaller size than before */
      font-weight: 600; /* semi-bold for emphasis */
      color: white; /* matching your theme color */
      margin-bottom: 18px;
      text-align: center;
    }
    .flex-container {
      display: flex;
      flex-wrap: nowrap;
      align-items: stretch;
      gap: 10px;
      background-color: #002244;
      border: 2px solid black;
      border-radius: 4px;
      padding: 10px;
      margin-top: 30px;
      box-sizing: border-box;
    }

    .flex-item.stretch {
      flex: 1 1 160px;      /* grow and shrink with min width */
      max-width: 100%;      /* prevent overflow */
      box-sizing: border-box;
    }

    .flex-item.stretch.d-flex {
      flex: 0 0 auto;        /* don't stretch icon buttons */
      gap: 10px;
    }
    
    .flex-container input,
    .flex-container select {
      width: 100%;
      background-color: white;
      color: black;
      border: 1px solid black;
      padding: 6px 10px;
      border-radius: 6px;
      font-size: 0.95rem;
      box-sizing: border-box;
    }

    .flex-container input:focus,
    .flex-container select:focus {
      border-color: #002244;
      box-shadow: 0 0 0 0.15rem rgba(0, 34, 68, 0.3);
    }
    .table-container {
      width: 100%;
      overflow-x: auto; 
      padding: 10px;
    }
    table {
      width: 100%; 
      border-collapse: collapse;
      font-family: Arial, sans-serif;
      table-layout: auto; 
      border-left: 2px solid black;  /* left border */
      border-right: 2px solid black; /* right border */ 
      border-bottom: 2px solid black; /* right border */ 
    }
    th, td {
      padding: 8px;
      border: none;
      text-align: center; /* center all table text */
      vertical-align: middle; /* vertically center too */
      
    }
    th {
      border-top: 2px solid black;  /* left border */
      background-color: #002244;
      color: white;
      font-weight: bold;
      cursor: pointer;
      user-select: none;
    }
    tr {
      border-bottom: 1px solid black; /* light gray row separator */
      border-radius: 5px;
    }

    thead tr {
      border-bottom: 2px solid #333; /* stronger line for header row */
    }
    tr:hover {
      background-color: #f9f9f9;
    }
    .btn-sm {
      font-size: 0.75rem;
      padding: 4px 8px;
    }
    select, input[type="text"] {
      padding: 5px 10px;
      font-size: 16px;
      border-radius: 5px;
      border: 1px solid #ccc;
      min-width: 150px;
    }
    #pagination {
      gap: 8px;
      flex-wrap: wrap;
    }

    #pagination button {
      padding: 8px 16px;
      border: 1px solid #002244;
      background-color: white;
      color: #002244;
      font-weight: 500;
      border-radius: 5px;
      transition: background-color 0.2s, color 0.2s;
    }

    #pagination button:hover {
      background-color: #002244;
      color: white;
    }

    #pagination button.active {
      background-color: #002244;
      color: white;
      font-weight: bold;
    }

    #pagination button:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }
    .custom-tooltip {
    position: absolute;
    background: #333;
    color: white;
    padding: 5px 8px;
    border-radius: 5px;
    font-size: 0.8rem;
    z-index: 9999;
    pointer-events: none;
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

  <main class="container-main">
    <h2>HKBC Client List</h2>
    <form method="GET" enctype="multipart/form-data" action="client.php" id="filterForm">
      <div class="flex-container">
        <div class="flex-item stretch">
          <input type="text" name="search" id="search"
            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
            placeholder="Search Policy #/Name"
            oninput="handleSearchInput()" />
        </div>

        <div class="flex-item stretch">
          <select name="status" id="status" class="filter-status" onchange="document.getElementById('filterForm').submit()">
            <option value="1" <?= (isset($_GET['status']) && $_GET['status'] === '1') ? 'selected' : '' ?>>Active </option>
            <option value="0" <?= (isset($_GET['status']) && $_GET['status'] === '0') ? 'selected' : '' ?>>Inactive </option>
          </select>
        </div>

        <div class="flex-item stretch">
          <select name="provider" id="provider" onchange="document.getElementById('filterForm').submit()">
            <option value="">Insurance Provider</option>
            <?php
              $providerResult = $connection->query("SELECT DISTINCT provider FROM cliente ORDER BY provider ASC");
              while ($prov = $providerResult->fetch_assoc()) {
                $selected = (isset($_GET['provider']) && $_GET['provider'] === $prov['provider']) ? 'selected' : '';  
                echo "<option value=\"{$prov['provider']}\" $selected>" . htmlspecialchars($prov['provider']) . "</option>";
              }
            ?>
          </select>
        </div>
            <div class="flex-item stretch">
              <select name="year" id=" year" onchange="document.getElementById('filterForm').submit()">
                <option value=""> Year </option>
                <?php
                  $currentYear = date('Y');
                  for ($y = $currentYear; $y >= 2023; $y--) {
                      $selected = (isset($_GET['year']) && $_GET['year'] == $y) ? 'selected' : '';
                      echo "<option value=\"$y\" $selected>$y</option>";
                  }
                ?>
              </select>
            </div>  
        <div class="flex-item stretch">
          <select name="month" id="month" onchange="document.getElementById('filterForm').submit()">
            <option value="">Month</option>
            <?php
              for ($m = 1; $m <= 12; $m++) {
                $monthValue = str_pad($m, 2, '0', STR_PAD_LEFT);
                $monthName = date('F', mktime(0, 0, 0, $m, 1));
                $selected = (isset($_GET['month']) && $_GET['month'] === $monthValue) ? 'selected' : '';
                echo "<option value='$monthValue' $selected>$monthName</option>";
              }
            ?>
          </select>
        </div>

        <div class="flex-item stretch">
          <select name="sort" id="sort" onchange="document.getElementById('filterForm').submit()">
            <option value="">Sort By</option>
            <option value="name_asc" <?php if (isset($_GET['sort']) && $_GET['sort'] === 'name_asc') echo 'selected'; ?>>Name Ascending</option>
            <option value="name_desc" <?php if (isset($_GET['sort']) && $_GET['sort'] === 'name_desc') echo 'selected'; ?>>Name Descending</option>
            <option value="start_date_asc" <?php if (isset($_GET['sort']) && $_GET['sort'] === 'start_date_asc') echo 'selected'; ?>>Issued Date Ascending</option>
            <option value="start_date_desc" <?php if (isset($_GET['sort']) && $_GET['sort'] === 'start_date_desc') echo 'selected'; ?>>Issued Date Descending</option>
          </select>
        </div>

        <div class="flex-item stretch d-flex gap-2 align-items-center">
          <a href="create.php"
            class="btn btn-sm rounded-circle d-flex justify-content-center align-items-center"
            title="Add Client"
            style="width: 36px; height: 36px; background-color: #6ab2faff; color: white;">
            <i class="fas fa-user-plus"></i>
          </a>

          <button type="button"
                  class="btn btn-sm rounded-circle d-flex justify-content-center align-items-center"
                  title="Import Clients"
                  style="width: 36px; height: 36px; background-color: #3ecc5fff; color: white;"
                  data-bs-toggle="modal"
                  data-bs-target="#importModal">
            <i class="fas fa-file-import"></i>
          </button>
        </div>
      </div>
    </form>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Policy Number</th>
            <th>Assured Name</th>
            <th>Insurance Provider</th>
            <th>Issued Date</th>
            <th>Policy Status</th>
            <th>Operations</th>
          </tr>
        </thead>
            <tbody>
            <?php
              $connection = new mysqli("localhost", "root", "", "u755652361_thesis");
              if ($connection->connect_error) {
                die("Connection Failed: " . $connection->connect_error);
              }

              if (!isset($_GET['status'])) {
                  $_GET['status'] = '1'; // Show only Active clients by default
              }

              $whereClauses = [];

              // Search
              if (!empty($_GET['search'])) {
                  $search = $connection->real_escape_string($_GET['search']);
                  $whereClauses[] = "(policy_number LIKE '%$search%' OR name LIKE '%$search%')";
              }
   
              // Status filter (only allow 0 or 1)
              if (isset($_GET['status']) && ($_GET['status'] === '0' || $_GET['status'] === '1')) {
                  $status = $connection->real_escape_string($_GET['status']);
                  $whereClauses[] = "is_active = '$status'";
              }

              // Provider
              if (!empty($_GET['provider'])) {
                  $provider = $connection->real_escape_string($_GET['provider']);
                  $whereClauses[] = "provider = '$provider'";
              }

              if (!empty($_GET['year'])) {
                  $year = $connection->real_escape_string($_GET['year']);
                  $whereClauses[] = "YEAR(start_date) = '$year'";
              }
                 
              // Month filter (check month of start_date)
              if (!empty($_GET['month'])) {
                  $month = str_pad($connection->real_escape_string($_GET['month']), 2, '0', STR_PAD_LEFT);
                  $whereClauses[] = "MONTH(start_date) = '$month'";
              }

              // Build WHERE clause only if conditions exist
              $whereSql = '';
              if (!empty($whereClauses)) {
                  $whereSql = 'WHERE ' . implode(' AND ', $whereClauses);
              }

              // Sorting
              $orderBy = 'name ASC'; // default sort
              if (isset($_GET['sort'])) {
                  switch ($_GET['sort']) {
                      case 'name_asc': $orderBy = 'name ASC'; break;
                      case 'name_desc': $orderBy = 'name DESC'; break;
                      case 'start_date_asc': $orderBy = 'start_date ASC'; break;
                      case 'start_date_desc': $orderBy = 'start_date DESC'; break;
                  }
              }

              // Final query
              $sql = "SELECT id, policy_number, name, provider, start_date, is_active FROM cliente $whereSql ORDER BY $orderBy";
              $result = $connection->query($sql);
              if (!$result) die("Invalid Query: " . $connection->error); 

              if ($result->num_rows > 0) {
                  while ($items = $result->fetch_assoc()) {
                    $formattedDate = date("Y-m-d", strtotime($items["start_date"]));

                    echo "<tr>
                            <td>" . htmlspecialchars($items["policy_number"]) . "</td>
                            <td>" . htmlspecialchars($items["name"]) . "</td>
                            <td>" . htmlspecialchars($items["provider"]) . "</td>
                            <td>" . $formattedDate . "</td>
                            <td>" . ($items["is_active"] == 1 ? "Active" : "Inactive") . "</td>
                              <td>
                                <a href='view_client.php?id=" . $items['id'] . "' class='btn btn-sm btn-outline-primary me-1' title='View Profile'>
                                  <i class='fas fa-user'></i>
                                </a>
                                <a href='payment_history.php?id=" . $items['id'] . "' class='btn btn-sm btn-outline-info me-1' title='Payment History'>
                                  <i class='fas fa-receipt'></i>
                                </a>
                                <a href='renew_client.php?id=" . $items['id'] . "' class='btn btn-sm btn-outline-success' title='Renew Client'>
                                  <i class='fas fa-redo'></i>
                                </a>
                              </td>
                          </tr>";
                  }
              } else {
                echo "<tr><td colspan='4' style='text-align:center;'>No records found</td></tr>";
              }

              $connection->close();
            ?>
          </tbody>
      </table>
    </div>

    <!-- Pagination Controls -->
    <div id="pagination" class="d-flex justify-content-center mt-3"></div>
  </main>

<script> 
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  sidebar.classList.toggle('collapsed');
  document.body.classList.toggle('sidebar-collapsed');
}
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const rowsPerPage = 8;
    const table = document.querySelector('.table-container table');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const pagination = document.getElementById('pagination');
    let currentPage = 1;

    function displayPage(page) {
      const start = (page - 1) * rowsPerPage;
      const end = start + rowsPerPage;

      rows.forEach((row, index) => {
        row.style.display = (index >= start && index < end) ? '' : 'none';
      });
    }

    function setupPagination() {
      pagination.innerHTML = '';
      const pageCount = Math.ceil(rows.length / rowsPerPage);

      const prevBtn = document.createElement('button');
      prevBtn.className = 'btn btn-sm btn-outline-primary mx-1';
      prevBtn.textContent = 'Previous';
      prevBtn.disabled = currentPage === 1;
      prevBtn.addEventListener('click', function () {
        if (currentPage > 1) {
          currentPage--;
          displayPage(currentPage);
          setupPagination();
        }
      });
      pagination.appendChild(prevBtn);

      for (let i = 1; i <= pageCount; i++) {
        const btn = document.createElement('button');
        btn.className = 'btn btn-sm btn-outline-primary mx-1';
        btn.textContent = i;
        if (i === currentPage) btn.classList.add('active');

        btn.addEventListener('click', function () {
          currentPage = i;
          displayPage(currentPage);
          setupPagination();
        });

        pagination.appendChild(btn);
      }

      const nextBtn = document.createElement('button');
      nextBtn.className = 'btn btn-sm btn-outline-primary mx-1';
      nextBtn.textContent = 'Next';
      nextBtn.disabled = currentPage === pageCount;
      nextBtn.addEventListener('click', function () {
        if (currentPage < pageCount) {
          currentPage++;
          displayPage(currentPage);
          setupPagination();
        }
      });
      pagination.appendChild(nextBtn);
    }

    displayPage(currentPage);
    setupPagination();
  });
</script>

<script>
function handleSearchInput() {
    const input = document.getElementById('search');
    const form = document.getElementById('filterForm');

    if (input.value.length >= 20 || input.value.length === 0) {
        form.submit();
    }
}
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
    tooltipTriggerList.forEach(el => {
      el.addEventListener('mouseenter', () => {
        const t = document.createElement('div');
        t.className = 'custom-tooltip';
        t.innerText = el.title;
        document.body.appendChild(t);
        const rect = el.getBoundingClientRect();
        t.style.left = rect.left + window.scrollX + 'px';
        t.style.top = rect.top + window.scrollY - 30 + 'px';
      });
      el.addEventListener('mouseleave', () => {
        document.querySelectorAll('.custom-tooltip').forEach(t => t.remove());
      });
    });
  });
</script>

<!-- Import Excel Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" enctype="multipart/form-data" action="client.php">
        <div class="modal-header">
          <h5 class="modal-title" id="importModalLabel">Import Clients via Excel</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="excel_file" class="form-label">Choose Excel File (.xlsx or .xls)</label>
            <input type="file" name="excel_file" id="excel_file" class="form-control" accept=".xlsx,.xls" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="import_excel" class="btn btn-success">Import</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>
