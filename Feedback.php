<?php
session_start();
include("includes/config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if(isset($_POST['submit'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $rating = $_POST['rating'];

    // CHECK IF USER ALREADY GAVE FEEDBACK
    $check = mysqli_query($conn,
    "SELECT * FROM feedback WHERE user_id='$user_id'");

    if(mysqli_num_rows($check) > 0){

        // UPDATE EXISTING FEEDBACK
        mysqli_query($conn,
        "UPDATE feedback 
         SET name='$name', message='$message', rating='$rating'
         WHERE user_id='$user_id'");

        echo "<script>alert('Feedback Updated Successfully');</script>";

    } else {

        // INSERT NEW FEEDBACK
        mysqli_query($conn,
        "INSERT INTO feedback (user_id, name, message, rating)
         VALUES ('$user_id','$name','$message','$rating')");

        echo "<script>alert('Feedback Submitted Successfully');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Feedback</title>

<style>
body{
    background:#0f172a;
    font-family:Arial;
    color:white;
}

.container{
    max-width:500px;
    margin:50px auto;
    background:#1e293b;
    padding:25px;
    border-radius:10px;
}

input, textarea, select{
    width:100%;
    padding:10px;
    margin-top:10px;
    border:none;
    border-radius:6px;
}

button{
    margin-top:15px;
    padding:10px;
    background:#22c55e;
    border:none;
    color:white;
    cursor:pointer;
}
</style>

</head>
<body>

<div class="container">

<h2>Give Your Feedback</h2>

<form method="POST">

<input type="text" name="name" placeholder="Your Name" required>

<textarea name="message" placeholder="Your Feedback" required></textarea>

<select name="rating">
    <option value="5">★★★★★</option>
    <option value="4">★★★★</option>
    <option value="3">★★★</option>
    <option value="2">★★</option>
    <option value="1">★</option>
</select>

<button type="submit" name="submit">Submit</button>

</form>

</div>

</body>
</html>
