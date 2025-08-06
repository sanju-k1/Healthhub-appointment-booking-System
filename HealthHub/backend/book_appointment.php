<?php
session_start();
include __DIR__ . "/../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_SESSION['user_id'];  // logged-in patient
    $doctor_id = $_POST['doctor_id'];
    $date = $_POST['date'];
    $slot = $_POST['slot'];

    // ✅ Check if slot is already taken
    $check = $conn->prepare("SELECT id FROM appointments WHERE doctor_id=? AND date=? AND slot=?");
    $check->bind_param("iss", $doctor_id, $date, $slot);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $_SESSION['error'] = "❌ Slot already taken!";
        header("Location: ../patient/dashboard.php");
        exit;
    }
    $check->close();

    // ✅ Insert appointment with status 'pending'
    $stmt = $conn->prepare("
        INSERT INTO appointments (patient_id, doctor_id, date, slot, status) 
        VALUES (?, ?, ?, ?, 'pending')
    ");
    $stmt->bind_param("iiss", $patient_id, $doctor_id, $date, $slot);

    if ($stmt->execute()) {
        $_SESSION['success'] = "✅ Appointment request sent! Waiting for doctor's approval.";
    } else {
        $_SESSION['error'] = "❌ Failed to book appointment.";
    }

    header("Location: ../patient/dashboard.php");
    exit;
}
?>












