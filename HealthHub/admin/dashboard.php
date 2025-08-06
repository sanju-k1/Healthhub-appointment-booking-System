<?php
session_start();
include "../db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// ✅ Approve/Reject Doctors
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    if ($action == "approve") {
        $conn->query("UPDATE doctors SET approved=1 WHERE id=$id");
    } elseif ($action == "reject") {
        $conn->query("DELETE FROM doctors WHERE id=$id");
    }
    header("Location: dashboard.php?msg=$action");
    exit;
}

// ✅ Pending Doctors
$pendingDoctors = $conn->query("SELECT * FROM doctors WHERE approved=0");

// ✅ Chart Data
$stats = $conn->query("SELECT status, COUNT(*) AS cnt FROM appointments GROUP BY status");
$chartData = ['approved'=>0,'pending'=>0,'rejected'=>0];
while($row = $stats->fetch_assoc()) $chartData[strtolower($row['status'])] = (int)$row['cnt'];

// ✅ Filters
$doctorList = $conn->query("SELECT id, name FROM doctors WHERE approved=1");
$patientListFilter = $conn->query("SELECT id, username FROM users WHERE role='patient'");

$where = "1=1";
if (!empty($_GET['status']) && $_GET['status'] != 'all') {
    $status = $conn->real_escape_string($_GET['status']);
    $where .= " AND a.status='$status'";
}
if (!empty($_GET['doctor']) && $_GET['doctor'] != 'all') {
    $doc = intval($_GET['doctor']);
    $where .= " AND d.id=$doc";
}
if (!empty($_GET['patient']) && $_GET['patient'] != 'all') {
    $pat = intval($_GET['patient']);
    $where .= " AND u.id=$pat";
}

// ✅ Appointments
$appointments = $conn->query("
    SELECT u.username AS patient, d.name AS doctor, a.date, a.slot, a.status 
    FROM appointments a
    JOIN users u ON a.patient_id = u.id
    JOIN doctors d ON a.doctor_id = d.id
    WHERE $where
    ORDER BY a.date DESC
");

// ✅ Doctors & Patients List
$doctorsList = $conn->query("SELECT name, specialty, approved FROM doctors ORDER BY id DESC");
$checkColumn = $conn->query("SHOW COLUMNS FROM users LIKE 'email'");
$hasEmail = ($checkColumn->num_rows > 0);
$patientsList = $conn->query("SELECT username" . ($hasEmail ? ", email" : "") . " FROM users WHERE role='patient' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
body { font-family:'Poppins',sans-serif; background:linear-gradient(135deg,rgba(0,255,255,0.1),rgba(255,255,255,0.3)); margin:0; padding:0; transition:background .3s,color .3s; }
.dark-mode { background:#121212; color:#ddd; }
.dark-mode table { background:rgba(50,50,50,0.6); }
.dark-mode th { background:#333; }
.navbar { background:rgba(0,123,255,0.8); padding:15px; color:#fff; font-size:18px; font-weight:bold; display:flex; justify-content:space-between; align-items:center; }
.container { width:94%; margin:20px auto; background:rgba(255,255,255,0.2); backdrop-filter:blur(10px); border-radius:15px; padding:20px; box-shadow:0 4px 15px rgba(0,0,0,0.1); }
h2 { color:#333; }
table { width:100%; border-collapse:collapse; margin-top:15px; background:rgba(255,255,255,0.8); }
th,td { padding:10px; text-align:center; border:1px solid rgba(0,0,0,0.1); }
th { background:rgba(0,123,255,0.7); color:white; }
.btn-approve { background:#28a745; color:white; padding:5px 10px; border-radius:5px; text-decoration:none; }
.btn-reject { background:#dc3545; color:white; padding:5px 10px; border-radius:5px; text-decoration:none; }
/* ✅ Light Colors */
tr.status-approved td { background:#d4edda !important; color:#155724; font-weight:bold; }
tr.status-pending td  { background:#fff3cd !important; color:#856404; font-weight:bold; }
tr.status-rejected td { background:#f8d7da !important; color:#721c24; font-weight:bold; }
.filter-box { display:flex; gap:10px; margin-bottom:15px; flex-wrap:wrap; }
select,.export-btn,#searchInput { padding:8px; border-radius:5px; border:1px solid #ccc; }
.export-btn { background:#28a745; color:white; border:none; cursor:pointer; }
.chart-container { width:200px; height:200px; margin:10px auto; background:white; border-radius:50%; padding:10px; box-shadow:0 3px 10px rgba(0,0,0,0.2); }
/* Small Tables */
.small-table { width:48%; background:rgba(255,255,255,0.7); backdrop-filter:blur(8px); border-radius:10px; padding:10px; margin:10px 1%; float:left; box-shadow:0 3px 8px rgba(0,0,0,0.1); }
/* Dark Mode Toggle */
.toggle-btn { background:#444; color:white; border:none; padding:6px 12px; border-radius:5px; cursor:pointer; }
/* Pagination */
.pagination { margin-top:10px; text-align:center; }
.pagination button { margin:2px; padding:5px 10px; border:none; background:#007bff; color:white; border-radius:5px; cursor:pointer; }
.pagination button.disabled { background:#ccc; cursor:not-allowed; }
</style>
</head>
<body>
<div class="navbar">
    👤 Welcome Sanju
    <div>
        <button class="toggle-btn" onclick="toggleDarkMode()">🌙 Dark Mode</button>
        <a href="../logout.php" style="color:yellow;margin-left:10px;">Logout</a>
    </div>
</div>

<div class="container">

<h2>🩺 Pending Doctor Approvals</h2>
<table>
<tr><th>Name</th><th>Specialty</th><th>Action</th></tr>
<?php while($doc=$pendingDoctors->fetch_assoc()){ ?>
<tr>
<td><?= $doc['name'] ?></td>
<td><?= $doc['specialty'] ?></td>
<td>
    <a href="javascript:void(0);" class="btn-approve" onclick="confirmAction('approve',<?= $doc['id'] ?>)">Approve</a>
    <a href="javascript:void(0);" class="btn-reject" onclick="confirmAction('reject',<?= $doc['id'] ?>)">Reject</a>
</td>
</tr><?php } ?>
</table>

<h2>📊 Appointment Status Overview</h2>
<div class="chart-container"><canvas id="chart"></canvas></div>

<h2>📅 Appointment History</h2>
<div class="filter-box">
    <form method="GET">
        <select name="status">
            <option value="all">All Status</option>
            <option value="pending" <?= ($_GET['status']??'')=='pending'?'selected':'' ?>>Pending</option>
            <option value="approved" <?= ($_GET['status']??'')=='approved'?'selected':'' ?>>Approved</option>
            <option value="rejected" <?= ($_GET['status']??'')=='rejected'?'selected':'' ?>>Rejected</option>
        </select>
        <select name="doctor">
            <option value="all">All Doctors</option>
            <?php while($d=$doctorList->fetch_assoc()){ ?>
                <option value="<?= $d['id'] ?>" <?= ($_GET['doctor']??'')==$d['id']?'selected':'' ?>><?= $d['name'] ?></option>
            <?php } ?>
        </select>
        <select name="patient">
            <option value="all">All Patients</option>
            <?php while($p=$patientListFilter->fetch_assoc()){ ?>
                <option value="<?= $p['id'] ?>" <?= ($_GET['patient']??'')==$p['id']?'selected':'' ?>><?= $p['username'] ?></option>
            <?php } ?>
        </select>
        <button type="submit">Filter</button>
    </form>
    <input type="text" id="searchInput" placeholder="🔍 Search..." onkeyup="searchTable()">
    <button class="export-btn" onclick="exportTable()">⬇ Export</button>
</div>

<table id="appointmentTable">
<tr><th>Patient</th><th>Doctor</th><th>Date</th><th>Slot</th><th>Status</th></tr>
<?php while($row=$appointments->fetch_assoc()){ $statusClass="status-".strtolower($row['status']); ?>
<tr class="<?= $statusClass ?>"><td><?= $row['patient'] ?></td><td><?= $row['doctor'] ?></td><td><?= $row['date'] ?></td><td><?= $row['slot'] ?></td><td><?= ucfirst($row['status']) ?></td></tr>
<?php } ?>
</table>
<div class="pagination" id="pagination"></div>

<!-- Doctors & Patients Lists -->
<div class="small-table">
<h3>👨‍⚕️ Registered Doctors</h3>
<table><tr><th>Name</th><th>Specialty</th><th>Status</th></tr>
<?php while($doc=$doctorsList->fetch_assoc()){ $s=$doc['approved']?'Approved':'Pending'; $c=$doc['approved']?'status-approved':'status-pending'; ?>
<tr class="<?= $c ?>"><td><?= $doc['name'] ?></td><td><?= $doc['specialty'] ?></td><td><?= $s ?></td></tr><?php } ?>
</table>
</div>

<div class="small-table">
<h3>🧑‍🤝‍🧑 Registered Patients</h3>
<table><tr><th>Username</th><?php if($hasEmail) echo "<th>Email</th>"; ?></tr>
<?php while($p=$patientsList->fetch_assoc()){ ?>
<tr><td><?= $p['username'] ?></td><?php if($hasEmail) echo "<td>".$p['email']."</td>"; ?></tr><?php } ?>
</table>
</div>
<div style="clear:both;"></div>
</div>

<script>
// ✅ Chart
new Chart(document.getElementById("chart").getContext("2d"),{
    type:"pie",
    data:{labels:["Approved","Pending","Rejected"],datasets:[{data:[<?= $chartData['approved'] ?>,<?= $chartData['pending'] ?>,<?= $chartData['rejected'] ?>],
    backgroundColor:["#d4edda","#fff3cd","#f8d7da"],borderColor:["#fff","#fff","#fff"],borderWidth:2}]},
    options:{plugins:{legend:{position:'bottom'}},responsive:true}
});

// ✅ Dark Mode
function toggleDarkMode(){document.body.classList.toggle("dark-mode");}

// ✅ SweetAlert
function confirmAction(action,id){Swal.fire({title:(action==='approve')?'Approve Doctor?':'Reject Doctor?',icon:(action==='approve')?'success':'warning',
showCancelButton:true,confirmButtonColor:(action==='approve')?'#28a745':'#dc3545',cancelButtonColor:'#6c757d',
confirmButtonText:(action==='approve')?'✅ Approve':'❌ Reject'}).then(r=>{if(r.isConfirmed)location="?action="+action+"&id="+id;});}

// ✅ Search Filter
function searchTable(){let input=document.getElementById("searchInput").value.toLowerCase();
document.querySelectorAll("#appointmentTable tr").forEach((row,i)=>{if(i===0)return;row.style.display=row.innerText.toLowerCase().includes(input)?"":"none";});}

// ✅ Export All Rows (Not just visible)
function exportTable(){let rows=document.querySelectorAll("#appointmentTable tr");
let csv=[];rows.forEach(r=>{let cols=[];r.querySelectorAll("td,th").forEach(c=>cols.push(c.innerText));csv.push(cols.join(","));});
let blob=new Blob([csv.join("\n")],{type:"text/csv"});let link=document.createElement("a");link.href=URL.createObjectURL(blob);link.download="appointments.csv";link.click();}

// ✅ Pagination Controls
const rows=document.querySelectorAll("#appointmentTable tr");let perPage=8,page=1;
function renderPagination(){let total=Math.ceil((rows.length-1)/perPage);let pag=document.getElementById("pagination");pag.innerHTML="";
for(let i=1;i<=total;i++){let b=document.createElement("button");b.textContent=i;b.onclick=()=>showPage(i);if(i===page)b.disabled=true;pag.appendChild(b);}}
function showPage(p){page=p;rows.forEach((r,i)=>{if(i===0)return;r.style.display=(i>p*perPage||i<=perPage*(p-1))?"none":"";});renderPagination();}
showPage(1);
</script>
<?php if(isset($_GET['msg'])){ ?>
<script>Swal.fire({icon:'<?=($_GET['msg']=="approve")?"success":"error"?>',title:'<?=($_GET['msg']=="approve")?"Doctor Approved!":"Doctor Rejected!"?>',timer:1500,showConfirmButton:false});</script>
<?php } ?>
</body>
</html>





















