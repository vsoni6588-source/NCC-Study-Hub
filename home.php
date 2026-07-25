<?php
session_start();
include("includes/config.php");

// USER ID
$user_id = $_SESSION['user_id'] ?? 0;

// USER DATA
$userData = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT * FROM users WHERE id='$user_id'"
));

// CLEAN WING NAME
$userWing = strtolower(trim($userData['wing'] ?? ''));
$userWing = str_replace(' ', '', $userWing);

// MAP WING ➝ ID
$wingMap = [
    "army" => 1,
    "navy" => 2,
    "airforce" => 3
];

$wing_id = $wingMap[$userWing] ?? 0;

// TOTAL SUBJECTS
$total = 0;
$completed = 0;

if($wing_id > 0){

    // TOTAL SUBJECTS OF USER WING
    $totalQuery = mysqli_query($conn,
        "SELECT COUNT(*) as total 
         FROM subjects 
         WHERE wing_id='$wing_id'"
    );
    $total = mysqli_fetch_assoc($totalQuery)['total'];

    // COMPLETED SUBJECTS (STRICT FILTER)
    $completedQuery = mysqli_query($conn,
        "SELECT COUNT(*) as done 
         FROM user_progress up
         JOIN subjects s ON up.subject_id = s.id
         WHERE up.user_id='$user_id' 
         AND up.status='completed'
         AND s.wing_id='$wing_id'"
    );
    $completed = mysqli_fetch_assoc($completedQuery)['done'];
}

// FINAL %
$progress = ($total > 0) ? round(($completed/$total)*100) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NCC Study Hub</title>

<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<style>
    /* NCC GOALS SECTION */
.ncc-goals{
    max-width:1100px;
    margin:50px auto;
    padding:0 10px;
}

/* CONTAINER */
.goals-container{
    display:flex;
    gap:20px;
    justify-content:space-between;
    flex-wrap:wrap;
}

/* CARD */
.goal-card{
    flex:1;
    min-width:280px;
    background:#61643c; /* SAME as module-card */
    color:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 4px 6px rgba(0,0,0,0.2);
    transition:0.3s;
}

/* HOVER SAME AS MODULE */
.goal-card:hover{
    transform:translateY(-5px);
    background:#555835;
}

/* ICON */
.goal-card i{
    font-size:28px;
    margin-bottom:10px;
    color:#d4af37; /* GOLD */
}

/* TITLE */
.goal-card h3{
    margin-bottom:10px;
    font-size:18px;
}

/* TEXT */
.goal-card p{
    font-size:14px;
    opacity:0.85;
    margin-bottom:15px;
}

/* LINK SAME STYLE */
.goal-card a{
    color:#d4af37;
    text-decoration:none;
    font-weight:600;
    font-size:14px;
}

.goal-card a:hover{
    text-decoration:underline;
}

/* RESPONSIVE */
@media(max-width:768px){
    .goals-container{
        flex-direction:column;
    }
}
</style>

<body>

<!-- ✅ NAVBAR -->
<?php include("includes/navbar.php"); ?>

<!-- HERO SECTION -->
<section class="hero">

<div class="hero-text">

<h4>EMPOWERING FUTURE LEADERS</h4>

<?php if(isset($_SESSION['user_name'])) { ?>
<h3 style="color:#ffcc00;">
Welcome, <?php echo $_SESSION['user_name']; ?> 👋
</h3>
<?php } ?>

<h1>
THE DIGITAL LEARNING HUB FOR <br>
<h2>THE NATIONAL CADET CORPS</h2>
</h1>

<p>
Excellence in Military Training, Leadership Development, and Academic Discipline for all NCC Cadets.
</p>

<div class="hero-btn">

<a href="home.php" class="btn-outline">Explore Wings</a>
</div>

</div>

</section>

<!-- WINGS -->
<section class="wings">

<div class="wing-card army">
<i class="fa-solid fa-person-military-rifle"></i>
<h3>Army Wing</h3>
<p>Military training & discipline for Army cadets.</p>
<a href="army.php">Learn More</a>
</div>

<div class="wing-card navy">
<i class="fa-solid fa-anchor"></i>
<h3>Naval Wing</h3>
<p>Maritime training and naval discipline.</p>
<a href="naval.php">Learn More</a>
</div>

<div class="wing-card air">
<i class="fa-solid fa-plane"></i>
<h3>Air Wing</h3>
<p>Aviation and air force training.</p>
<a href="air.php">Learn More</a>
</div>

</section>


<section class="ncc-goals">

<h2 class="main-title"><b>NCC EXAMS, CAMPS & LEADERSHIP</b></h2>

<div class="goals-container">

    <!-- EXAMS -->
    <div class="goal-card">
        <i class="fa-solid fa-graduation-cap"></i>
        <h3>Certificate Exams</h3>
        <p>Prepare for NCC A, B & C certificates with structured study material.</p>
        <a href="nccexam.php">Start Preparation</a>
    </div>

    <!-- CAMPS -->
    <div class="goal-card">
        <i class="fa-solid fa-campground"></i>
        <h3>Camps & Activities</h3>
        <p>Get ready for RDC, CATC, TSC and other NCC camps.</p>
        <a href="camps.php">Explore Camps</a>
    </div>

    <!-- LEADERSHIP -->
    <div class="goal-card">
        <i class="fa-solid fa-user-tie"></i>
        <h3>Leadership Training</h3>
        <p>Build discipline, confidence and leadership skills.</p>
        <a href="leadership.php">Learn More</a>
    </div>

</div>

</section>



<!-- TRAINING MODULES -->
<div class="container">

<h2 class="main-title"><b>TRAINING MODULES & RESOURCES</b></h2>

<?php
$query = "SELECT s.*, r.file_path 
          FROM subjects s
          LEFT JOIN resources r ON s.id = r.subject_id
          WHERE s.type='training'";

$result = mysqli_query($conn, $query);
?>

<div class="grid-container">

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<div class="module-card">
<h3 class="module-title"><?php echo $row['title']; ?></h3>
<p class="module-desc"><?php echo $row['description']; ?></p><br>

<?php if(!empty($row['file_path'])) { ?>
<a href="view.php?id=<?php echo $row['id']; ?>" class="learn-link">
Learn More
</a>
<?php } else { ?>
<span>No PDF Available</span>
<?php } ?>

</div>

<?php } ?>

</div>
</div>

<!-- DRILL SECTION -->
<div class="container">

<h2 class="main-title"><b>DRILL & GUARD OF HONOUR</b></h2>

<?php
$query2 = "SELECT s.*, r.file_path 
           FROM subjects s
           LEFT JOIN resources r ON s.id = r.subject_id
           WHERE s.type='drill&goh'";

$result2 = mysqli_query($conn, $query2);
?>

<div class="grid-container">

<?php while($row = mysqli_fetch_assoc($result2)) { ?>

<div class="module-card">
<h3 class="module-title"><?php echo $row['title']; ?></h3>
<p class="module-desc"><?php echo $row['description']; ?></p><br>

<?php if(!empty($row['file_path'])) { ?>
<a href="view.php?id=<?php echo $row['id']; ?>" class="learn-link">
Learn More
</a>
<?php } else { ?>
<span>No PDF Available</span>
<?php } ?>

</div>

<?php } ?>

</div>
</div>

<!-- NEWS -->
<!-- NEWS -->
<?php
$query = "SELECT * FROM news ORDER BY created_at DESC LIMIT 6";
$result = mysqli_query($conn, $query);

$first = true;
?>

<?php
$query = "SELECT * FROM news ORDER BY created_at DESC LIMIT 10";
$result = mysqli_query($conn, $query);
?>

<section class="news-section">

<h2 class="news-title"><b>LATEST NEWS & UPDATES</b></h2>

<div class="news-wrapper">

    <!-- LEFT ARROW -->
    <div class="arrow left" onclick="scrollNews(-1)">⟨</div>

    <!-- SLIDER -->
    <div class="news-slider" id="newsSlider">

    <?php
    $query = "SELECT * FROM news ORDER BY created_at DESC LIMIT 10";
    $result = mysqli_query($conn, $query);

    while($row = mysqli_fetch_assoc($result)) {
    ?>

    <div class="news-card">

        

        <div class="news-content">

            <span class="tag <?php echo $row['category']; ?>">
                <?php echo strtoupper($row['category']); ?>
            </span>

            <h3><?php echo $row['title']; ?></h3>

            <p><?php echo substr($row['description'],0,60); ?>...</p>

            <div class="news-footer">
                <span><?php echo date("d M Y", strtotime($row['created_at'])); ?></span>

                <a href="news_details.php?id=<?php echo $row['id']; ?>">
                    Read ➝
                </a>
            </div>

        </div>

    </div>

    <?php } ?>

    </div>

    <!-- RIGHT ARROW -->
    <div class="arrow right" onclick="scrollNews(1)">⟩</div>

</div>

</section>
<!-- FOOTER -->
<footer class="main-footer">

<div class="footer-container">

<div class="footer-column">
<h3>SITE MAP</h3>
<ul>
<li><a href="#">About</a></li>
<li><a href="army.php">Army</a></li>
<li><a href="naval.php">Navy</a></li>
<li><a href="air.php">Airforce</a></li>
<li><a href="Feedback.php">Feedback</a></li>
</ul>
</div>

<div class="footer-column">
<h3>QUICK LINKS</h3>
<ul>
<li><a href="my_training.php">my training</a></li>
<li><a href="my_progress.php">my progress</a></li>
<li><a href="my_downloads.php">Downloads</a></li>
</ul>
</div>

<div class="footer-logo">
<img src="images/ncc logo.png">
</div>

<div class="footer-column">
<h3>CONTACT</h3>
<p>📞 +91 99325 5900</p>
<p>✉ nccstudyhub@.com</p>
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
<!-- PROFILE DROPDOWN SCRIPT -->
<script>
function toggleProfile(){
    let dropdown = document.getElementById("profileDropdown");
    dropdown.classList.toggle("show");
}

window.onclick = function(e){
    if(!e.target.closest('.profile-menu')){
        let d = document.getElementById("profileDropdown");
        if(d) d.classList.remove("show");
    }
}
</script>
<script>
function scrollNews(direction){
    const slider = document.getElementById("newsSlider");
    const cardWidth = 280; // width + gap

    slider.scrollBy({
        left: direction * cardWidth,
        behavior: "smooth"
    });
}
</script>


</body>
</html>




