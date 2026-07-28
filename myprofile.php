<?php
session_start();
include("includes/config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// USER DATA
$userData = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM users WHERE id='$user_id'"
));

// UPDATE PROFILE
if(isset($_POST['update_profile'])){

    $name = mysqli_real_escape_string($conn, $_POST['fullname']);
    $rank = mysqli_real_escape_string($conn, $_POST['rank']);
    $wing = mysqli_real_escape_string($conn, $_POST['wing']);

    mysqli_query($conn, "
        UPDATE users 
        SET fullname='$name', rank='$rank', wing='$wing'
        WHERE id='$user_id'
    ");

    $_SESSION['user_name'] = $name;

    header("Location: myprofile.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>My Profile</title>

<style>
body{
    margin:0;
    font-family:'Segoe UI', sans-serif;
    background:#0f172a;
    color:white;
}

/* CENTER CONTAINER */
.container{
    width:90%;
    max-width:500px;
    margin:120px auto;
    padding-top:150px;
    
}

/* CARD */
.card{
    background:#1e293b;
    padding:25px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,0.3);
}

/* TITLE */
.card h2{
    text-align:center;
    margin-bottom:20px;
}

/* LABEL */
label{
    font-size:13px;
    color:#94a3b8;
}

/* INPUTS */
input, select{
    width:100%;
    padding:10px;
    margin-top:5px;
    margin-bottom:15px;
    border:none;
    border-radius:8px;
    background:#0f172a;
    color:white;
    outline:none;
}

/* EMAIL BOX (readonly style) */
.email-box{
    padding:10px;
    background:#0f172a;
    border-radius:8px;
    margin-top:5px;
    margin-bottom:15px;
    color:#cbd5e1;
}

/* BUTTON */
button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    background:#22c55e;
    color:white;
    font-size:15px;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:#16a34a;
}

/* BADGE */
.badge{
    text-align:center;
    margin-bottom:15px;
    font-size:12px;
    color:#94a3b8;
}
</style>
</head>

<body>

<?php include("includes/navbar.php"); ?>

<div class="container">

<div class="card">

    <h2>🪖 Cadet Profile</h2>

    <div class="badge">
        Manage your personal information
    </div>

    <form method="POST">

        <!-- NAME -->
        <label>Full Name</label>
        <input type="text" name="fullname"
               value="<?php echo $userData['fullname']; ?>" required>

        <!-- EMAIL (NOT EDITABLE) -->
        <label>Email</label>
        <div class="email-box">
            <?php echo $userData['email']; ?>
        </div>

        <!-- RANK -->
        <label>Rank</label>
        <select name="rank">
            <option <?php if($userData['rank']=="Cadet") echo "selected"; ?>>Cadet</option>
            <option <?php if($userData['rank']=="Lance Corporal") echo "selected"; ?>>Lance Corporal</option>
            <option <?php if($userData['rank']=="Corporal") echo "selected"; ?>>Corporal</option>
            <option <?php if($userData['rank']=="Sergeant") echo "selected"; ?>>Sergeant</option>
        </select>

        <!-- WING -->
        <label>Wing</label>
        <select name="wing">
            <option value="army" <?php if($userData['wing']=="army") echo "selected"; ?>>Army</option>
            <option value="navy" <?php if($userData['wing']=="navy") echo "selected"; ?>>Navy</option>
            <option value="airforce" <?php if($userData['wing']=="airforce") echo "selected"; ?>>Air Force</option>
        </select>

        <!-- SAVE -->
        <button type="submit" name="update_profile">
            💾 Save Profile
        </button>

    </form>

</div>

</div>

</body>
</html>
