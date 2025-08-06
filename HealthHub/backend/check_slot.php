<?php
include('../db.php');

if (isset($_GET['doctor']) && isset($_GET['date']) && isset($_GET['slot'])) {
    $doctor_id = intval($_GET['doctor']);
    $date = $_GET['date'];
    $slot = $_GET['slot'];

    $stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM appointments WHERE doctor_id=? AND date=? AND slot=? AND status!='Rejected'");
    $stmt->bind_param("iss", $doctor_id, $date, $slot);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    echo ($res['cnt'] > 0) ? "taken" : "available";
} else {
    echo "invalid";
}
?>





