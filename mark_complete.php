<?php
session_start();
include("includes/config.php");

$user_id = $_SESSION['user_id'] ?? 0;
$subject_id = $_GET['id'] ?? 0;

if($user_id == 0 || $subject_id == 0){
    echo "error";
    exit();
}

// check if already exists
$check = mysqli_query($conn, 
    "SELECT * FROM user_progress 
     WHERE user_id='$user_id' AND subject_id='$subject_id'"
);

if(mysqli_num_rows($check) == 0){

    mysqli_query($conn, "
    INSERT INTO user_progress (user_id, subject_id, status)
    VALUES ('$user_id', '$subject_id', 'completed')
    ");

}

echo "done";
?>
