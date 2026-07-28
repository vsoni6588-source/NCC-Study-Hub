<?php
session_start();
include("includes/config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// USER INFO
$userData = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT * FROM users WHERE id='$user_id'"
));

/* =========================================
   FUNCTION FOR CATEGORY PROGRESS
========================================= */

function getProgressData($conn, $user_id, $type){

    // CONDITION
    if($type == "army"){

        $condition = "q.wing_id='1'";

    }
    elseif($type == "navy"){

        $condition = "q.wing_id='2'";

    }
    elseif($type == "airforce"){

        $condition = "q.wing_id='3'";

    }
    elseif($type == "leadership"){

        $condition = "q.wing_id='4'";

    }
    else{

        $condition = "1=0";

    }

    // TOTAL ATTEMPTS
    $attempts = mysqli_fetch_assoc(mysqli_query($conn,

    "SELECT COUNT(*) as total
     FROM user_answers ua
     JOIN questions q
     ON ua.question_id = q.id

     WHERE ua.user_id='$user_id'
     AND $condition"

    ))['total'];

    // CORRECT ANSWERS
    $correct = mysqli_fetch_assoc(mysqli_query($conn,

    "SELECT COUNT(*) as total
     FROM user_answers ua
     JOIN questions q
     ON ua.question_id = q.id

     WHERE ua.user_id='$user_id'
     AND ua.is_correct='1'
     AND $condition"

    ))['total'];

    // ACCURACY
    $accuracy = 0;

    if($attempts > 0){

        $accuracy = round(($correct / $attempts) * 100);

    }

    return [

        "attempts" => $attempts,
        "correct" => $correct,
        "accuracy" => $accuracy

    ];
}

/* =========================================
   GET DATA
========================================= */

$army = getProgressData($conn,$user_id,"army");
$navy = getProgressData($conn,$user_id,"navy");
$air = getProgressData($conn,$user_id,"airforce");
$leadership = getProgressData($conn,$user_id,"leadership");

/* =========================================
   OVERALL
========================================= */

$totalAttempts =
$army['attempts'] +
$navy['attempts'] +
$air['attempts'] +
$leadership['attempts'];

$totalCorrect =
$army['correct'] +
$navy['correct'] +
$air['correct'] +
$leadership['correct'];

$overallAccuracy =
($totalAttempts > 0)
? round(($totalCorrect/$totalAttempts)*100)
: 0;

?>

<!DOCTYPE html>
<html>
<head>

<title>My Progress Dashboard</title>

<link rel="stylesheet" href="assets/css/style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

body{
    margin:0;
    background:#0f172a;
    color:white;
    font-family:'Segoe UI',sans-serif;
}

/* MAIN */
.container{
    width:92%;
    max-width:1300px;
    margin:120px auto 40px;
}

/* HERO */
.hero{
    background:linear-gradient(135deg,#1e293b,#0f172a);
    border-radius:20px;
    padding:40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:30px;
    margin-bottom:30px;
    border:1px solid rgba(255,255,255,0.08);
}

.hero h1{
    margin:0;
    font-size:38px;
}

.hero p{
    color:#cbd5e1;
    margin-top:10px;
    line-height:1.7;
}

.overall-box{
    width:240px;
    background:#111827;
    padding:25px;
    border-radius:18px;
    text-align:center;
}

.circle{
    width:130px;
    height:130px;
    border-radius:50%;
    border:10px solid #22c55e;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:auto;
    font-size:30px;
    font-weight:bold;
}

/* GRID */
.progress-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:25px;
}

/* CARD */
.progress-card{
    background:#1e293b;
    border-radius:18px;
    padding:25px;
    border:1px solid rgba(255,255,255,0.06);
    transition:0.3s;
}

.progress-card:hover{
    transform:translateY(-5px);
}

.top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.icon{
    width:60px;
    height:60px;
    border-radius:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
}

.army{
    background:#14532d;
    color:#22c55e;
}

.navy{
    background:#082f49;
    color:#38bdf8;
}

.air{
    background:#1e3a8a;
    color:#60a5fa;
}

.leadership{
    background:#581c87;
    color:#d946ef;
}

.progress-card h2{
    margin:0;
    font-size:24px;
}

.progress-card p{
    color:#94a3b8;
    margin-top:5px;
}

/* BAR */
.bar{
    width:100%;
    height:12px;
    background:#334155;
    border-radius:20px;
    overflow:hidden;
    margin:18px 0;
}

.fill{
    height:100%;
    border-radius:20px;
}

.fill.army{
    background:#22c55e;
}

.fill.navy{
    background:#38bdf8;
}

.fill.air{
    background:#60a5fa;
}

.fill.leadership{
    background:#d946ef;
}

/* STATS */
.stats{
    display:flex;
    justify-content:space-between;
    margin-top:15px;
}

.stats div{
    text-align:center;
}

.stats h3{
    margin:0;
    font-size:22px;
}

.stats span{
    color:#94a3b8;
    font-size:14px;
}

/* RESPONSIVE */
@media(max-width:900px){

    .hero{
        flex-direction:column;
        align-items:flex-start;
    }

    .overall-box{
        width:100%;
    }

    .hero h1{
        font-size:30px;
    }
}

</style>
</head>

<body>

<?php include("includes/navbar.php"); ?>

<div class="container">

<!-- HERO -->
<div class="hero">

<div>

<h1>📊 My Progress Dashboard</h1>

<p>
Track your NCC MCQ performance across Army, Navy,
Air Force, and Leadership training modules.
Monitor your accuracy, attempts, and overall growth.
</p>

</div>

<div class="overall-box">

<div class="circle">
<?php echo $overallAccuracy; ?>%
</div>

<h3>Overall Accuracy</h3>

<p>
<?php echo $totalCorrect; ?>
Correct out of
<?php echo $totalAttempts; ?>
Attempts
</p>

</div>

</div>

<!-- GRID -->
<div class="progress-grid">

<!-- ARMY -->
<div class="progress-card">

<div class="top">

<div>
<h2>Army Wing</h2>
<p>Army MCQ Performance</p>
</div>

<div class="icon army">
<i class="fa-solid fa-shield"></i>
</div>

</div>

<div class="bar">
<div class="fill army"
style="width:<?php echo $army['accuracy']; ?>%">
</div>
</div>

<div class="stats">

<div>
<h3><?php echo $army['accuracy']; ?>%</h3>
<span>Accuracy</span>
</div>

<div>
<h3><?php echo $army['attempts']; ?></h3>
<span>Attempts</span>
</div>

<div>
<h3><?php echo $army['correct']; ?></h3>
<span>Correct</span>
</div>

</div>

</div>

<!-- NAVY -->
<div class="progress-card">

<div class="top">

<div>
<h2>Navy Wing</h2>
<p>Navy MCQ Performance</p>
</div>

<div class="icon navy">
<i class="fa-solid fa-anchor"></i>
</div>

</div>

<div class="bar">
<div class="fill navy"
style="width:<?php echo $navy['accuracy']; ?>%">
</div>
</div>

<div class="stats">

<div>
<h3><?php echo $navy['accuracy']; ?>%</h3>
<span>Accuracy</span>
</div>

<div>
<h3><?php echo $navy['attempts']; ?></h3>
<span>Attempts</span>
</div>

<div>
<h3><?php echo $navy['correct']; ?></h3>
<span>Correct</span>
</div>

</div>

</div>

<!-- AIR -->
<div class="progress-card">

<div class="top">

<div>
<h2>Air Force</h2>
<p>Air Wing MCQ Performance</p>
</div>

<div class="icon air">
<i class="fa-solid fa-plane"></i>
</div>

</div>

<div class="bar">
<div class="fill air"
style="width:<?php echo $air['accuracy']; ?>%">
</div>
</div>

<div class="stats">

<div>
<h3><?php echo $air['accuracy']; ?>%</h3>
<span>Accuracy</span>
</div>

<div>
<h3><?php echo $air['attempts']; ?></h3>
<span>Attempts</span>
</div>

<div>
<h3><?php echo $air['correct']; ?></h3>
<span>Correct</span>
</div>

</div>

</div>

<!-- LEADERSHIP -->
<div class="progress-card">

<div class="top">

<div>
<h2>Leadership</h2>
<p>Leadership MCQ Performance</p>
</div>

<div class="icon leadership">
<i class="fa-solid fa-user-tie"></i>
</div>

</div>

<div class="bar">
<div class="fill leadership"
style="width:<?php echo $leadership['accuracy']; ?>%">
</div>
</div>

<div class="stats">

<div>
<h3><?php echo $leadership['accuracy']; ?>%</h3>
<span>Accuracy</span>
</div>

<div>
<h3><?php echo $leadership['attempts']; ?></h3>
<span>Attempts</span>
</div>

<div>
<h3><?php echo $leadership['correct']; ?></h3>
<span>Correct</span>
</div>

</div>

</div>

</div>

</div>

</body>
</html>
