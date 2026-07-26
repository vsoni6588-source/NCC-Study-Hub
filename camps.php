<?php
session_start();
include("includes/config.php");
?>

<!DOCTYPE html>
<html>
<head>
<title>NCC Camps</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<style>

/* SECTION */
.camp-section{
    max-width:1100px;
    margin:100px auto 40px;
    padding:0 10px;
}

/* GRID */
.camp-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(250px,1fr));
    gap:20px;
}

/* CARD */
.camp-card{
    background:#1e293b;
    color:white;
    padding:20px;
    border-radius:12px;
    transition:0.3s;
}

.camp-card:hover{
    transform:translateY(-5px);
}

.camp-card i{
    font-size:28px;
    margin-bottom:10px;
    color:#22c55e;
}

.camp-card h3{
    margin-bottom:8px;
}

.camp-card p{
    font-size:13px;
    color:#cbd5e1;
}

/* MATERIAL CARD */
.material-card{
    background:#ffffff;
    padding:15px;
    border-radius:10px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.material-card h4{
    margin:0;
}

.open-btn{
    background:#2563eb;
    color:white;
    padding:6px 12px;
    border-radius:6px;
    text-decoration:none;
}

.open-btn:hover{
    background:#1d4ed8;
}

</style>

<body>

<?php include("includes/navbar.php"); ?>

<div class="camp-section">

<h2 class="main-title"><b>NCC CAMPS & PREPARATION</b></h2>

<!-- ============================= -->
<!-- 🪖 CAMP TYPES -->
<!-- ============================= -->

<h3 style="margin-bottom:15px;">🪖 Types of NCC Camps</h3>

<div class="camp-grid">

<div class="camp-card">
<i class="fa-solid fa-campground"></i>
<h3>CATC</h3>
<p>Combined Annual Training Camp for basic training, discipline and teamwork.</p>
</div>

<div class="camp-card">
<i class="fa-solid fa-flag"></i>
<h3>RDC</h3>
<p>Republic Day Camp in Delhi. Highest level camp with national exposure.</p>
</div>

<div class="camp-card">
<i class="fa-solid fa-bullseye"></i>
<h3>TSC</h3>
<p>Thal Sainik Camp focused on shooting, drill and field craft.</p>
</div>

<div class="camp-card">
<i class="fa-solid fa-anchor"></i>
<h3>NSC</h3>
<p>Nau Sainik Camp for Naval cadets focusing on seamanship skills.</p>
</div>

<div class="camp-card">
<i class="fa-solid fa-plane"></i>
<h3>Vayu Camp</h3>
<p>Air wing camp focused on aviation and air force training.</p>
</div>

<div class="camp-card">
<i class="fa-solid fa-mountain"></i>
<h3>Adventure Camps</h3>
<p>Trekking, rock climbing and outdoor survival activities.</p>
</div>

</div>

<!-- ============================= -->
<!-- 📚 CAMP MATERIAL -->
<!-- ============================= -->

<h3 style="margin:30px 0 15px;">📚 Camp Preparation Material</h3>

<div class="camp-grid">

<?php
$result = mysqli_query($conn,
"SELECT * FROM camp_material ORDER BY id DESC");

if(mysqli_num_rows($result) > 0){
while($row = mysqli_fetch_assoc($result)){
?>

<div class="material-card">

<div>
<h4><?php echo $row['title']; ?></h4>
<p style="font-size:12px; color:#666;">
<?php echo substr($row['description'],0,60); ?>...
</p>
</div>

<a href="<?php echo $row['file_path']; ?>" target="_blank" class="open-btn">
Open
</a>

</div>

<?php } } else { ?>

<p style="color:white;">No camp materials added yet.</p>

<?php } ?>

</div>

</div>

</body>
</html>
