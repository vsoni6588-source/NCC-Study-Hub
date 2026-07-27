
<?php
session_start();
include("includes/config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Leadership Training - NCC Study Hub</title>

<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/wing.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<style>

/* ========================= */
/* TEST BOX (FIXED) */
/* ========================= */

.test-box {
    margin-top: 40px;
    margin-bottom: 60px;
    background: linear-gradient(135deg, #1e293b, #0e3b03);
    border-radius: 15px;
    padding: 25px;

    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;

    color: white;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
}

/* LEFT */
.test-left {
    flex: 1;
    text-align: center;
}

/* TEXT */
.test-left h2 {
    font-size: 26px;
    margin-bottom: 10px;
}

.test-left p {
    font-size: 14px;
    color: #cbd5e1;
    margin-bottom: 20px;
}

/* PROGRESS */
.test-progress {
    margin-bottom: 20px;
}

.progress-bar {
    width: 100%;
    height: 10px;
    background: #334155;
    border-radius: 20px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(to right, #22c55e, #4ade80);
}

/* BUTTON */
.test-btn {
    padding: 12px 25px;
    background: linear-gradient(to right, #22c55e, #16a34a);
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    border: none;
}

.test-btn.disabled {
    background: #64748b;
    cursor: not-allowed;
}

/* RIGHT ICON */
.test-right {
    font-size: 60px;
    color: #22c55e;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .test-box {
        flex-direction: column;
        text-align: center;
    }
}

</style>

<body>

<!-- NAVBAR -->
<?php include("includes/navbar.php"); ?>

<!-- HERO -->
<section class="armyhero">
<div class="hero-text">
<h2>LEADERSHIP TRAINING</h2>
<p>Confidence | Discipline | Command Skills</p>
</div>
</section>

<!-- SUBJECT SECTION -->
<section class="training-section">

<h2 class="section-title">LEADERSHIP MODULES</h2>

<div class="training-list">

<?php
// ✅ FETCH LEADERSHIP SUBJECTS
$result = mysqli_query($conn, 
"SELECT * FROM subjects WHERE type='leadership' ORDER BY id DESC");

if(mysqli_num_rows($result) > 0){

    while($row = mysqli_fetch_assoc($result)){

        $pdf = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT * FROM resources WHERE subject_id=".$row['id']." LIMIT 1"));

        if($pdf){
?>

<a href="view.php?id=<?php echo $row['id']; ?>" class="training-card">

    <div class="icon">
        <i class="fa-solid fa-user-tie"></i>
    </div>

    <div class="text">
        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
        <p>Click to learn leadership skills</p>
    </div>

    <div class="card-arrow">➝</div>

</a>

<?php
        }

    }

} else {
    echo "<p style='padding:20px;'>No leadership modules available.</p>";
}
?>

</div>

</section>

<!-- TEST SECTION -->
<div class="container">

<div class="test-box">

<div class="test-left">

<h2>Ready for Leadership Assessment?</h2>

<p>
Develop decision-making, communication, and command skills.
Complete modules to unlock leadership test.
</p>

<?php
$user_id = $_SESSION['user_id'];

// TOTAL
$total = mysqli_fetch_assoc(mysqli_query($conn, 
"SELECT COUNT(*) as total FROM subjects WHERE type='leadership'"
))['total'];

// COMPLETED
$completed = mysqli_fetch_assoc(mysqli_query($conn, 
"SELECT COUNT(*) as done 
FROM user_progress up
JOIN subjects s ON up.subject_id = s.id
WHERE up.user_id='$user_id' 
AND s.type='leadership'"
))['done'];

$progress = ($total > 0) ? round(($completed/$total)*100) : 0;
?>

<!-- PROGRESS -->
<div class="test-progress">
<div class="progress-bar">
<div class="progress-fill" style="width: <?php echo $progress; ?>%"></div>
</div>
<span><?php echo $progress; ?>% Completed</span>
</div>

<!-- BUTTON -->
<?php if($progress >= 50){ ?>
<a href="leadership_test.php" class="test-btn">
▶ Start Test
</a>
<?php } else { ?>
<button class="test-btn disabled">
                🔒 Locked (Complete 50%)
<?php } ?>

</div>

<div class="test-right">
<i class="fa-solid fa-user-shield"></i>
</div>

</div>

</div>

<!-- FOOTER -->
<footer class="main-footer">

<div class="footer-container">

<div class="footer-column">
<h3>SITE MAP</h3>
<ul>
<li><a href="#">About</a></li>
<li><a href="#">Wings</a></li>
<li><a href="#">Learning</a></li>
<li><a href="#">Events</a></li>
</ul>
</div>

<div class="footer-column">
<h3>QUICK LINKS</h3>
<ul>
<li><a href="#">Quick Links</a></li>
<li><a href="#">Partners</a></li>
<li><a href="#">Contact</a></li>
</ul>
</div>

<div class="footer-logo">
<img src="images/ncc logo.png">
</div>

<div class="footer-column">
<h3>CONTACT</h3>
<p>📞 +91 99325 5900</p>
<p>✉ email@studyhub.com</p>
<p>📍 Noida, India</p>
</div>

<div class="footer-column">
<h3>SOCIAL MEDIA</h3>

<div class="social-icons">
<a href="#" class="social-circle">f</a>
<a href="#" class="social-circle">ig</a>
<a href="#" class="social-circle">yt</a>
<a href="#" class="social-circle">in</a>
</div>

</div>

</div>

<div class="footer-bottom">
<p>© 2026 NCC Study Hub</p>
</div>

</footer>
</body>
</html>
