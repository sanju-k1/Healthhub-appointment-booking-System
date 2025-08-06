<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = md5($_POST['password']);
    $role = $_POST['role'];
    $specialty = isset($_POST['specialty']) ? trim($_POST['specialty']) : "";

    // ✅ Check if username already exists
    $check = $conn->prepare("SELECT * FROM users WHERE username=?");
    $check->bind_param("s", $username);
    $check->execute();
    $res = $check->get_result();
    
    if ($res->num_rows > 0) {
        $error = "⚠️ Username already exists!";
    } else {
        // ✅ Insert into users
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $role);
        $stmt->execute();
        $user_id = $stmt->insert_id;

        // ✅ If role is doctor, insert into doctors with approved=0
        if ($role == 'doctor') {
            $doc = $conn->prepare("INSERT INTO doctors (user_id, name, specialty, approved) VALUES (?, ?, ?, 0)");
            $doc->bind_param("iss", $user_id, $username, $specialty);
            $doc->execute();
        }

        $_SESSION['success'] = "✅ Registration successful! You can now log in.";
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - HealthHub</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <style>
        body {
            background: url('assets/img/register-bg.jpg') no-repeat center center/cover;
            font-family: Arial, sans-serif;
            color: #fff;
            text-align: center;
        }
        .register-container {
            width: 400px;
            background: rgba(0, 0, 0, 0.7);
            margin: 8% auto;
            padding: 20px;
            border-radius: 10px;
        }
        label { display: block; margin-top: 10px; text-align: left; }
        select, input, button { width: 100%; padding: 10px; margin-top: 5px; }
        button { background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
    <script>
        function toggleSpecialty() {
            let role = document.getElementById("role").value;
            document.getElementById("specialtyDiv").style.display = (role === 'doctor') ? 'block' : 'none';
        }
    </script>
</head>
<body>
<div class="register-container">
    <h2>📝 Register</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Role</label>
        <select name="role" id="role" onchange="toggleSpecialty()" required>
            <option value="patient">Patient</option>
            <option value="doctor">Doctor</option>
        </select>

        <div id="specialtyDiv" style="display:none;">
            <label>Specialty (For Doctors)</label>
            <input type="text" name="specialty" placeholder="e.g. Cardiology">
        </div>

        <button type="submit">Register</button>
    </form>
</div>
</body>
</html>



