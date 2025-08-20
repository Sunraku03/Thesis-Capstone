<?php
session_start();

if (!isset($_GET['id'])) {
    die("No client ID provided.");
}

$id = $_GET['id'];

$conn = new mysqli("localhost", "root", "", "u755652361_thesis");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $conn->real_escape_string($id);

$query = "SELECT * FROM cliente WHERE id = '$id'";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    die("Client not found.");
}

$client = $result->fetch_assoc();

$imagePath = !empty($client['photo']) ? "profiles/" . $client['photo'] : "profiles/nophoto.jpg";

$payment_query = "SELECT * FROM payment_history WHERE client_id = '$id' ORDER BY payment_date DESC";
$payments = $conn->query($payment_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Profile - <?= htmlspecialchars($client['name']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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
    .document {
      max-width: 1100px;
      margin: auto;
      background: white;
      padding: 40px 60px;
      border: 1px solid #ccc;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    .document h1 {
      text-align: center;
      font-size: 28px;
      margin-bottom: 40px;
      color: #003366;
    }

    .client-container {
      display: flex;
      gap: 20px; 
      flex-wrap: wrap;
      margin-top: 80px;
    }

    .left-panel {
      width: 250px;
      flex-shrink: 0;
      text-align: center;
    }

    .right-panel {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .info-grid {
      display: flex;
      flex-direction: column; /* stack vertically */
      gap: 20px;
      margin-right: 20px;
    }

    .info-box {
      background: #fff;
      border: 2px solid #003366;
      border-radius: 10px;
      padding: 15px 20px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      width: 100%;
    }

    .info-box h4 {
      background: #05445e;
      color: white;
      padding: 8px 12px;
      margin: -15px -20px 15px -20px;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .info-box h4 i {
      cursor: pointer;
      font-size: 0.9em;
    }
    .info-box-text-columns {
      display: flex;
      flex-wrap: wrap;
      column-gap: 2%;
    }

    .info-box-text-columns p {
      flex: 0 0 48%;
      margin: 4px 0;
    }
    .info-box-text-columns .payment-entry {
      flex: 0 0 48%;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .payment-header, .payment-row {
      display: flex;
      align-items: center;
      padding: 6px 0;
      border-bottom: 1px solid #ccc;
    }

    .payment-header {
      font-weight: bold;
      background: #f1f1f1;
      padding: 8px 0;
      margin-bottom: 5px;
    }

    .payment-header div,
    .payment-row div {
      flex: 1;
      padding: 0 10px;
    }

    .payment-entry form {
      margin-left: 10px;
      margin-bottom: 10px;
      display: inline;
    }
    /* Full-width general info override */
    .general-info-box {
      width: calc(100% + 270px); /* extend into the left-panel space */
      margin-left: -270px;
      margin-bottom: 10px;
    }

    .edit-link {
      text-align: right;
      margin-top: 10px;
    }

    .edit-link a {
      color: #003366;
      font-weight: 600;
      text-decoration: none;
    }

    .edit-link a:hover {
      text-decoration: underline;
    }

    .edit-button {
      background-color: #003366;
      color: white;
      padding: 8px 16px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      transition: background-color 0.3s;
      display: inline-block;
    }

    .edit-button:hover {
      background-color: #0055aa;
      color: white;
    }

    .previous {
      background-color: #f1f1f1;
      color: black;
    }

    .status-badge {
      font-size: 0.75rem;
      padding: 2px 6px;
      margin-left: 8px;
      border-radius: 10px;
      color: white;
    }

    .paid .status-badge {
      background-color: #28a745; /* Green */
    }

    .unpaid .status-badge {
      background-color: #dc3545; /* Red */
    }

    .paid-button {
      background-color: #f44336;
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 4px;
      cursor: pointer;
    }

    .unpaid-button {
      background-color:    #4CAF50;
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 4px;
      cursor: pointer;
    }

    .paid-button:hover,
    .unpaid-button:hover {
      opacity: 0.85;
    }
        /* View Proof button */
    .view-proof-btn {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 5px 14px;
      border-radius: 5px;
      cursor: pointer;
      transition: 0.3s;
    }
    .view-proof-btn:hover {
      background-color: #0056b3;
    }
    .status-container {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
      margin-top: 10px;
      text-align: center;
    }

    .status-container p {
      margin: 0;
      font-size: 16px;
    }

    .status-toggle {
      background: none;
      border: none;
      cursor: pointer;
      padding: 2px;
    }

    .status-toggle i {
      font-size: 1.6rem;
      transition: color 0.3s ease;
    }

    .text-active {
      color: #007bff; /* Blue when active */
    }

    .text-inactive {
      color: #dc3545; /* Red when inactive */
    }

    .status-toggle:hover i {
      opacity: 0.8;
    }

    /* Show insurance layout only when printing */
    #insurance-print {
      display: none;
      font-family: Arial, sans-serif;
      font-size: 12px;
      line-height: 1.2;
      color: #000;
    }

    @media print {
      body * {
        visibility: hidden;
      } 
      #insurance-print, #insurance-print * {
        visibility: visible;
      }

      #insurance-print {
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        padding: 5mm;
        page-break-after: avoid;
      }  

      @page {
        size: A4 landscape;
        margin: 8mm;
      }
    }

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
    <li><a href="report.php "><i class="fas fa-archive"></i> <span class="text">Archive</span></a></li>
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
<div class="client-container">

  <!-- LEFT PANEL -->
  <div class="left-panel">
    <img src="<?= htmlspecialchars($imagePath) ?>" alt="Client Photo" style="width: 150px; height: 150px; border-radius: 10px; object-fit: cover; border: 2px solid #ccc;">
    <div class="status-container">
      <p>Status: <strong><?= htmlspecialchars($client['status']) ?></strong></p>
      <button class="status-toggle" data-bs-toggle="modal" data-bs-target="#statusModal" title="Toggle Status">
        <i class="bi <?= $client['is_active'] ? 'bi-toggle-on text-active' : 'bi-toggle-off text-inactive' ?>"></i>
      </button>
    </div>
    <button class="edit-button">View history details <i class="fas fa-clock-rotate-left"></i></button>
    <div style="display: center; justify-content: gap: 10px; margin-top: 20px;" class="no-print">
            <a href="#" onclick="window.print()" class="edit-button">🖨️ Print Insurance Form</a>
     </div>
  </div>

  <!-- RIGHT PANEL -->
  <div class="right-panel">
    <div class="info-grid">

      <!-- GENERAL INFO -->
      <div class="info-box">
        <h4>
          <span>General Information</span>
          <a href="edit_personal.php?id=<?= $client['id'] ?>" class="edit-icon" title="Edit Personal Info">
            <i class="fas fa-pen"></i>
          </a>
        </h4>
        <p><strong>Policy Number:</strong> <?= htmlspecialchars($client['policy_number']) ?></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($client['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($client['email']) ?></p>
        <p><strong>Facebook:</strong> <?= htmlspecialchars($client['facebook']) ?></p>
        <p><strong>Contact No.:</strong> <?= htmlspecialchars($client['contact_number']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($client['address']) ?></p>
      </div>

      <!-- POLICY VEHICLE INFO -->
      <div class="info-box general-info-box">
        <h4>
          <span>Vehicle Information</span>
          <a href="edit_vehicle.php?id=<?= $client['id'] ?>" class="edit-icon" title="Edit Personal Info">
            <i class="fas fa-pen"></i>
          </a>
        </h4>
        <div class="info-box-text-columns">
          <p><strong>Issued Date:</strong> <?= htmlspecialchars($client['start_date']) ?></p>
          <p><strong>Effectivity:</strong> <?= htmlspecialchars($client['date_remittance']) ?></p>
          <p><strong>Vehicle Unit:</strong> <?= htmlspecialchars($client['vehicle_unit']) ?></p>
          <p><strong>Chasis no.:</strong> <?= htmlspecialchars($client['chasis_no']) ?></p>
          <p><strong>Motor no.:</strong> <?= htmlspecialchars($client['motor_no']) ?></p>
          <p><strong>Plate no.:</strong> <?= htmlspecialchars($client['plate_no']) ?></p>
          <p><strong>Vehicle color:</strong> <?= htmlspecialchars($client['vehicle_color']) ?></p>
        </div>
      </div>

      <!-- INSURANCE PAYMENT -->
      <div class="info-box general-info-box">
        <h4>
          <span>Covered Payment</span>
          <a href="edit_covered_payment.php?id=<?= $client['id'] ?>" class="edit-icon" title="Edit Personal Info">
            <i class="fas fa-pen"></i>
          </a>
        </h4>
        <div class="info-box-text-columns">
        <p><strong>Amount Insured:</strong> ₱<?= number_format($client['amount_insured'], 2) ?></p>
        <p><strong>BIPD:</strong> ₱<?= number_format($client['bipd'], 2) ?></p>
        <p><strong>PA:</strong> ₱<?= number_format($client['pa'], 2) ?></p>
        <p><strong>AON:</strong> ₱<?= number_format($client['aon'], 2) ?></p>
        </div>
      </div> 
    
<!-- COLLECTION DATA -->
<div class="info-box general-info-box">
  <h4>
    <span>Collection Data</span>
    <a href="edit_collection.php?id=<?= $client['id'] ?>" class="edit-icon" title="Edit Collection Info">
      <i class="fas fa-pen"></i>
    </a>
  </h4>

  <!-- Payment Headers -->
  <div class="payment-header">
    <div>Payment</div>
    <div>Date of Payment</div>
    <div>Method of Payment</div>
    <div>Remark</div>
    <div>Proof of Payment</div>
  </div>

  <!-- Payments Loop -->
  <?php for ($i = 1; $i <= 6; $i++): ?>
    <?php
      $paymentAmount = $client["payment_$i"];
      $paymentStatus = $client["payment_{$i}_status"];
      $paymentDate = $client["payment_{$i}_date"];
      $paymentMethod = $client["payment_{$i}_method"];
      $proofImage = $client["proof_payment_$i"];
      $isPaid = $paymentStatus == 1;
      $suffix = ($i === 1) ? 'st' : (($i === 2) ? 'nd' : (($i === 3) ? 'rd' : 'th'));
    ?>
    <div class="payment-row">
      <div><strong><?= $i . $suffix ?> Payment:</strong> ₱<?= number_format($paymentAmount, 2) ?></div>
      <div><?= $paymentDate ? htmlspecialchars($paymentDate) : 'N/A' ?></div>
      <div><?= $paymentMethod ? htmlspecialchars($paymentMethod) : 'N/A' ?></div>
      <div>
        <form method="post" action="update_payment_status.php">
          <input type="hidden" name="client_id" value="<?= $client['id'] ?>">
          <input type="hidden" name="payment_number" value="<?= $i ?>">
          <input type="hidden" name="set_paid" value="<?= $isPaid ? 0 : 1 ?>">
          <button class="<?= $isPaid ? 'unpaid-button' : 'paid-button' ?>">
            <?= $isPaid ? 'Paid' : 'Unpaid' ?>
          </button>
        </form>
      </div>
      <div>
<!-- View Proof Button -->
<?php if (!empty($proofImage)): ?>
  <button class="view-proof-btn" onclick="openModal('<?= htmlspecialchars($proofImage) ?>')">View</button>
<?php else: ?>
  <small>No Proof</small>
<?php endif; ?>
      </div>
    </div>
  <?php endfor; ?>

  <!-- Additional Info -->
  <div class="info-box-text-columns" style="margin-top: 10px;">
    <p><strong>Date Remittance:</strong> <?= htmlspecialchars($client['end_date']) ?></p>
    <p><strong>CTPL:</strong> <?= htmlspecialchars($client['ctpl']) ?></p>
    <p><strong>Bank Status:</strong> <?= htmlspecialchars($client['bank_status']) ?></p>
    <p><strong>Remark:</strong> <?= htmlspecialchars($client['remarks']) ?></p>
  </div>
</div>

      <!-- NET PAYMENT -->
      <div class="info-box general-info-box">
        <h4>
          <span>Net Payment</span>
          <a href="edit_net_payment.php?id=<?= $client['id'] ?>" class="edit-icon" title="Edit Personal Info">
            <i class="fas fa-pen"></i>
          </a>
        </h4>
        <div class="info-box-text-columns">
        <p><strong>Net Remitting:</strong> ₱<?= number_format($client['net_remitting'], 2) ?></p>
        <p><strong>HKBC Net:</strong> ₱<?= number_format($client['hkbc_net'], 2) ?></p>
        <p><strong>Mark Up:</strong> ₱<?= number_format($client['mark_up'], 2) ?></p>
        <p><strong>Late Charges:</strong> ₱<?= number_format($client['late_charges'], 2) ?></p>
        <p><strong>Cancelled Income:</strong> ₱<?= number_format($client['cancelled_income'], 2) ?></p>
        <p><strong>Reinstatement Fee:</strong> ₱<?= number_format($client['reinstatement_fee'], 2) ?></p>
        <p><strong>Mark-up Agent:</strong> <?= htmlspecialchars($client['make_up_agent']) ?></p>
        <p><strong>Commission:</strong> ₱<?= number_format($client['comission'], 2) ?></p>
        <p><strong>Source:</strong> <?= htmlspecialchars($client['source']) ?></p>
        <p><strong>Mortgage:</strong> <?= htmlspecialchars($client['mortgage']) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($client['status']) ?></p>
        <p><strong>OR Number:</strong> <?= htmlspecialchars($client['or_number']) ?></p>
      </div>
      </div>
    </div>
  </div>
</div>

<!-- LANDSCAPE PRINT SECTION -->
<div id="insurance-print">
  <h2 style="text-align:center; margin-bottom: 4px;">HKBC INSURANCE SERVICES</h2>
  <p style="text-align:center; margin: 0;">"We care for your property, because you care."</p>

    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
      <tr>
        <td><strong>Name:</strong> <?= htmlspecialchars($client['name']) ?></td>
        <td><strong>Policy Number:</strong> <?= htmlspecialchars($client['policy_number']) ?></td>
        <td><strong>Provider:</strong> <?= htmlspecialchars($client['provider']) ?></td>
      </tr>
      <tr>
        <td><strong>Facebook:</strong> <?= htmlspecialchars($client['facebook']) ?></td>
        <td><strong>Contact:</strong> <?= htmlspecialchars($client['contact_number']) ?></td>
        <td><strong>Email:</strong> <?= htmlspecialchars($client['email']) ?></td>
      </tr>
      <tr>
        <td colspan="3"><strong>Address:</strong> <?= htmlspecialchars($client['address']) ?></td>
      </tr>
      <tr>
        <td><strong>Date Issued  :</strong> <?= htmlspecialchars($client['start_date']) ?></td>
        <td><strong>Effectivity:</strong> <?= htmlspecialchars($client['date_remittance']) ?></td>
        <td><strong>Status:</strong> <?= htmlspecialchars($client['status']) ?></td>
      </tr>
    </table>

    <hr style="margin: 20px 0;">

    <h4>Vehicle Information</h4>
    <table style="width: 100%; border-collapse: collapse;">
      <tr>
        <td><strong>Unit:</strong> <?= htmlspecialchars($client['vehicle_unit']) ?></td>
        <td><strong>Chassis No.:</strong> <?= htmlspecialchars($client['chasis_no']) ?></td>
        <td><strong>Motor No.:</strong> <?= htmlspecialchars($client['motor_no']) ?></td>
        <td><strong>Plate No.:</strong> <?= htmlspecialchars($client['plate_no']) ?></td>
      </tr>
      <tr>
        <td colspan="4"><strong>Vehicle Color:</strong> <?= htmlspecialchars($client['vehicle_color']) ?></td>
      </tr>
    </table>

    <hr style="margin: 20px 0;">

    <h4>Insurance & Coverage</h4>
    <table style="width: 100%; border-collapse: collapse;">
      <tr>
        <td><strong>Amount Insured:</strong> ₱<?= number_format($client['amount_insured'], 2) ?></td>
        <td><strong>BIPD:</strong> <?= htmlspecialchars($client['bipd']) ?></td>
        <td><strong>PA:</strong> <?= htmlspecialchars($client['pa']) ?></td>
        <td><strong>AO:</strong> <?= htmlspecialchars($client['aon']) ?></td>
      </tr>
    </table>

    <hr style="margin: 20px 0;">

    <h4>Net Payment Breakdown</h4>
    <table style="width: 100%; border-collapse: collapse;">
      <tr>
        <td><strong>Net Remitting:</strong> ₱<?= number_format($client['net_remitting'], 2) ?></td>
        <td><strong>HKBC Net:</strong> ₱<?= number_format($client['hkbc_net'], 2) ?></td>
        <td><strong>Markup:</strong> ₱<?= number_format($client['mark_up'], 2) ?></td>
      </tr>
      <tr>
        <td><strong>Late Charges:</strong> ₱<?= number_format($client['late_charges'], 2) ?></td>
        <td><strong>Cancelled Income:</strong> ₱<?= number_format($client['cancelled_income'], 2) ?></td>
        <td><strong>Reinstatement Fee:</strong> ₱<?= number_format($client['reinstatement_fee'], 2) ?></td>
      </tr>
      <tr>
        <td><strong>Mark-up Agent:</strong> <?= htmlspecialchars($client['make_up_agent']) ?></td>
        <td><strong>Commission of Agent:</strong> ₱<?= number_format($client['comission'], 2) ?></td>
        <td><strong>Source of Agent:</strong> <?= htmlspecialchars($client['source']) ?></td>
      </tr>
      <tr>
        <td colspan="2"><strong>Mortgage:</strong> <?= htmlspecialchars($client['mortgage']) ?></td>
        <td><strong>OR Number:</strong> <?= htmlspecialchars($client['or_number']) ?></td>
      </tr>
    </table>

    <hr style="margin: 20px 0;">

    <h4>Collection Data</h4>
    <table style="width: 100%; border-collapse: collapse;">
      <tr>
        <td><strong>1st Payment:</strong> ₱<?= number_format($client['payment_1'], 2) ?></td>
        <td><strong>2nd Payment:</strong> ₱<?= number_format($client['payment_2'], 2) ?></td>
        <td><strong>3rd Payment:</strong> ₱<?= number_format($client['payment_3'], 2) ?></td>
      </tr>
      <tr>
        <td><strong>4th Payment:</strong> ₱<?= number_format($client['payment_4'], 2) ?></td>
        <td><strong>5th Payment:</strong> ₱<?= number_format($client['payment_5'], 2) ?></td>
        <td><strong>6th Payment:</strong> ₱<?= number_format($client['payment_6'], 2) ?></td>
      </tr>
      <tr>
        <td><strong>Payment Status:</strong> <?= htmlspecialchars($client['payment_status']) ?></td>
        <td><strong>Date Remittance:</strong> <?= htmlspecialchars($client['end_date']) ?></td>
        <td><strong>CTPL:</strong> <?= htmlspecialchars($client['ctpl']) ?></td>
      </tr>
      <tr>
        <td colspan="2"><strong>Bank Status:</strong> <?= htmlspecialchars($client['bank_status']) ?></td>
        <td><strong>Remarks:</strong> <?= htmlspecialchars($client['remarks']) ?></td>
      </tr>
    </table>

    <div style="margin-top: 40px; text-align: right;">
      <p>Prepared by: ____________________</p>
      <p>Approved by: ____________________</p>
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

<!-- Status Toggle Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="update_status.php">
      <input type="hidden" name="client_id" value="<?= $client['id'] ?>">
      <input type="hidden" name="new_status" value="<?= $client['is_active'] ? 'Inactive' : 'Active' ?>">
      <input type="hidden" name="new_is_active" value="<?= $client['is_active'] ? 0 : 1 ?>">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="statusModalLabel">Confirm Status Change</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to set this client as <strong><?= $client['is_active'] ? 'Inactive' : 'Active' ?></strong>?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Yes, Change Status</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Proof of Payment Modal -->
<div id="proofModal" class="modal" style="display:none; position:fixed; z-index:999; left:0; top:0; width:100%; height:100%; overflow:auto; background-color: rgba(0,0,0,0.8);">
  <span onclick="closeModal()" style="position:absolute; top:20px; right:30px; color:#fff; font-size:40px; cursor:pointer;">&times;</span>
  <div style="margin: 100px auto; display: flex; justify-content: center;">
    <img id="modalImage" src="" alt="Proof of Payment" style="max-width:90%; max-height:80vh;">
  </div>
</div>

<script>
function openModal(imagePath) {
  document.getElementById("modalImage").src = "uploads/" + imagePath;
  document.getElementById("proofModal").style.display = "block";
}
function closeModal() {
  document.getElementById("proofModal").style.display = "none";
}
</script>
</body>
</html>
