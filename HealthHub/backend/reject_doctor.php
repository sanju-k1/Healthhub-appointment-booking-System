<?php
include "../db.php";
$id = $_GET['id'];
$conn->query("DELETE FROM doctors WHERE id=$id");
header("Location: ../admin/dashboard.php");
?>
