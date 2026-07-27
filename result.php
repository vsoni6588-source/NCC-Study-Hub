<?php
session_start();
include("includes/config.php");

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Test Result</title>

<style>

body {
    background: #0f172a;
    color: white;
    font-family: Arial;
}

.result-container {
    max-width: 900px;
    margin: 50px auto;
    background: #1e293b;
    padding: 30px;
    border-radius: 12px;
}

.correct { color: #22c55e; }
.wrong { color: #ef4444; }

.question {
    margin-bottom: 20px;
    padding: 15px;
    background: #334155;
    border-radius: 8px;
}

</style>

</head>
<body>

<div class="result-container">

<h2>📊 Your Result</h2>

<h3>
Score: <?php echo $_SESSION['score']; ?> / <?php echo $_SESSION['total']; ?>
</h3>

<hr>

<?php
$result = mysqli_query($conn,
"SELECT q.question, ua.is_correct,
 o.option_text AS user_answer,
 co.option_text AS correct_answer
 FROM user_answers ua
 JOIN questions q ON ua.question_id = q.id
 JOIN options o ON ua.selected_option = o.id
 JOIN options co ON co.question_id = q.id AND co.is_correct = 1
 WHERE ua.user_id='$user_id'
");
?>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<div class="question">

<p><b><?php echo $row['question']; ?></b></p>

<p>Your Answer: <?php echo $row['user_answer']; ?></p>
<p>Correct Answer: <?php echo $row['correct_answer']; ?></p>

<?php if($row['is_correct']) { ?>
<p class="correct">✔ Correct</p>
<?php } else { ?>
<p class="wrong">❌ Wrong</p>
<?php } ?>

</div>

<?php } ?>

</div>

</body>
</html>
