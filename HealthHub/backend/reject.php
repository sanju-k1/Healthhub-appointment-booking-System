<?php
session_start();
include("../db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit();
}

if (isset($_GET['id'])) {
    $appointment_id = intval($_GET['id']);
    $stmt = $conn->prepare("UPDATE appointments SET status='Rejected' WHERE id=?");
    $stmt->bind_param("i", $appointment_id);
    if ($stmt->execute()) {
        header("Location: ../doctor/dashboard.php?msg=rejected");
    } else {
        echo "❌ Error rejecting appointment.";
    }
} else {
    echo "❌ Invalid Request.";
}
?>
