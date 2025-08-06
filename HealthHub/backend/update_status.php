<?php
session_start();
include "../db.php";

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = $_GET['status'];

    // Update appointment status
    $conn->query("UPDATE appointments SET status='$status' WHERE id=$id");

    header("Location: dashboard.php");
    exit;
}
?>


