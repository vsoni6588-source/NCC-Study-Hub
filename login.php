<?php
session_start();
include("includes/config.php");

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if($user && password_verify($password, $user['password'])){
    
    $_SESSION['user_id'] = $user['id'];          // store ID
    $_SESSION['user_name'] = $user['fullname'];  // store name
    $_SESSION['user_email'] = $user['email'];    // store email

    header("Location: home.php");
    exit();
} else {
        $error = "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>NCC Study Hub - Login</title>
    <link rel="stylesheet" href="assets/css/lstyle.css">
</head>
<body>
    <div class="container">

   
<div class="login-container">
    <div class="login-box">

        <h2><b>LOGIN</b></h2>
        <p class="welcome">Welcome Back, Cadet!</p>

        <?php if(isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>

        <form method="POST">
            <div class="input-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <div class="remember">
                <input type="checkbox"> Remember Me
            </div>

            <button type="submit" name="login" class="login-btn">
                Login
            </button>
        </form>

        <a href="#" class="forgot">Forgot Password?</a>

        <div class="divider"></div>

        <p class="register-text">
            Donâ€™t have an account?
            <a href="register.php">Register Here</a>
        </p>

    </div>
</div>

</body>
</html>







