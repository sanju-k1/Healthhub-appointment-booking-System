<?php
session_start();
include "../db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'patient') {
    header("Location: ../login.php");
    exit;
}

$patient_id = $_SESSION['user_id'];

// ✅ Fetch Doctors
$doctors = $conn->query("SELECT id, name, specialty FROM doctors WHERE approved=1");

// ✅ Handle Booking
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $doctor_id = $_POST['doctor_id'];
    $date = $_POST['date'];
    $slot = $_POST['slot'];

    // Check if already booked
    $check = $conn->query("SELECT id FROM appointments WHERE doctor_id=$doctor_id AND date='$date' AND slot='$slot'");
    if ($check->num_rows > 0) {
        $msg = "⚠️ Slot already taken!";
    } else {
        $conn->query("INSERT INTO appointments (patient_id, doctor_id, date, slot, status) VALUES ($patient_id, $doctor_id, '$date', '$slot', 'pending')");
        $msg = "✅ Appointment Booked (Waiting for Doctor Approval)";
    }
}

// ✅ Fetch Patient History
$result = $conn->query("
    SELECT d.name AS doctor_name, a.date, a.slot, a.status
    FROM appointments a
    JOIN doctors d ON a.doctor_id = d.id
    WHERE a.patient_id=$patient_id
    ORDER BY a.date DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Patient Dashboard</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="navbar">👤 Welcome <?= $_SESSION['username'] ?> | <a href="../logout.php" style="color:yellow;">Logout</a></div>
<div class="container">
<h2>🩺 Book Appointment</h2>
<?php if(!empty($msg)) echo "<p style='color:orange;font-weight:bold;'>$msg</p>"; ?>
<form method="POST">
<label>Doctor</label>
<select name="doctor_id" required>
<option value="">-- Select Doctor --</option>
<?php while($doc = $doctors->fetch_assoc()) { ?>
<option value="<?= $doc['id'] ?>"><?= $doc['name']." (".$doc['specialty'].")" ?></option>
<?php } ?>
</select>
<label>Date</label>
<input type="date" name="date" required>
<label>Slot</label>
<select name="slot" required>
<option>10:00 AM</option>
<option>11:00 AM</option>
<option>2:00 PM</option>
<option>4:00 PM</option>
</select>
<button type="submit">Book</button>
</form>

<h2>📅 Your Appointments</h2>
<table>
<tr><th>Doctor</th><th>Date</th><th>Slot</th><th>Status</th></tr>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
<td><?= $row['doctor_name'] ?></td>
<td><?= $row['date'] ?></td>
<td><?= $row['slot'] ?></td>
<td><?= ucfirst($row['status']) ?></td>
</tr>
<?php } ?>
</table>
</div>
</body>
</html>














