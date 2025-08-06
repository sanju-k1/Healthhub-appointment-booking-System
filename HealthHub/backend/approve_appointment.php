<?php
include "../db.php";
$id = $_GET['id'];
$conn->query("UPDATE appointments SET status='approved' WHERE id=$id");
header("Location: ../doctor/dashboard.php");
?>
