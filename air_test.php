<?php
session_start();
include("includes/config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// FETCH AIR FORCE QUESTIONS (wing_id = 3)
$questions = mysqli_query($conn, 
"SELECT * FROM questions WHERE wing_id = 3 ORDER BY RAND() LIMIT 20");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Air Force Test - NCC Study Hub</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
    font-family: Arial;
    background:#0f172a;
    color:white;
}

/* CONTAINER */
.test-container{
    width:90%;
    max-width:900px;
    margin:40px auto;
}

/* HEADER */
.test-header{
    text-align:center;
    margin-bottom:30px;
}

.test-header h2{
    color:#60a5fa;
}

/* QUESTION CARD */
.question-box{
    background:#1e293b;
    padding:20px;
    border-radius:10px;
    margin-bottom:20px;
}

.question-box h3{
    margin-bottom:15px;
}

/* OPTIONS */
.option{
    display:block;
    margin:8px 0;
    padding:10px;
    border-radius:6px;
    background:#334155;
    cursor:pointer;
}

.option:hover{
    background:#475569;
}

/* BUTTON */
.submit-btn{
    display:block;
    margin:30px auto;
    padding:12px 30px;
    background:#2563eb;
    border:none;
    color:white;
    font-size:16px;
    border-radius:8px;
    cursor:pointer;
}

.submit-btn:hover{
    background:#1d4ed8;
}

/* RESULT */
.result-box{
    background:#1e293b;
    padding:20px;
    border-radius:10px;
    margin-top:30px;
}

.correct{ color:#22c55e; }
.wrong{ color:#ef4444; }

</style>

</head>
<body>

<div class="test-container">

<div class="test-header">
    <h2>✈️ Air Force MCQ Test</h2>
    <p>Attempt all questions and check your performance</p>
</div>

<form method="POST">

<?php
$i = 1;
$qData = [];

while($row = mysqli_fetch_assoc($questions)){
    $qData[] = $row;
?>

<div class="question-box">
    <h3>Q<?= $i ?>. <?= $row['question'] ?></h3>

    <label class="option">
        <input type="radio" name="q<?= $row['id'] ?>" value="A"> <?= $row['option_a'] ?>
    </label>

    <label class="option">
        <input type="radio" name="q<?= $row['id'] ?>" value="B"> <?= $row['option_b'] ?>
    </label>

    <label class="option">
        <input type="radio" name="q<?= $row['id'] ?>" value="C"> <?= $row['option_c'] ?>
    </label>

    <label class="option">
        <input type="radio" name="q<?= $row['id'] ?>" value="D"> <?= $row['option_d'] ?>
    </label>

</div>

<?php $i++; } ?>

<button type="submit" name="submit_test" class="submit-btn">
    🚀 Submit Test
</button>

</form>

<?php
// =====================
// RESULT LOGIC
// =====================
if(isset($_POST['submit_test'])){

    $score = 0;
    $total = count($qData);

    echo "<div class='result-box'>";
    echo "<h3>📊 Your Result</h3><br>";

    foreach($qData as $q){

        $userAns = $_POST['q'.$q['id']] ?? '';
        $correct = $q['correct_answer'];

        if($userAns == $correct){
            $score++;
            echo "<p class='correct'>✔ ".$q['question']."</p>";
        }else{
            echo "<p class='wrong'>❌ ".$q['question']."<br>
            Your Answer: $userAns | Correct: $correct</p>";
        }
    }

    echo "<hr>";
    echo "<h2>Score: $score / $total</h2>";
    echo "</div>";
}
?>

</div>

</body>
</html>
