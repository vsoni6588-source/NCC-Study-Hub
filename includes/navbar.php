<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

include(__DIR__ . "/config.php");

$user_id = $_SESSION['user_id'] ?? 0;

// USER DATA
$userData = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT * FROM users WHERE id='$user_id'"
));

// PROGRESS (optional - you can reuse your logic)
// CLEAN WING NAME (VERY IMPORTANT)
$userWing = strtolower(trim($userData['wing'] ?? ''));
$userWing = str_replace(' ', '', $userWing);

// MAP WING â†’ ID (CORRECT)
$wingMap = [
    "army" => 1,
    "navy" => 2,
    "airforce" => 3
];

$wing_id = $wingMap[$userWing] ?? 1; // default army

// TOTAL SUBJECTS
$total = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as total 
     FROM subjects 
     WHERE wing_id='$wing_id'"
))['total'];

// COMPLETED SUBJECTS (STRICT FILTER)
$completed = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as done 
     FROM user_progress up
     JOIN subjects s ON up.subject_id = s.id
     WHERE up.user_id='$user_id' 
     AND up.status='completed'
     AND s.wing_id='$wing_id'"
))['done'];

// FINAL %
$progress = ($total > 0) ? round(($completed/$total)*100) : 0;


// ==============================
// 🪖 PRACTICAL PROGRESS
// ==============================

// TOTAL SUBJECTS
$practical_total = mysqli_fetch_assoc(mysqli_query($conn,

"SELECT COUNT(*) as total

FROM subjects

WHERE
type='leadership'
OR type='training'
OR type='drill&goh'"

))['total'];


// COMPLETED SUBJECTS
$practical_done = mysqli_fetch_assoc(mysqli_query($conn,

"SELECT COUNT(*) as done

FROM user_progress up

JOIN subjects s
ON up.subject_id = s.id

WHERE up.user_id='$user_id'
AND up.status='completed'

AND
(
    s.type='leadership'
    OR
    s.type='training'
    OR
    s.type='drill&goh'
)"

))['done'];


// FINAL %
$practical_progress = ($practical_total > 0)

? round(($practical_done / $practical_total) * 100)

: 0;
?>
<link rel="stylesheet" href="assets/css/navbar.css">
<header class="navbar">

    <div class="logo">
        <img src="images/ncc logo.png" alt="NCC Logo">
        <div>
            <h2>NCC Study Hub</h2>
            <p>Discipline | Leadership | Patriotism</p>
        </div>
    </div>

    <!-- Mobile Menu Button -->
    <div class="menu-toggle" onclick="toggleMenu()">
        <i class="fa fa-bars"></i>
    </div>

    <!-- Navigation -->
    <div class="nav-container" id="navContainer">

        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Wings</a></li>
                <li><a href="army.php">Army</a></li>
                <li><a href="naval.php">Navy</a></li>
                <li><a href="air.php">Air</a></li>
                <li><a href="#">Courses</a></li>
                <li><a href="#">Resources</a></li>
                <li><a href="#">Training</a></li>
            </ul>
        </nav>

        <div class="auth-btn">

        <?php if($user_id != 0){ ?>

            <div class="profile-menu">

                <div class="profile-btn" onclick="toggleProfile()">
                    <i class="fa fa-user-circle"></i>
                    <span><?php echo $_SESSION['user_name']; ?></span>
                </div>

                <div class="profile-dropdown" id="profileDropdown">

                    <div class="profile-header">
                        <i class="fa fa-user-circle big-icon"></i>

                        <h3><?php echo $userData['fullname']; ?></h3>
                        <p><?php echo $userData['email']; ?></p>

                        <span class="cadet-badge">
                            <?php echo $userData['rank'] ?? 'Cadet'; ?>
                        </span>
                    </div>

                    <div class="profile-info">
                        <p>🎖 Rank: <?php echo $userData['rank'] ?? 'Cadet'; ?></p>
                        <p>🪖 Wing: <?php echo $userData['wing'] ?? 'Army'; ?></p>
                    </div>

                    <div class="progress-box">

                        <p>📚 Training Progress</p>

                        <div class="progress-bar">
                            <div class="progress-fill" style="width:<?php echo $progress; ?>%"></div>
                        </div>

                        <span><?php echo $progress; ?>% Completed</span>

                        <br><br>

                        <p>Practical Knowledge Progress</p>

                        <div class="progress-bar">
                            <div class="progress-fill" style="width:<?php echo $practical_progress; ?>%"></div>
                        </div>

                        <span><?php echo $practical_progress; ?>% Completed</span>

                    </div>

                    <hr>

                    <a href="myprofile.php">My Profile</a>
                    <a href="my_training.php">My Training</a>
                    <a href="my_progress.php">My Progress</a>
                    <a href="my_downloads.php">My Downloads</a>

                    <hr>

                    <a href="logout.php" class="logout">Logout</a>

                </div>

            </div>

        <?php } else { ?>

            <a href="login.php" class="login">Login</a>
            <a href="register.php" class="signup">Sign Up</a>

        <?php } ?>

        </div>

    </div>

</header>

<script>
function toggleMenu() {
    document.getElementById("navContainer").classList.toggle("active");
}
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
