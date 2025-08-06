<?php
include("../db.php");
if(isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = $_GET['status'];
    $q = $conn->prepare("UPDATE appointments SET status=? WHERE id=?");
    $q->bind_param("si", $status, $id);
    $q->execute();
    header("Location: ../doctor/dashboard.php");
    exit;
} else {
    echo "Invalid Request!";
}
?>
