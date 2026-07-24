<?php
session_start();
include("includes/config.php");

if(isset($_POST['register'])){

    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // NEW FIELDS
    $wing = mysqli_real_escape_string($conn, $_POST['wing']);
    $rank = mysqli_real_escape_string($conn, $_POST['rank']);

    // Check password match
    if($password !== $confirm_password){
        $error = "Passwords do not match!";
    } else {

        // Check email exists
        $check = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $check);

        if(mysqli_num_rows($result) > 0){
            $error = "Email already registered!";
        } else {

            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // INSERT WITH WING + RANK
            $sql = "INSERT INTO users (fullname, email, password, wing, rank) 
                    VALUES ('$fullname', '$email', '$hashed_password', '$wing', '$rank')";

            if(mysqli_query($conn, $sql)){
                $success = "Registration successful! You can login now.";
            } else {
                $error = "Something went wrong!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>NCC Study Hub - Register</title>
    <link rel="stylesheet" href="assets/css/lstyle.css">
</head>
<body>

<div class="container">

<div class="login-container">
    <div class="login-box">

        <h2><b>REGISTER</b></h2>
        <p class="welcome">Join the NCC Study Hub!</p>

        <?php if(isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>

        <?php if(isset($success)) { ?>
            <p style="color:green;"><?php echo $success; ?></p>
        <?php } ?>

        <form method="POST">

            <div class="input-group">
                <input type="text" name="fullname" placeholder="Full Name" required>
            </div>

            <div class="input-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <div class="input-group">
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            </div>

            <!-- ✅ NEW: WING -->
            <div class="input-group select-group">
                <select name="wing" required>
                    <option value="">Select Wing</option>
                    <option value="Army">Army</option>
                    <option value="Navy">Navy</option>
                    <option value="Air Force">Air Force</option>
                </select>
            </div>

            <!-- ✅ NEW: RANK -->
            <div class="input-group select-group">
                <select name="rank" required>
                    <option value="">Select Rank</option>
                    <option value="Cadet">Cadet</option>
                    <option value="Lance Corporal">Lance Corporal</option>
                    <option value="Corporal">Corporal</option>
                    <option value="Sergeant">Sergeant</option>
                </select>
            </div>

            <button type="submit" name="register" class="login-btn">
                Sign Up
            </button>

        </form>

        <div class="divider"></div>

        <p class="register-text">
            Already have an account?
            <a href="login.php">Login Here</a>
        </p>

    </div>
</div>

</div>

</body>
</html>
