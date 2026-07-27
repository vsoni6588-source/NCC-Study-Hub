<?php
session_start();
include("includes/config.php");

$user_id = $_SESSION['user_id'];
$answers = $_POST['answer'];

mysqli_query($conn, "DELETE FROM user_answers WHERE user_id='$user_id'");

$score = 0;

foreach($answers as $question_id => $option_id){

    $correct = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT id FROM options 
         WHERE question_id='$question_id' AND is_correct=1"
    ));

    $is_correct = ($correct['id'] == $option_id) ? 1 : 0;

    if($is_correct) $score++;

    mysqli_query($conn,
        "INSERT INTO user_answers (user_id, question_id, selected_option, is_correct)
         VALUES ('$user_id','$question_id','$option_id','$is_correct')"
    );
}

$_SESSION['score'] = $score;
$_SESSION['total'] = count($answers);

header("Location: result.php");
exit();
?>
