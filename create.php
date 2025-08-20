<?php
session_start();
require 'phpspreadsheet/vendor/autoload.php';

$servername = "localhost";
$username = "root";
$password = "";
$database = "u755652361_thesis";

$connection = new mysqli($servername, $username, $password, $database);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

function getPost($key) {
    return htmlspecialchars($_POST[$key] ?? '');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['import_excel'])) {
    $data = [];
    $fields = [
        'policy_number', 'name', 'provider', 'facebook', 'contact_number', 'email', 'address',
        'start_date', 'end_date', 'vehicle_unit', 'chasis_no', 'motor_no', 'plate_no', 'vehicle_color',
        'amount_insured', 'bipd', 'pa', 'aon', 'net_remitting', 'hkbc_net', 'mark_up', 'late_charges',
        'cancelled_income', 'reinstatement_fee', 'make_up_agent', 'comission', 'source', 'mortgage',
        'status', 'or_number', 'payment_1', 'payment_2', 'payment_3', 'payment_4', 'payment_5', 'payment_6',
        'payment_status', 'date_remittance', 'ctpl', 'bank_status', 'remarks'
    ];

    foreach ($fields as $field) {
        $data[$field] = getPost($field);
    }
           

    $is_active = strtolower($data['status']) === 'active' ? 1 : 0;

    $photoFileName = null;
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] === UPLOAD_ERR_OK) {
        $photoTmpPath = $_FILES["photo"]["tmp_name"];
        $originalName = basename($_FILES["photo"]["name"]);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $photoFileName = uniqid("client_", true) . '.' . $extension;
        $uploadDir = "profiles/";
        $destPath = $uploadDir . $photoFileName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        move_uploaded_file($photoTmpPath, $destPath);
    }

    $columns = implode(", ", array_merge($fields, ['is_active', 'photo']));
    $data['is_active'] = $is_active;
    $data['photo'] = $photoFileName;
    $values = implode(", ", array_map(function ($val) use ($connection) {
        return "'" . $connection->real_escape_string($val) . "'";
    }, $data));

    $sql = "INSERT INTO cliente ($columns) VALUES ($values)";

    if ($connection->query($sql)) {
        $newClientId = $connection->insert_id;
        $currentUserId = $_SESSION['user_id'] ?? 0;
        $logSql = "INSERT INTO client_activity_log (client_id, user_id, action) VALUES ($newClientId, $currentUserId, 'Created')";
        $connection->query($logSql);
        header("Location: client.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: {$connection->error}</div>";
    }
}

$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>HKBC Admin System - New Client</title>
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
  />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
    body {
      background-color: #002244;
      font-family: Arial, sans-serif;
      padding-top: 1120px;
    }

    h2 {
      font-size: 1.8rem;
      font-weight: bold;
      color: #002244;
      margin-bottom: 20px;
      text-align: center;
    }

    .form-card {
      background-color: #ffffff;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      width: 900px;
      margin-top: 20px;
      margin-bottom : 20px;
    }

    label {
      font-weight: 500;
      color: #002244;
    }

    .form-control:focus {
      border-color: #0056b3;
      box-shadow: 0 0 0 0.1rem rgba(0, 123, 255, 0.25);
    }

    .btn-primary {
      background-color: #002244;
      border-color: #002244;
    }

    .btn-primary:hover {
      background-color: #013366;
      border-color: #013366;
    }

    .btn-outline-primary {
      border-color: #002244;
      color: #002244;
    }

    .btn-outline-primary:hover {
      background-color: #002244;
      color: #fff;
    }
  </style>
</head>
<body>
  <div class="container my-5">

    <?php
    if (!empty($errorMessage)) {
        echo "
        <div class='alert alert-warning alert-dismissible fade show' role='alert'>
          <strong>$errorMessage</strong>
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
        ";
    }
    ?>

<body>
  <div class="d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-8 form-card">
      <h2>Add New Client</h2>

      <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong><?= $errorMessage ?></strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>
        <h5 class="text-primary mt-4">Personal Profile</h5>  
        <!-- Policy Number -->
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Policy Number</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="policy_number" value="<?= htmlspecialchars($_POST['policy_number'] ?? '') ?>">
          </div>
        </div>
        <!-- Name -->
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Assured Name</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
          </div>
        </div>
        
        <!-- Provider -->
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Insurance Provider</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="provider" value="<?= htmlspecialchars($_POST['provider'] ?? '') ?>">
          </div>
        </div>

        <!-- Facebook -->
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Facebook</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="facebook" value="<?= htmlspecialchars($_POST['facebook'] ?? '') ?>">
          </div>
        </div>

        <!-- Contact Number -->
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Contact Number</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="contact_number" value="<?= htmlspecialchars($_POST['contact_number'] ?? '') ?>">
          </div>
        </div>

        <!-- Email -->
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Email</label>
          <div class="col-sm-9">
            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
          </div>
        </div>

        <!-- Address -->
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Address</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="address" value="<?= htmlspecialchars($_POST['address'] ?? '') ?>">
          </div>
        </div>

        <h5 class="text-primary mt-4">Vehicle Information</h5> 
        <!-- Vehicle Unit -->
          <div class="mb-3 row">
          <!-- Start Date -->
          <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Issued Date</label>
            <div class="col-sm-9">
              <input type="date" class="form-control" name="start_date" value="<?= htmlspecialchars($_POST['start_date'] ?? '') ?>">
            </div>
          </div>

          <!-- End Date -->
          <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Effectivity</label>
            <div class="col-sm-9">
              <input type="date" class="form-control" name="date_remittance" value="<?= htmlspecialchars($_POST['date_remittance'] ?? '') ?>">
            </div>
          </div>
            <label class="col-sm-3 col-form-label">Vehicle Unit </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="vehicle_unit" value="<?= htmlspecialchars($_POST['vehicle_unit'] ?? '') ?>">
            </div>
          </div>

          <!-- Chasis No. -->
          <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Chasis No. </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="chasis_no" value="<?= htmlspecialchars($_POST['chasis_no'] ?? '') ?>">
            </div>
          </div>

          <!-- Motor No. -->
          <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Motor No. </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="motor_no" value="<?= htmlspecialchars($_POST['motor_no'] ?? '') ?>">
            </div>
          </div>

          <!-- Plate No. -->
          <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Plate No. </label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="plate_no" value="<?= htmlspecialchars($_POST['plate_no'] ?? '') ?>">
            </div>
          </div>
            <!-- Vehicle Color -->
          <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Vehicle Color</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="vehicle_color" value="<?= htmlspecialchars($_POST['vehicle_color'] ?? '') ?>">
            </div>
          </div>

         <!-- Section: Covered Payments -->
        <h5 class="text-primary mt-4">Covered Payments</h5>
          <!-- Amount Insured -->
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Amount Insured</label>
          <div class="col-sm-9">
            <input type="number" class="form-control" name="amount_insured" step="0.01" value="<?= htmlspecialchars($_POST['amount_insured'] ?? '') ?>">
          </div>
        </div>
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">BIPD</label>
          <div class="col-sm-9">
            <input type="number" step="0.01" class="form-control" name="bipd" value="<?= htmlspecialchars($_POST['bipd'] ?? '') ?>">
          </div>
        </div>
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">PA</label>
          <div class="col-sm-9">
            <input type="number" step="0.01" class="form-control" name="pa" value="<?= htmlspecialchars($_POST['pa'] ?? '') ?>">
          </div>
        </div>
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">AON</label>
          <div class="col-sm-9">
            <input type="number" step="0.01" class="form-control" name="aon" value="<?= htmlspecialchars($_POST['aon'] ?? '') ?>">
          </div>
        </div>

        <!-- Section: Net Payments -->
        <h5 class="text-primary mt-4">Net Payments</h5>
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Net Remitting</label>
          <div class="col-sm-9">
            <input type="number" class="form-control" name="net_remitting" step="0.01" value="<?= htmlspecialchars($_POST['net_remitting'] ?? '') ?>">
          </div>
        </div>
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">HKBC net</label>
          <div class="col-sm-9">
            <input type="number" class="form-control" name="hkbc_net" step="0.01" value="<?= htmlspecialchars($_POST['hkbc_net'] ?? '') ?>">
          </div>
        </div>
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Mark Up</label>
          <div class="col-sm-9">
            <input type="number" class="form-control" name="mark_up" step="0.01" value="<?= htmlspecialchars($_POST['mark_up'] ?? '') ?>">
          </div>
        </div>
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Late Charges</label>
          <div class="col-sm-9">
            <input type="number" class="form-control" name="late_charges" step="0.01" value="<?= htmlspecialchars($_POST['late_charges'] ?? '') ?>">
          </div>
        </div>
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Cancelled Income</label>
          <div class="col-sm-9">
            <input type="number" class="form-control" name="cancelled_income" step="0.01" value="<?= htmlspecialchars($_POST['cancelled_income'] ?? '') ?>">
          </div>
        </div>
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Reinstatement Fee</label>
          <div class="col-sm-9">
            <input type="number" class="form-control" name="reinstatement_fee" step="0.01" value="<?= htmlspecialchars($_POST['reinstatement_fee'] ?? '') ?>">
          </div>
        </div>
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Mark up Agent</label>
          <div class="col-sm-9">
            <input type="number" class="form-control" name="make_up_agent" step="0.01" value="<?= htmlspecialchars($_POST['make_up_agent'] ?? '') ?>">
          </div>
        </div>
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Comission of Agent</label>
          <div class="col-sm-9">
            <input type="number" class="form-control" name="comission" step="0.01" value="<?= htmlspecialchars($_POST['comission'] ?? '') ?>">
          </div>
        </div>
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Source of Sale</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="source" step="0.01" value="<?= htmlspecialchars($_POST['source'] ?? '') ?>">
          </div>
        </div>
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Mortgage</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="mortgage" step="0.01" value="<?= htmlspecialchars($_POST['mortgage'] ?? '') ?>">
          </div>
        </div>
        <?php $status = $_POST['status'] ?? ''; ?>
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Status</label>
          <div class="col-sm-9">
            <select class="form-select" name="status">
              <option value="">Select Status</option>
              <option value="Active" <?= htmlspecialchars($status) === "Active" ? "selected" : "" ?>>Active</option>
              <option value="Inactive" <?= htmlspecialchars($status) === "Inactive" ? "selected" : "" ?>>Inactive</option>
            </select>
          </div>
        </div>
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">OR Number</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="or_number" value="<?= htmlspecialchars($_POST['or_number'] ?? '') ?>">
          </div>
        </div>
        
        <!-- Section: Collection -->
        <h5 class="text-primary mt-4">Collection Data</h5>
        <?php
          $payment_fields = [
            'payment_1', 'payment_2', 'payment_3',
            'payment_4', 'payment_5', 'payment_6'
          ];
          foreach ($payment_fields as $field):
        ?>
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label"><?= ucwords(str_replace("_", " ", $field)) ?></label>
          <div class="col-sm-9">
            <input type="number" step="0.01" class="form-control" name="<?= $field ?>" value="<?= htmlspecialchars($_POST[$field] ?? '') ?>">
          </div>
        </div>
        <?php endforeach; ?>
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Payment Status</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="payment_status" value="<?= htmlspecialchars($_POST['payment_status'] ?? '') ?>">
          </div>
        </div>
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Date Remittance</label>
          <div class="col-sm-9">
            <input type="date" class="form-control" name=" end_date" value="<?= htmlspecialchars($_POST['end_date'] ?? '') ?>">
          </div>
        </div>
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">CTPL</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="ctpl" value="<?= htmlspecialchars($_POST['ctpl'] ?? '') ?>">
          </div>
        </div>
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Bank Status</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="bank_status" value="<?= htmlspecialchars($_POST['bank_status'] ?? '') ?>">
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Remarks</label>
          <div class="col-sm-9">
            <textarea class="form-control" name="remarks"><?= htmlspecialchars($_POST['remarks'] ?? '') ?></textarea>
          </div>
        </div>
        <!-- Photo -->
        <div class="mb-3 row">
          <label class="col-sm-3 col-form-label">Photo</label>
          <div class="col-sm-9">
            <input type="file" class="form-control" name="photo" accept="image/*">
          </div>
        </div>

        <?php if (!empty($successMessage)): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><?= $successMessage ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

      <div class="row mb-3">
        <div class="offset-sm-3 col-sm-3 d-grid">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        <div class="col-sm-3 d-grid">
          <a class="btn btn-outline-primary" href="client.php" role="button">Cancel</a>
        </div>
      </div>
    </form>
  </div>
</body>
</html>
