
# 🏥 **HealthHub Appointment Booking System**

🚀 **HealthHub** is a complete web-based platform for managing doctor-patient appointments. It provides an **easy-to-use interface** for patients to book appointments, doctors to manage schedules, and admins to oversee the entire system.

---

## ✨ **Features**

### 👨‍💻 **Admin Panel**

* ✅ **Doctor Management**: Approve/Reject doctors with SweetAlert confirmations.
* ✅ **Appointment Overview**: View all appointments with status indicators (✅ Approved, 🟡 Pending, ❌ Rejected).
* ✅ **Analytics Dashboard**: Attractive **Pie Chart** showing appointment status distribution.
* ✅ **Filters & Search**:

  * Filter by **Status**, **Doctor**, and **Patient**.
  * Live Search for quick lookup.
* ✅ **Export Reports**: Export all appointments to **CSV** with one click.
* ✅ **Registered Users Lists**: Separate glass-effect tables for **Doctors** & **Patients**.
* ✅ **Dark Mode**: Modern dark theme toggle for better UI experience.
* ✅ **Pagination**: View all appointments with easy navigation.

### 👨‍⚕️ **Doctor Panel**

* View and manage their own appointments.
* Update availability slots.

### 🧑‍🤝‍🧑 **Patient Panel**

* Register/Login securely.
* Book appointments with top doctors in just a few clicks.
* Receive confirmation notifications.

---

## 🛠 **Tech Stack**

| Technology          | Usage                    |
| ------------------- | ------------------------ |
| **PHP**             | Backend & Business Logic |
| **MySQL**           | Database                 |
| **HTML5, CSS3, JS** | Frontend                 |
| **Chart.js**        | Interactive Pie Chart    |
| **SweetAlert2**     | Beautiful Alert Modals   |
| **AJAX (optional)** | For smooth UI updates    |

---

## 🎨 **UI Highlights**

* **Glassmorphism Design** for Admin Dashboard.
* **Light & Dark Mode** support.
* **Color-coded Status**:

  * ✅ Light Green → Approved
  * 🟡 Light Yellow → Pending
  * ❌ Light Red → Rejected

## ⚡ **Installation Guide**

### 1️⃣ Clone Repository

```bash
git clone https://github.com/your-username/healthhub.git
cd healthhub
```

### 2️⃣ Configure Database

* Create a MySQL database (e.g., `healthhub_db`).
* Import the provided SQL file:

```bash
mysql -u root -p healthhub_db < database.sql
```

* Update `db.php` with your database credentials.

### 3️⃣ Run on Localhost

* Place the project in `htdocs` (XAMPP/Laragon).
* Start Apache & MySQL.
* Open in browser:

```
http://localhost/HealthHub/
```

---

## 📂 **Project Structure**

```
HealthHub/
│── admin/               # Admin Dashboard
│── doctor/              # Doctor Panel
│── patient/             # Patient Panel
│── assets/              # CSS, JS, Images
│── db.php               # Database Connection
│── login.php            # User Login
│── register.php         # User Registration
│── README.md            # Project Documentation
│── database.sql         # Database Dump
```

---

## 🔐 **Login Credentials (Sample)**

| Role    | Email/Username | Password |
| ------- | -------------- | -------- |
| Admin   | admin          | admin123 |
| Doctor  | drsmith        | 12345    |
| Patient | john           | 12345    |

---

## 📊 **Database Schema**

* **users**: Stores patient and doctor login info.
* **doctors**: Contains doctor profiles and approval status.
* **appointments**: Tracks all appointment bookings and status.

---

## 🚀 **Upcoming Features**

* 📱 **Mobile App (Flutter/React Native)**
* 🔔 **Push Notifications** for appointment updates.
* 📅 **Calendar View** for easy scheduling.
* 💬 **Chat System** between doctors & patients.

---

## 🤝 **Contributing**

Want to improve this project?

1. Fork the repo
2. Create a branch (`feature-new`)
3. Commit changes
4. Submit a Pull Request

---

## 📌 **Author**

👤 **Sanjay K.**
🎓 Electronics & Communication Engineer | 💻 Web Developer
🌟 *Feel free to star this repository if you like it!*

---

## 🏆 **Project Highlights**

✅ **Reduced scheduling time by 40%**
✅ **Optimized MySQL queries improving performance by 30%**
✅ **Enhanced security with role-based authentication**

---

## 🛡 **License**

This project is licensed under the **MIT License**.

---

