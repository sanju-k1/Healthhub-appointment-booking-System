<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $name = $_POST['name'];
    $specialty = $_POST['specialty'];

    // Insert doctor as user (role=doctor)
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'doctor')");
    $stmt->bind_param("ss", $username, $password);
    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;

        // Insert into doctors table (status pending)
        $stmt2 = $conn->prepare("INSERT INTO doctors (name, specialty, user_id, status) VALUES (?, ?, ?, 'pending')");
        $stmt2->bind_param("ssi", $name, $specialty, $user_id);
        $stmt2->execute();

        $_SESSION['success'] = "✅ Registration successful! Please wait for admin approval.";
        header("Location: login.php");
        exit;
    } else {
        $error = "❌ Username already exists!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Doctor Registration - HealthHub</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>🩺 Doctor Registration</h2>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <label>Full Name</label>
            <input type="text" name="name" required>
            <label>Specialty</label>
            <input type="text" name="specialty" required>
            <label>Username</label>
            <input type="text" name="username" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>

