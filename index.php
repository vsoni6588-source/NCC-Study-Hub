
<?php 
session_start(); 
include("includes/config.php"); // ✅ THIS LINE IS MISSING
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NCC Study Hub</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/style.css">

<style>

/* RESET */
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{background:#f5f7fa;color:#222;line-height:1.6;}
.container{width:90%;max-width:1200px;margin:auto;}

/* HERO */
.hero{
height:100vh;
display:flex;
align-items:center;
color:#fff;
background-image:
linear-gradient(to right,
rgba(6,25,45,0.95) 35%,
rgba(6,25,45,0.75) 50%,
rgba(6,25,45,0.0) 70%
),
url("images/hncc.jpeg");

background-size:cover;
background-position:center;
}

.hero h1{font-size:52px;}
.hero span{color:#f4c430;}
.hero p{margin:20px 0;color:#ddd;max-width:500px;}

.btn{
padding:12px 25px;
border-radius:6px;
font-weight:600;
cursor:pointer;
transition:.3s;
}

.primary{background:#f4c430;border:none;}
.primary:hover{background:#d4af37;}
.secondary{
border:1px solid #fff;
background:transparent;
color:#fff;
margin-left:10px;
}

/* SECTION */
.section{padding:10px 0;}
.section h2{
text-align:center;
margin-bottom:40px;
color:#0a1d3b;
}

/* CARDS */
.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(230px,1fr));
gap:25px;
}

.card{
background:#fff;
padding:25px;
border-radius:12px;
text-align:center;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
transition:.3s;
}

.card:hover{transform:translateY(-6px);}
.card i{font-size:30px;color:#f4c430;margin-bottom:10px;}

.card p{
font-size:14px;
color:#555;
margin-top:10px;
}

/* DARK */
.dark{
background:#0a1d3b;
color:#fff;
}
.dark h2{color:#fff;}
.dark .card{
background:#132544;
color:#fff;
}
.dark .card p{color:#cbd5e1;}

/* WINGS */
.wings{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
gap:20px;
}

.wing{
padding:30px;
border-radius:12px;
color:#fff;
}

.army{background:#6b7d2f;}
.navy{background:#0a3d62;}
.air{background:#0f766e;}

.wing p{margin:10px 0;font-size:14px;}
.wing a{color:#f4c430;text-decoration:none;}

/* STEPS */
.steps{
display:flex;
justify-content:space-between;
flex-wrap:wrap;
gap:20px;
text-align:center;
}

.step{
flex:1;
min-width:150px;
}

.step i{
font-size:28px;
margin-bottom:10px;
color:#f4c430;
}

.step p{
font-size:13px;
color:#555;
}

/* STATS */
.stats{
background:#0a1d3b;
color:#fff;
display:flex;
justify-content:space-around;
padding:60px 0;
text-align:center;
}

.stats h2{color:#f4c430;}

.testimonials{
display:flex;
gap:20px;
overflow-x:auto;
padding:20px 0;
}

.testimonial{
min-width:300px;
background:#fff;
padding:25px;
border-radius:12px;
box-shadow:0 10px 25px rgba(0,0,0,0.1);
transition:0.3s;
}

.testimonial:hover{
transform:translateY(-5px);
}

.testimonial h4{
color:#0a1d3b;
}

.testimonial-slider{
display:flex;
gap:20px;
overflow-x:auto;
padding:10px;
}

.testimonial-card{
min-width:300px;
background:#fff;
padding:20px;
border-radius:12px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.stars{
color:#f4c430;
margin-bottom:10px;
}

.msg{
font-size:14px;
color:#555;
margin-bottom:15px;
}

.user{
display:flex;
align-items:center;
gap:10px;
}

.avatar{
width:40px;
height:40px;
border-radius:50%;
background:#0a1d3b;
color:#fff;
display:flex;
align-items:center;
justify-content:center;
font-weight:bold;
}

.user h4{
margin:0;
font-size:14px;
}

.user small{
color:#777;
}

.cta{
    background: linear-gradient(135deg,#0a1d3b,#123a6b);
    color:#fff;
    text-align:center;
    padding:60px 20px;
    
    border-radius:20px;          /* ✅ Curved edges */
    width:90%;
    max-width:1200px;
    margin:50px auto;            /* ✅ Center + spacing */
    
    box-shadow:0 10px 30px rgba(0,0,0,0.2);
}
.cta .btn{
    background:#f4c430;
    color:#000;
    border:none;
    padding:12px 30px;
    border-radius:8px;
    font-weight:600;
    margin-top:15px;
    cursor:pointer;
    text-decoration: none;
}

.cta .btn:hover{
    background:#d4af37;
}

/* FOOTER */
footer{
background:#09152a;
color:#ccc;
padding:40px;
}

.footer-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
gap:20px;
}

/* TIMELINE */
.timeline{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    position:relative;
    margin-top:50px;
}

/* DOTTED LINE */
.timeline::before{
    content:"";
    position:absolute;
    top:40px;
    left:5%;
    width:90%;
    border-top:2px dashed #ccc;
    z-index:0;
}

/* ITEM */
.timeline-item{
    text-align:center;
    width:18%;
    position:relative;
    z-index:1;
}

/* CIRCLE */
.circle{
    width:70px;
    height:70px;
    margin:auto;
    background:#fff;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    position:relative;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

/* ICON */
.circle i{
    font-size:22px;
    color:#0a1d3b;
}

/* NUMBER DOT */
.circle span{
    position:absolute;
    top:-8px;
    right:-8px;
    background:#f4c430;
    color:#000;
    width:25px;
    height:25px;
    font-size:12px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:bold;
}

/* TEXT */
.timeline-item h4{
    margin-top:15px;
    font-size:16px;
}

.timeline-item p{
    font-size:13px;
    color:#555;
    margin-top:5px;
}

/* RESPONSIVE */
@media(max-width:768px){

.timeline{
    flex-direction:column;
    align-items:center;
}

.timeline::before{
    display:none;
}

.timeline-item{
    width:100%;
    margin-bottom:30px;
}

}

.section-intro{
    max-width: 750px;
    margin: 0 auto 50px;
    text-align: center;
}

.section-intro p{
    font-size: 20px;
    color: #555;
    line-height: 1.7;
    margin-bottom: 15px;
}

/* DARK SECTION SUPPORT */
.dark .section-intro p{
    color: #cbd5e1;
}

</style>
<!-- NAVBAR -->
 <link rel="stylesheet" href="assets/css/navbar.css">
<header class="navbar">

<div class="logo">
<img src="images/ncc logo.png">
<div>
<h2>NCC Study Hub</h2>
<p>Discipline | Leadership | Patriotism</p>
</div>
</div>

<nav>
<ul>
<li><a href="#">Home</a></li>
<li><a href="#">About Us</a></li>
<li><a href="#">Wings</a></li>
<li><a href="#">Army</a></li>
<li><a href="#">Navy</a></li>
<li><a href="#">Air</a></li>
<li><a href="#">Courses</a></li>
<li><a href="#">Resources</a></li>
<li><a href="#">Training</a></li>
</ul>
</nav>

<div class="auth-btn">
<a href="login.php" class="login">Login</a>
<a href="register.php" class="signup">Sign Up</a>
</div>

</header>
</head>

<body>

<div id="page-loader">
    <div class="loader"></div>
</div>


<!-- HERO SECTION -->
<section class="hero">

<div class="hero-text">

<h4>EMPOWERING FUTURE LEADERS</h4>


<h1>
THE DIGITAL LEARNING HUB FOR <br>
<h2>THE NATIONAL CADET CORPS</h2>
</h1>

<p>
Excellence in Military Training, Leadership Development, and Academic Discipline for all NCC Cadets.
</p>

<div class="hero-btn">
<a href="login.php" class="btn-primary"><b>JOIN NOW ➝</b></a>

</div>

</div>

</section><br>


<!-- ABOUT -->
<section class="section container">
<h2>WHAT IS NCC STUDY HUB ?</h2>
<div class="section-intro">
    <p>
        NCC Study Hub is a digital learning platform created to support NCC cadets in their academic and training journey. 
        It provides structured study materials, practice tests, and useful resources that help cadets prepare for exams, 
        improve their knowledge, and build discipline.
    </p>

    <p>
        The platform also focuses on developing leadership qualities and confidence, helping cadets grow into responsible 
        and capable individuals.
    </p>
</div>

<div class="cards">
<div class="card">
<i class="fas fa-book"></i>
<h3>Learn</h3>
<p>Access structured study materials and training modules.</p>
</div>

<div class="card">
<i class="fas fa-clipboard"></i>
<h3>Prepare</h3>
<p>Prepare for NCC exams, tests and certifications.</p>
</div>

<div class="card">
<i class="fas fa-campground"></i>
<h3>Participate</h3>
<p>Stay updated about camps and activities.</p>
</div>

<div class="card">
<i class="fas fa-user"></i>
<h3>Lead</h3>
<p>Develop leadership skills and confidence.</p>
</div>
</div>
</section><br>

<!-- WHY -->
<section class="section dark">
<div class="container">
<h2>WHY CHOOSE NCC STUDY HUB ?</h2>

<div class="cards">
<div class="card">
<i class="fas fa-graduation-cap"></i>
<h3>NCC Content</h3>
<p>Aligned with NCC syllabus and training requirements.</p>
</div>

<div class="card">
<i class="fas fa-shield"></i>
<h3>Guidance</h3>
<p>Learn from experienced cadets and mentors.</p>
</div>

<div class="card">
<i class="fas fa-clock"></i>
<h3>Anytime</h3>
<p>Access content anytime, anywhere easily.</p>
</div>

<div class="card">
<i class="fas fa-trophy"></i>
<h3>Future</h3>
<p>Build skills and become future leaders.</p>
</div>
</div>
</div><br>
</section><br>

<!-- WINGS -->
<section class="section container">
<h2>Explore Our Wings</h2><br>

<div class="wings">
<div class="wing army">
<h3>Army Wing</h3>
<p>Military training & discipline for cadets.</p>
<a href="#">Explore â†’</a>
</div>

<div class="wing navy">
<h3>Naval Wing</h3>
<p>Maritime training and naval discipline.</p>
<a href="#">Explore â†’</a>
</div>

<div class="wing air">
<h3>Air Wing</h3>
<p>Aviation and air force training.</p>
<a href="#">Explore â†’</a>
</div>
</div>
</section>

<!-- HOW IT WORKS -->
<section class="section container">
<h2>How It Works</h2>

<div class="timeline">

<div class="timeline-item">
    <div class="circle">
        <i class="fas fa-user"></i>
        <span>1</span>
    </div>
    <h4>Sign Up</h4>
    <p>Create your account in few seconds.</p>
</div>

<div class="timeline-item">
    <div class="circle">
        <i class="fas fa-search"></i>
        <span>2</span>
    </div>
    <h4>Choose Wing</h4>
    <p>Select your wing (Army, Navy or Air).</p>
</div>

<div class="timeline-item">
    <div class="circle">
        <i class="fas fa-book"></i>
        <span>3</span>
    </div>
    <h4>Start Learning</h4>
    <p>Access courses, notes and materials.</p>
</div>

<div class="timeline-item">
    <div class="circle">
        <i class="fas fa-file"></i>
        <span>4</span>
    </div>
    <h4>Practice & Test</h4>
    <p>Attempt tests and improve.</p>
</div>

<div class="timeline-item">
    <div class="circle">
        <i class="fas fa-trophy"></i>
        <span>5</span>
    </div>
    <h4>Achieve</h4>
    <p>Earn certificates and become leader.</p>
</div>

</div>
</section><br>

<!-- STATS -->
<section class="stats">
<div><h2>10,000+</h2><p>Cadets</p></div>
<div><h2>500+</h2><p>Resources</p></div>
<div><h2>200+</h2><p>Camps</p></div>
<div><h2>95%</h2><p>Success</p></div>
</section><br>

<!-- TESTIMONIAL -->
<section class="section container">
<h2>WHAT OUR CADETS SAY</h2>

<div class="testimonial-slider">

<?php
$feedbacks = mysqli_query($conn,
"SELECT * FROM feedback ORDER BY id DESC LIMIT 10");

if(mysqli_num_rows($feedbacks) > 0){

while($row = mysqli_fetch_assoc($feedbacks)) {
?>

<div class="testimonial-card">

<div class="stars">
<?php 
for($i=1;$i<=5;$i++){
    echo ($i <= $row['rating']) ? "★" : "☆";
}
?>
</div>

<p class="msg"><?php echo $row['message']; ?></p>

<div class="user">
    <div class="avatar">
        <?php echo strtoupper(substr($row['name'],0,1)); ?>
    </div>

    <div>
        <h4><?php echo $row['name']; ?></h4>
        <small>NCC Cadet</small>
    </div>
</div>

</div>

<?php 
} 
} else {
?>
<p style="text-align:center;">No feedback available</p>
<?php } ?>

</div>
</section><br>
<!-- CTA -->
<section class="cta">
<h2>STRAT YOUR NCC JOURNEY TODAY</h2><br>
<p><centre>Join thousand of cadets learning, training and growing together.</centre></p><br>
<a href="login.php" class="btn primary">Join Now ➝</a>
</section><br>

<!-- FOOTER -->
<footer class="main-footer">

<div class="footer-container">

<div class="footer-column">
<h3>SITE MAP</h3>
<ul>
<li><a href="#">About</a></li>
<li><a href="login.php">Army</a></li>
<li><a href="login.php">Navy</a></li>
<li><a href="login.php">Airforce</a></li>
<li><a href="Feedback.php">Feedback</a></li>
</ul>
</div>

<div class="footer-column">
<h3>QUICK LINKS</h3>
<ul>
<li><a href="login.php">my training</a></li>
<li><a href="login.php">my progress</a></li>
<li><a href="login.php">Downloads</a></li>
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


<script>
window.addEventListener("load", function () {
    document.getElementById("page-loader").classList.add("hide");

    setTimeout(function () {
        document.getElementById("page-loader").remove();
    }, 400);
});
</script>

</body>
</html>
