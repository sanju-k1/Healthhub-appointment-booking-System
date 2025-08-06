<?php
session_start();
include "db.php";

$msg = "";

// ✅ Handle Patient Registration
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    // ✅ Check if username already exists
    $check = $conn->query("SELECT * FROM users WHERE username='$username'");
    if ($check->num_rows > 0) {
        echo "<script>alert('⚠️ Username already taken! Please choose another.');</script>";
    } else {
        $conn->query("INSERT INTO users (username, password, role) VALUES ('$username', '$password', 'patient')");
        echo "<script>alert('✅ Registration successful! You can now log in.'); window.location.href='login.php';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Registration - HealthHub</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(0,255,255,0.15), rgba(0,255,255,0.2)),
                        url('assets/img/hospital-bg.jpg') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-box {
            background: rgba(255,255,255,0.08);
            border: 2px solid rgba(0,255,255,0.3);
            backdrop-filter: blur(18px);
            padding: 40px;
            width: 400px;
            border-radius: 15px;
            box-shadow: 0 4px 30px rgba(0,255,255,0.3);
            animation: fadeIn 1s ease-in-out;
            color: white;
            text-align: center;
        }

        h2 {
            color: #00ffff;
            margin-bottom: 20px;
            text-shadow: 0 0 12px rgba(0,255,255,0.6);
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            outline: none;
        }

        button {
            background: #00ffff;
            border: none;
            color: black;
            padding: 12px;
            font-weight: bold;
            width: 100%;
            border-radius: 25px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(0,255,255,0.5);
        }

        a {
            color: #00ffff;
            text-decoration: none;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>👤 Patient Registration</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <button type="submit">📝 Register</button>
        </form>
        <p><a href="index.php">⬅ Back to Home</a></p>
    </div>
</body>
</html>

