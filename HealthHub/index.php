<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HealthHub - Online Appointment System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(0, 255, 255, 0.2), rgba(0, 255, 255, 0.25)),
                        url('assets/img/hospital-bg.jpg') no-repeat center center/cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .home-box {
            background: rgba(255, 255, 255, 0.08);
            border: 2px solid rgba(0, 255, 255, 0.3);
            backdrop-filter: blur(18px);
            padding: 50px 60px;
            border-radius: 20px;
            width: 85%;
            max-width: 600px;
            text-align: center;
            box-shadow: 0 6px 30px rgba(0, 255, 255, 0.25);
            animation: fadeIn 1s ease-in-out;
        }

        .home-box h1 {
            font-size: 2.7em;
            color: #00ffff;
            text-shadow: 0 0 12px rgba(0, 255, 255, 0.8);
            margin-bottom: 15px;
        }

        .home-box h1 span:last-child {
            color: #ff5f6d;
        }

        .home-box p {
            font-size: 1.4em;
            font-weight: 500;
            margin-bottom: 35px;
            color: #ffffff;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        a {
            text-decoration: none;
            padding: 14px 28px;
            font-weight: bold;
            font-size: 1em;
            border-radius: 25px;
            transition: 0.3s ease-in-out;
            display: inline-block;
        }

        .login-btn {
            background: #00ffff;
            color: #000;
            box-shadow: 0 0 12px rgba(0, 255, 255, 0.5);
        }

        .patient-btn {
            background: #1e90ff;
            color: #fff;
            box-shadow: 0 0 12px rgba(30, 144, 255, 0.5);
        }

        .doctor-btn {
            background: #0077b6;
            color: #fff;
            box-shadow: 0 0 12px rgba(0, 119, 182, 0.5);
        }

        a:hover {
            transform: scale(1.08);
            box-shadow: 0 0 18px rgba(0, 255, 255, 0.6);
            opacity: 0.95;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="home-box">
        <h1>🏥 Welcome to <span>Health</span><span>Hub</span></h1>
        <p>🌟 <b>Book appointments with top doctors in just a few clicks!</b> 🌟</p>
        <div class="button-group">
            <a href="login.php" class="login-btn">🔐 Login</a>
            <a href="register_patient.php" class="patient-btn">🧑‍⚕️ Patient Register</a>
            <a href="register_doctor.php" class="doctor-btn">👨‍⚕️ Doctor Register</a>
        </div>
    </div>
</body>
</html>


