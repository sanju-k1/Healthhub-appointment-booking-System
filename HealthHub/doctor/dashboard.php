<?php
session_start();
include "../db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'doctor') {
    header("Location: ../login.php");
    exit;
}

$doctor_user_id = $_SESSION['user_id'];

// ✅ Get Doctor Info
$docQuery = $conn->query("SELECT id, name FROM doctors WHERE user_id=$doctor_user_id");
$doctor = $docQuery->fetch_assoc();
$doctor_id = $doctor['id'];

// ✅ Handle Approve/Reject
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    if ($action == "approve") {
        $conn->query("UPDATE appointments SET status='approved' WHERE id=$id AND doctor_id=$doctor_id");
    } elseif ($action == "reject") {
        $conn->query("UPDATE appointments SET status='rejected' WHERE id=$id AND doctor_id=$doctor_id");
    }
    header("Location: dashboard.php?updated_id=$id&status=$action");
    exit;
}

// ✅ Fetch Appointments
$sql = "
    SELECT a.id, u.username AS patient_name, a.date, a.slot, IFNULL(a.status,'pending') AS status
    FROM appointments a
    JOIN users u ON a.patient_id = u.id
    WHERE a.doctor_id=$doctor_id
    ORDER BY a.date DESC";
$appointments = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .btn-approve { background:#28a745;color:#fff;padding:5px 10px;text-decoration:none;border-radius:5px; }
        .btn-reject { background:#dc3545;color:#fff;padding:5px 10px;text-decoration:none;border-radius:5px; }
        .status-pending { color:orange;font-weight:bold; }
        .status-approved { color:green;font-weight:bold; }
        .status-rejected { color:red;font-weight:bold; }
        table { width:100%; border-collapse:collapse; margin-top:20px; }
        th, td { border:1px solid #ddd; padding:10px; text-align:center; }
        th { background:#007bff; color:white; }
        .navbar { background:linear-gradient(90deg,#007bff,#00c6ff);padding:15px;color:white;font-weight:bold; }
        .container { width:80%;margin:auto;margin-top:30px;background:white;padding:20px;border-radius:10px;box-shadow:0 4px 15px rgba(0,0,0,0.1); }
        /* ✅ Animation Effects */
        .row-animate { transition: background 0.8s ease; }
        .row-approved { background:rgba(40,167,69,0.2); }
        .row-rejected { background:rgba(220,53,69,0.2); }
    </style>
    <script>
        function confirmAction(action, id) {
            Swal.fire({
                title: (action === 'approve') ? 'Approve Appointment?' : 'Reject Appointment?',
                text: (action === 'approve') 
                      ? "Once approved, the patient will be notified." 
                      : "Once rejected, the patient will be notified.",
                icon: (action === 'approve') ? 'success' : 'warning',
                showCancelButton: true,
                confirmButtonColor: (action === 'approve') ? '#28a745' : '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: (action === 'approve') ? '✅ Approve' : '❌ Reject'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "?action=" + action + "&id=" + id;
                }
            });
        }
    </script>
</head>
<body>
<div class="navbar">👨‍⚕️ Welcome Dr. <?= htmlspecialchars($doctor['name']) ?> | <a href="../logout.php" style="color:yellow;">Logout</a></div>
<div class="container">
<h2>📅 Your Appointments</h2>
<table>
<tr><th>Patient</th><th>Date</th><th>Slot</th><th>Status</th><th>Action</th></tr>
<?php while($row = $appointments->fetch_assoc()) { 
    $status = strtolower(trim($row['status']));
    $rowClass = "";
    if(isset($_GET['updated_id']) && $_GET['updated_id'] == $row['id']) {
        $rowClass = ($_GET['status'] == 'approve') ? "row-approved" : "row-rejected";
    }
?>
<tr id="row<?= $row['id'] ?>" class="row-animate <?= $rowClass ?>">
<td><?= htmlspecialchars($row['patient_name']) ?></td>
<td><?= htmlspecialchars($row['date']) ?></td>
<td><?= htmlspecialchars($row['slot']) ?></td>
<td>
    <?php if($status == 'pending'){ ?>
        <span class="status-pending">⏳ Pending</span>
    <?php } elseif($status == 'approved'){ ?>
        <span class="status-approved">✅ Approved</span>
    <?php } elseif($status == 'rejected'){ ?>
        <span class="status-rejected">❌ Rejected</span>
    <?php } ?>
</td>
<td>
<?php if($status == 'pending'){ ?>
    <a href="javascript:void(0);" class="btn-approve" onclick="confirmAction('approve', <?= $row['id'] ?>)">Approve</a>
    <a href="javascript:void(0);" class="btn-reject" onclick="confirmAction('reject', <?= $row['id'] ?>)">Reject</a>
<?php } else { echo "—"; } ?>
</td>
</tr>
<?php } ?>
</table>
</div>

<?php if(isset($_GET['status'])) { ?>
<script>
    Swal.fire({
        icon: '<?php echo ($_GET['status'] == "approve") ? "success" : "error"; ?>',
        title: '<?php echo ($_GET['status'] == "approve") ? "✅ Appointment Approved!" : "❌ Appointment Rejected!"; ?>',
        showConfirmButton: false,
        timer: 1500
    });
</script>
<?php } ?>
</body>
</html>





















