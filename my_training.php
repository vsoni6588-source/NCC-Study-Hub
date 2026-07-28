<?php
session_start();
include("includes/config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ==========================
// USER DATA
// ==========================
$userData = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT * FROM users WHERE id='$user_id'"
));

// CLEAN WING
$userWing = strtolower(trim($userData['wing']));
$userWing = str_replace(' ', '', $userWing);

$wingMap = [
    "army" => 1,
    "navy" => 2,
    "airforce" => 3
];

$wing_id = $wingMap[$userWing] ?? 1;

// ==========================
// CURRENT WING SUBJECTS
// ==========================
$query = "
SELECT s.*,
COALESCE(up.status,'not_started') as status
FROM subjects s
LEFT JOIN user_progress up
ON s.id = up.subject_id
AND up.user_id='$user_id'
WHERE s.wing_id='$wing_id'
ORDER BY s.id DESC
";

$result = mysqli_query($conn,$query);

$subjects = [];

$total = 0;
$completed = 0;
$inprogress = 0;
$notstarted = 0;

while($row = mysqli_fetch_assoc($result)){

    $total++;

    if($row['status']=="completed"){
        $completed++;
    }
    elseif($row['status']=="in_progress"){
        $inprogress++;
    }
    else{
        $notstarted++;
    }

    $subjects[] = $row;
}

// ==========================
// MAIN PROGRESS
// ==========================
$progress = ($total > 0)
? round(($completed/$total)*100)
: 0;

// ==========================
// CONTINUE LEARNING
// ==========================
$continue = null;

foreach($subjects as $s){
    if($s['status']=="in_progress"){
        $continue = $s;
        break;
    }
}

// ==========================
// OTHER WINGS
// ==========================
$other_wings = mysqli_query($conn,"
SELECT * FROM wings
WHERE id != '$wing_id'
");

// ==========================
// CURRENT WING NAME
// ==========================
$currentWingName = ucfirst($userWing);

// ==========================
// COLORS
// ==========================
$color = "#22c55e";

if($wing_id == 2){
    $color = "#0ea5e9";
}

if($wing_id == 3){
    $color = "#2563eb";
}

// =====================================
// PRACTICAL KNOWLEDGE PROGRESS
// ONLY TRAINING + DRILL&GOH
// =====================================

// TOTAL PRACTICAL SUBJECTS
$practical_total = mysqli_fetch_assoc(mysqli_query($conn,
"
SELECT COUNT(*) as total
FROM subjects
WHERE
type='training'
OR type='drill&goh'
"
))['total'];


// =====================================
// PRACTICAL KNOWLEDGE PROGRESS
// =====================================

// TOTAL SUBJECTS
$practical_total = mysqli_fetch_assoc(mysqli_query($conn,
"
SELECT COUNT(*) as total
FROM subjects
WHERE
type='leadership'
OR type='training'
OR type='drill&goh'
"
))['total'];


// COMPLETED SUBJECTS
$practical_completed = mysqli_fetch_assoc(mysqli_query($conn,
"
SELECT COUNT(*) as done
FROM user_progress up
JOIN subjects s ON up.subject_id = s.id

WHERE up.user_id='$user_id'
AND up.status='completed'

AND
(
    s.type='leadership'
    OR s.type='training'
    OR s.type='drill&goh'
)
"
))['done'];


// IN PROGRESS SUBJECTS
$practical_inprogress = mysqli_fetch_assoc(mysqli_query($conn,
"
SELECT COUNT(*) as done
FROM user_progress up
JOIN subjects s ON up.subject_id = s.id

WHERE up.user_id='$user_id'
AND up.status='in_progress'

AND
(
    s.type='leadership'
    OR s.type='training'
    OR s.type='drill&goh'
)
"
))['done'];


// NOT STARTED
$practical_notstarted =
$practical_total -
($practical_completed + $practical_inprogress);


// FINAL PROGRESS %
$practical_progress =
($practical_total > 0)
? round(($practical_completed / $practical_total) * 100)
: 0;
?>

<!DOCTYPE html>
<html>
<head>

<title>My Training</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<link rel="stylesheet" href="style.css">

<style>

body{
    margin:0;
    background:#0f172a;
    color:white;
    font-family:'Segoe UI',sans-serif;
}

/* MAIN */
.training-container{
    width:92%;
    max-width:1300px;
    margin:120px auto 40px;
}

/* HERO */
.training-hero{
    background:linear-gradient(135deg,#1e293b,#0f172a);
    border-radius:20px;
    padding:40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:30px;
    margin-bottom:25px;
    border:1px solid rgba(255,255,255,0.08);
}

.hero-left h1{
    font-size:40px;
    margin-bottom:10px;
}

.hero-left p{
    color:#cbd5e1;
    line-height:1.7;
    max-width:700px;
}

.hero-badge{
    display:inline-block;
    background:<?php echo $color; ?>;
    padding:8px 18px;
    border-radius:30px;
    margin-bottom:18px;
    font-size:14px;
    font-weight:600;
}

.hero-progress{
    width:260px;
    background:#111827;
    padding:25px;
    border-radius:18px;
    text-align:center;
}

.circle{
    width:140px;
    height:140px;
    border-radius:50%;
    border:10px solid <?php echo $color; ?>;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:auto;
    font-size:32px;
    font-weight:bold;
}

.hero-progress h3{
    margin-top:20px;
}

.hero-progress p{
    color:#94a3b8;
}

/* QUICK STATS */
.stats-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-bottom:25px;
}

.stat-card{
    background:#1e293b;
    padding:25px;
    border-radius:16px;
    border:1px solid rgba(255,255,255,0.05);
    transition:0.3s;
}

.stat-card:hover{
    transform:translateY(-5px);
}

.stat-card i{
    font-size:26px;
    margin-bottom:15px;
    color:<?php echo $color; ?>;
}

.stat-card h2{
    font-size:30px;
    margin-bottom:5px;
}

.stat-card p{
    color:#94a3b8;
}

/* CONTINUE */
.continue-box{
    background:linear-gradient(135deg,#1e293b,#172033);
    border-radius:18px;
    padding:25px;
    margin-bottom:25px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:20px;
}

.continue-info h2{
    margin-bottom:10px;
}

.continue-info p{
    color:#cbd5e1;
}

.resume-btn{
    background:<?php echo $color; ?>;
    color:white;
    padding:12px 22px;
    border-radius:10px;
    text-decoration:none;
    font-weight:600;
}

/* SECTION */
.section-title{
    margin:35px 0 20px;
    font-size:28px;
    color:black;
}

/* FILTERS */
.filters{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin-bottom:20px;
}

.filter-btn{
    border:none;
    padding:10px 18px;
    border-radius:30px;
    background:#1e293b;
    color:white;
    cursor:pointer;
    transition:0.3s;
}

.filter-btn.active{
    background:<?php echo $color; ?>;
}

/* SUBJECT GRID */
.subject-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
    gap:20px;
}

/* SUBJECT CARD */
.subject-card{
    background:#1e293b;
    border-radius:18px;
    padding:22px;
    border:1px solid rgba(255,255,255,0.06);
    transition:0.3s;
}

.subject-card:hover{
    transform:translateY(-6px);
}

.subject-top{
    display:flex;
    justify-content:space-between;
    margin-bottom:15px;
}

.subject-icon{
    width:55px;
    height:55px;
    background:rgba(255,255,255,0.08);
    border-radius:12px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:22px;
    color:<?php echo $color; ?>;
}

.status{
    padding:6px 14px;
    border-radius:20px;
    font-size:12px;
    height:fit-content;
}

.completed{
    background:#14532d;
    color:#22c55e;
}

.in_progress{
    background:#78350f;
    color:#facc15;
}

.not_started{
    background:#1e293b;
    border:1px solid #475569;
    color:#cbd5e1;
}

.subject-card h3{
    margin-bottom:10px;
}

.subject-card p{
    color:#94a3b8;
    line-height:1.6;
    font-size:14px;
}

.card-btn{
    display:inline-block;
    margin-top:18px;
    padding:10px 18px;
    background:<?php echo $color; ?>;
    color:white;
    border-radius:10px;
    text-decoration:none;
}

/* OTHER WINGS */
.other-wings{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
}

.wing-box{
    background:#1e293b;
    padding:25px;
    border-radius:18px;
    border:1px solid rgba(255,255,255,0.05);
}

.wing-box h3{
    margin-bottom:10px;
}

.wing-box p{
    color:#94a3b8;
    line-height:1.6;
    margin-bottom:15px;
}

.wing-btn{
    display:inline-block;
    text-decoration:none;
    color:white;
    background:#334155;
    padding:10px 16px;
    border-radius:8px;
}

/* MOTIVATION */
.motivation{
    margin-top:35px;
    background:linear-gradient(135deg,#1e293b,#0f172a);
    border-radius:18px;
    padding:30px;
    text-align:center;
}

.motivation h2{
    margin-bottom:10px;
}

.motivation p{
    color:#cbd5e1;
}

/* RESPONSIVE */
@media(max-width:900px){

    .training-hero{
        flex-direction:column;
        align-items:flex-start;
    }

    .hero-progress{
        width:100%;
    }

    .hero-left h1{
        font-size:30px;
    }
}

@media(max-width:600px){

    .training-container{
        width:95%;
    }

    .hero-left h1{
        font-size:26px;
    }

    .section-title{
        font-size:24px;
    }
}

</style>
</head>

<body>

<?php include("includes/navbar.php"); ?>

<div class="training-container">

<!-- HERO -->
<div class="training-hero">

<div class="hero-left">

<div class="hero-badge">
🪖 <?php echo $currentWingName; ?> Wing Training
</div>

<h1>Track Your NCC Learning Journey</h1>

<p>
Access structured NCC learning modules, monitor your progress,
continue unfinished training, and explore other NCC wings to
expand your military knowledge and leadership skills.
</p>

</div>

<div class="hero-progress">

<div class="circle">
<?php echo $progress; ?>%
</div>

<h3>Overall Progress</h3>

<p><?php echo $completed; ?> of <?php echo $total; ?> Subjects Completed</p>

</div>

</div>

<!-- QUICK STATS -->
<div class="stats-grid">

<div class="stat-card">
<i class="fa-solid fa-book-open"></i>
<h2><?php echo $total; ?></h2>
<p>Total Subjects</p>
</div>

<div class="stat-card">
<i class="fa-solid fa-circle-check"></i>
<h2><?php echo $completed; ?></h2>
<p>Completed</p>
</div>

<div class="stat-card">
<i class="fa-solid fa-spinner"></i>
<h2><?php echo $inprogress; ?></h2>
<p>In Progress</p>
</div>

<div class="stat-card">
<i class="fa-solid fa-lock"></i>
<h2><?php echo $notstarted; ?></h2>
<p>Not Started</p>
</div>

</div>

<!-- CONTINUE LEARNING -->
<?php if($continue){ ?>

<div class="continue-box">

<div class="continue-info">
<h2>🚀 Continue Your Training</h2>

<p>
Resume your current module:
<strong><?php echo $continue['title']; ?></strong>
</p>
</div>

<a href="view.php?id=<?php echo $continue['id']; ?>"
class="resume-btn">
Continue Learning
</a>

</div>

<?php } ?>

<!-- SUBJECTS -->
<h2 class="section-title">📊 Your Training Modules</h2>

<div class="filters">
<button class="filter-btn active"
onclick="filterCards('all',event)">
All
</button>

<button class="filter-btn"
onclick="filterCards('completed',event)">
Completed
</button>

<button class="filter-btn"
onclick="filterCards('in_progress',event)">
In Progress
</button>

<button class="filter-btn"
onclick="filterCards('not_started',event)">
Not Started
</button>
</div>

<div class="subject-grid">

<?php foreach($subjects as $s){ ?>

<div class="subject-card"
data-status="<?php echo $s['status']; ?>">

<div class="subject-top">

<div class="subject-icon">
<i class="fa-solid fa-book"></i>
</div>

<div class="status <?php echo $s['status']; ?>">
<?php echo ucfirst(str_replace("_"," ",$s['status'])); ?>
</div>

</div>

<h3><?php echo $s['title']; ?></h3>

<p>
Build leadership, discipline, tactical knowledge,
and practical NCC understanding through this module.
</p>

<?php if($s['status']=="completed"){ ?>

<a href="view.php?id=<?php echo $s['id']; ?>"
class="card-btn">
Review
</a>

<?php } elseif($s['status']=="in_progress"){ ?>

<a href="view.php?id=<?php echo $s['id']; ?>"
class="card-btn">
Continue
</a>

<?php } else { ?>

<a href="view.php?id=<?php echo $s['id']; ?>"
class="card-btn">
Start Learning
</a>

<?php } ?>

</div>

<?php } ?>

</div>



<!-- ================================= -->
<!-- PRACTICAL KNOWLEDGE SECTION -->
<!-- ================================= -->

<div class="training-hero"
style="margin-top:30px;">

<div class="hero-left">

<div class="hero-badge">
✅ Practical Knowledge Training
</div>

<h1>Track Practical NCC Activities</h1>

<p>
Monitor your drill practice, weapon handling,
field training, physical exercises, parade preparation,
and practical NCC activities progress.
</p>

</div>

<div class="hero-progress">

<div class="circle">
<?php echo $practical_progress; ?>%
</div>

<h3>Practical Progress</h3>

<p>
<?php echo $practical_completed; ?>
of
<?php echo $practical_total; ?>
Practical Subjects Completed
</p>

</div>

</div>

<!-- PRACTICAL STATS -->
<div class="stats-grid">

<div class="stat-card">
<i class="fa-solid fa-dumbbell"></i>
<h2><?php echo $practical_total; ?></h2>
<p>Total Practical Subjects</p>
</div>

<div class="stat-card">
<i class="fa-solid fa-circle-check"></i>
<h2><?php echo $practical_completed; ?></h2>
<p>Completed</p>
</div>

<div class="stat-card">
<i class="fa-solid fa-spinner"></i>
<h2><?php echo $practical_inprogress; ?></h2>
<p>In Progress</p>
</div>

<div class="stat-card">
<i class="fa-solid fa-lock"></i>
<h2><?php echo $practical_notstarted; ?></h2>
<p>Not Started</p>
</div>

</div>



<!-- MOTIVATION -->
<div class="motivation">

<h2>✅ Stay Consistent Cadet!</h2>

<p>
Every completed lesson improves your discipline,
leadership, confidence, and NCC knowledge.
Keep training regularly to unlock your full cadet potential.
</p>

</div>

</div>

<script>

function filterCards(type,event){

    let cards = document.querySelectorAll(".subject-card");
    let buttons = document.querySelectorAll(".filter-btn");

    buttons.forEach(btn=>{
        btn.classList.remove("active");
    });

    event.target.classList.add("active");

    cards.forEach(card=>{

        if(type=="all" || card.dataset.status==type){
            card.style.display="block";
        }
        else{
            card.style.display="none";
        }

    });

}

</script>

</body>
</html>
