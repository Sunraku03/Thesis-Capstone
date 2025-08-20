<?php
session_start();

$servername   = "localhost";
$username     = "root";
$password     = "";
$database     = "u755652361_thesis";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getClient($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM cliente WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function updateClient($conn, $id, $fields) {
    $set = [];
    foreach ($fields as $key => $value) {
        $set[] = "$key = '" . $conn->real_escape_string($value) . "'";
    }
    $setClause = implode(", ", $set);
    $sql = "UPDATE cliente SET $setClause WHERE id = $id";
    return $conn->query($sql);
}

$section = basename(__FILE__, ".php"); // edit_personal, etc.
$id = $_GET['id'] ?? null;
$success = false;

if (!$id) {
    die("Missing client ID.");
}

$client = getClient($conn, $id);
if (!$client) {
    die("Client not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = [];

    switch ($section) {
        case 'edit_personal':
            $fields = [
                'policy_number' => $_POST['policy_number'],
                'name' => $_POST['name'],
                'provider' => $_POST['provider'],
                'facebook' => $_POST['facebook'],
                'contact_number' => $_POST['contact_number'],
                'email' => $_POST['email'],
                'address' => $_POST['address'],
            ];

            // Handle photo upload
            if (!empty($_FILES['photo']['name'])) {
                $targetDir = "profiles/";
                $fileName = uniqid() . "_" . basename($_FILES["photo"]["name"]);
                $targetFilePath = $targetDir . $fileName;
                $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($imageFileType, $allowedTypes)) {
                    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
                        $fields['photo'] = $fileName;
                    }
                }
            }
            break;

        case 'edit_vehicle':
            $fields = [
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date'],
                'vehicle_unit' => $_POST['vehicle_unit'],
                'chasis_no' => $_POST['chasis_no'],
                'motor_no' => $_POST['motor_no'],
                'plate_no' => $_POST['plate_no'],
                'vehicle_color' => $_POST['vehicle_color'],
            ];
            break;

        case 'edit_covered_payment':
            $fields = [
                'amount_insured' => $_POST['amount_insured'],
                'bipd' => $_POST['bipd'],
                'pa' => $_POST['pa'],
                'aon' => $_POST['aon'],
            ];
            break;

        case 'edit_net_payment':
            $fields = [
                'net_remitting' => $_POST['net_remitting'],
                'hkbc_net' => $_POST['hkbc_net'],
                'mark_up' => $_POST['mark_up'],
                'late_charges' => $_POST['late_charges'],
                'cancelled_income' => $_POST['cancelled_income'],
                'reinstatement_fee' => $_POST['reinstatement_fee'],
                'make_up_agent' => $_POST['make_up_agent'],
                'comission' => $_POST['comission'],
                'source' => $_POST['source'],
                'mortgage' => $_POST['mortgage'],
                'status' => $_POST['status'],
                'or_number' => $_POST['or_number'],
            ];
            break;

        case 'edit_collection':
            $fields = [
                'first_payment' => $_POST['first_payment'],
                'second_payment' => $_POST['second_payment'],
                'third_payment' => $_POST['third_payment'],
                'fourth_payment' => $_POST['fourth_payment'],
                'fifth_payment' => $_POST['fifth_payment'],
                'sixth_payment' => $_POST['sixth_payment'],
                'payment_status' => $_POST['payment_status'],
                'date_remittance' => $_POST['date_remittance'],
                'ctpl' => $_POST['ctpl'],
                'bank_status' => $_POST['bank_status'],
                'remarks' => $_POST['remarks'],
            ];
            break;
    }

if (updateClient($conn, $id, $fields)) {
    $currentUserId = $_SESSION['user_id'] ?? 0;
    $logSql = "INSERT INTO client_activity_log (client_id, user_id, action)
               VALUES ($id, $currentUserId, 'Edited')";
    $conn->query($logSql);

    header("Location: view_client.php?id=$id");
    exit;
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= ucfirst(str_replace("_", " ", $section)) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f7fa;
            padding: 30px;
        }
        .form-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 800px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        img {
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="form-card">
    <h3 class="mb-4"><?= ucfirst(str_replace("_", " ", $section)) ?></h3>
    <form method="post" enctype="multipart/form-data">
        <?php switch ($section):
            case 'edit_personal': ?>
                <input type="text" name="policy_number" value="<?= htmlspecialchars($client['policy_number']) ?>" class="form-control mb-3" placeholder="Policy Number">
                <input type="text" name="name" value="<?= htmlspecialchars($client['name']) ?>" class="form-control mb-3" placeholder="Name">
                <input type="text" name="provider" value="<?= htmlspecialchars($client['provider']) ?>" class="form-control mb-3" placeholder="Provider">
                <input type="text" name="facebook" value="<?= htmlspecialchars($client['facebook']) ?>" class="form-control mb-3" placeholder="Facebook">
                <input type="text" name="contact_number" value="<?= htmlspecialchars($client['contact_number']) ?>" class="form-control mb-3" placeholder="Contact Number">
                <input type="email" name="email" value="<?= htmlspecialchars($client['email']) ?>" class="form-control mb-3" placeholder="Email">
                <input type="text" name="address" value="<?= htmlspecialchars($client['address']) ?>" class="form-control mb-3" placeholder="Address">
                
                <!-- PHOTO UPLOAD -->
                <label class="form-label">Profile Photo</label><br>
                <?php if (!empty($client['photo'])): ?>
                    <img src="profiles/<?= htmlspecialchars($client['photo']) ?>" alt="Client Photo" style="height:100px; border-radius: 8px; border: 1px solid #ccc;">
                <?php endif; ?>
                <input type="file" name="photo" class="form-control mb-3">
                <?php break;

            case 'edit_vehicle': ?>
                <input type="date" name="start_date" value="<?= $client['start_date'] ?>" class="form-control mb-3">
                <input type="date" name="end_date" value="<?= $client['end_date'] ?>" class="form-control mb-3">
                <input type="text" name="vehicle_unit" value="<?= htmlspecialchars($client['vehicle_unit']) ?>" class="form-control mb-3" placeholder="Vehicle Unit">
                <input type="text" name="chasis_no" value="<?= htmlspecialchars($client['chasis_no']) ?>" class="form-control mb-3" placeholder="Chasis No.">
                <input type="text" name="motor_no" value="<?= htmlspecialchars($client['motor_no']) ?>" class="form-control mb-3" placeholder="Motor No.">
                <input type="text" name="plate_no" value="<?= htmlspecialchars($client['plate_no']) ?>" class="form-control mb-3" placeholder="Plate No.">
                <input type="text" name="vehicle_color" value="<?= htmlspecialchars($client['vehicle_color']) ?>" class="form-control mb-3" placeholder="Vehicle Color">
                <?php break;

            case 'edit_covered_payment': ?>
                <input type="number" step="0.01" name="amount_insured" value="<?= $client['amount_insured'] ?>" class="form-control mb-3" placeholder="Amount Insured">
                <input type="number" step="0.01" name="bipd" value="<?= $client['bipd'] ?>" class="form-control mb-3" placeholder="BIPD">
                <input type="number" step="0.01" name="pa" value="<?= $client['pa'] ?>" class="form-control mb-3" placeholder="PA">
                <input type="number" step="0.01" name="aon" value="<?= $client['aon'] ?>" class="form-control mb-3" placeholder="AON">
                <?php break;

            case 'edit_net_payment':
                foreach (["net_remitting", "hkbc_net", "mark_up", "late_charges", "cancelled_income", "reinstatement_fee", "make_up_agent", "comission", "source", "mortgage", "status", "or_number"] as $field): ?>
                    <input type="text" name="<?= $field ?>" value="<?= htmlspecialchars($client[$field]) ?>" class="form-control mb-3" placeholder="<?= ucwords(str_replace("_", " ", $field)) ?>">
                <?php endforeach; break;

            case 'edit_collection':
                foreach (["first_payment", "second_payment", "third_payment", "fourth_payment", "fifth_payment", "sixth_payment", "payment_status", "date_remittance", "ctpl", "bank_status", "remarks"] as $field): ?>
                    <input type="text" name="<?= $field ?>" value="<?= htmlspecialchars($client[$field]) ?>" class="form-control mb-3" placeholder="<?= ucwords(str_replace("_", " ", $field)) ?>">
                <?php endforeach; break;
        endswitch; ?>

        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="view_client.php?id=<?= $id ?>" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
