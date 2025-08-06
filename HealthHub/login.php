<?php
session_start();
include __DIR__ . "/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // match MD5 stored password

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header("Location: admin/dashboard.php");
        } elseif ($user['role'] == 'doctor') {
            header("Location: doctor/dashboard.php");
        } else {
            header("Location: patient/dashboard.php");
        }
        exit;
    } else {
        $error = "❌ Invalid Username or Password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - HealthHub</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(0, 255, 255, 0.1), rgba(0, 255, 255, 0.2)), 
                        url('assets/img/hospital-bg.jpg') no-repeat center center/cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.08);
            border: 2px solid rgba(0, 255, 255, 0.3);
            backdrop-filter: blur(20px);
            padding: 40px 50px;
            border-radius: 15px;
            width: 350px;
            box-shadow: 0 4px 30px rgba(0, 255, 255, 0.2);
            animation: fadeIn 1.2s ease-in-out;
            text-align: center;
        }

        .login-container h2 {
            color: #00ffff;
            margin-bottom: 20px;
            text-shadow: 0 0 8px rgba(0, 255, 255, 0.5);
        }

        .login-container input {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid rgba(0, 255, 255, 0.5);
            border-radius: 5px;
            background: rgba(0, 0, 0, 0.3);
            color: #fff;
            font-size: 1em;
        }

        .login-container input:focus {
            outline: none;
            border: 1px solid #00ffff;
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
        }

        .login-container button {
            width: 95%;
            padding: 12px;
            background: #00ffff;
            color: #000;
            border: none;
            font-weight: bold;
            border-radius: 25px;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-container button:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.6);
        }

        .error {
            background: rgba(255, 0, 0, 0.2);
            color: #ff5f5f;
            padding: 8px;
            border-radius: 5px;
            margin-bottom: 10px;
            text-shadow: 0 0 5px rgba(255, 0, 0, 0.4);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>🔐 Login</h2>
        <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <button type="submit">Login</button>
        </form>
        <p style="margin-top:15px; font-size:1.1em; font-weight:bold; color:#ffffff; text-shadow:0 0 8px rgba(0,255,255,0.6);">
    📝 <span style="color:#00ffff;">New here?</span> 
    <a href="register_patient.php" style="color:#ff5f6d; font-weight:bold; text-decoration:underline; text-shadow:0 0 5px rgba(255,95,109,0.6);">
        Register Now
    </a>
</p>

    </div>
</body>
</html>



