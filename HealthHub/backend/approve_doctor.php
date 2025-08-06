<?php
include "../db.php";
$id = $_GET['id'];
$conn->query("UPDATE doctors SET approved=1 WHERE id=$id");
header("Location: ../admin/dashboard.php");
?>
