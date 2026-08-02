<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Subjects</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

body {
    background: #f4f6fb;
}

.container {
    width: 90%;
    margin: 40px auto;
}

/* TITLE */
.title {
    font-size: 30px;
    margin-bottom: 30px;
    text-align: center;
    color: #333;
}

/* GRID SYSTEM */
.section-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

/* CARD */
.card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.08);
    transition: 0.3s;
    text-align: center;
}

.card:hover {
    transform: translateY(-6px);
}

/* ICON BOX */
.card-top {
    height: 100px;
    border-radius: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 15px;
}

.card-top img {
    width: 50px;
}

/* TEXT */
.card h2 {
    margin-bottom: 10px;
    color: #222;
}

.card p {
    font-size: 14px;
    color: #555;
    margin-bottom: 15px;
}

/* BUTTON */
.card a {
    text-decoration: none;
}

.card button {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    color: #fff;
    cursor: pointer;
}

/* COLORS */
.army .card-top { background: linear-gradient(135deg, #f7c948, #e0a800); }
.army button { background: linear-gradient(135deg, #f7c948, #e0a800); }

.navy .card-top { background: linear-gradient(135deg, #2b6cb0, #1e3a8a); }
.navy button { background: linear-gradient(135deg, #2b6cb0, #1e3a8a); }

.airforce .card-top { background: linear-gradient(135deg, #2ec4b6, #1b9aaa); }
.airforce button { background: linear-gradient(135deg, #2ec4b6, #1b9aaa); }

.training .card-top { background: linear-gradient(135deg, #6c5ce7, #4834d4); }
.training button { background: linear-gradient(135deg, #6c5ce7, #4834d4); }

.drill .card-top { background: linear-gradient(135deg, #00b894, #00cec9); }
.drill button { background: linear-gradient(135deg, #00b894, #00cec9); }

/* ✅ NEW LEADERSHIP */
.leadership .card-top { background: linear-gradient(135deg, #ff6a00, #ff3d00); }
.leadership button { background: linear-gradient(135deg, #ff6a00, #ff3d00); }

.section-title{
    font-size:22px;
    margin:20px 0;
    color:#333;
}

</style>
</head>

<body>

<div class="container">

<h1 class="title">Manage Subjects</h1>

<!-- ================= WINGS ================= -->
<div class="section-row">

<div class="card army">
    <div class="card-top">
        <img src="https://img.icons8.com/ios-filled/80/ffffff/soldier.png"/>
    </div>
    <h2>ARMY</h2>
    <p>Military training & discipline modules.</p>
    <a href="layout.php?page=armysubject&wing_id=1"><button>Manage</button></a>
</div>

<div class="card navy">
    <div class="card-top">
        <img src="https://img.icons8.com/ios-filled/80/ffffff/anchor.png"/>
    </div>
    <h2>NAVY</h2>
    <p>Maritime training & naval subjects.</p>
    <a href="layout.php?page=navysubject&wing_id=2"><button>Manage</button></a>
</div>

<div class="card airforce">
    <div class="card-top">
        <img src="https://img.icons8.com/ios-filled/80/ffffff/airplane-take-off.png"/>
    </div>
    <h2>AIR FORCE</h2>
    <p>Aviation and air wing training modules.</p>
    <a href="layout.php?page=airsubject&wing_id=3"><button>Manage</button></a>
</div>

</div>

<!-- ================= MODULES ================= -->
<h2 class="section-title">Training Modules</h2>

<div class="section-row">

<div class="card training">
    <div class="card-top">
        <img src="https://img.icons8.com/ios-filled/80/ffffff/training.png"/>
    </div>
    <h2>TRAINING</h2>
    <p>Weapons, fitness & theory modules.</p>
    <a href="layout.php?page=trainingsubject"><button>Manage</button></a>
</div>

<div class="card drill">
    <div class="card-top">
        <img src="https://img.icons8.com/ios-filled/80/ffffff/drill.png"/>
    </div>
    <h2>DRILL / GOH</h2>
    <p>Ceremonial & parade training.</p>
    <a href="layout.php?page=drill_gohsubject"><button>Manage</button></a>
</div>

<!-- ✅ NEW LEADERSHIP -->
<div class="card leadership">
    <div class="card-top">
        <img src="https://img.icons8.com/ios-filled/80/ffffff/leader.png"/>
    </div>
    <h2>LEADERSHIP</h2>
    <p>Leadership, discipline & personality development modules.</p>
    <a href="layout.php?page=leadershipsubject"><button>Manage</button></a>
</div>

</div>

</div>

</body>
</html>