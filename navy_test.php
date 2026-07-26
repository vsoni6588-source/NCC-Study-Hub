<?php
session_start();
include("includes/config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// FETCH NAVY QUESTIONS (wing_id = 2)
$questions = mysqli_query($conn,
"SELECT * FROM questions WHERE wing_id = 2 ORDER BY RAND() LIMIT 10");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Navy Test</title>

<style>

/* PAGE */
body {
    background: #0f172a;
    font-family: Arial;
    color: white;
}

/* CONTAINER */
.test-container {
    max-width: 900px;
    margin: 50px auto;
    background: #1e3a8a;
    padding: 30px;
    border-radius: 12px;
}

/* TITLE */
.test-title {
    text-align: center;
    margin-bottom: 30px;
}

/* QUESTION BOX */
.question-box {
    margin-bottom: 25px;
    padding: 20px;
    background: #1e40af;
    border-radius: 10px;
}

/* OPTIONS */
.option {
    display: block;
    margin: 10px 0;
    padding: 10px;
    background: #3b82f6;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.3s;
}

.option:hover {
    background: #60a5fa;
}

/* RADIO */
input[type="radio"] {
    margin-right: 10px;
}

/* BUTTON */
.submit-btn {
    display: block;
    margin: 30px auto 0;
    padding: 12px 30px;
    background: #22c55e;
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 16px;
    cursor: pointer;
}

.submit-btn:hover {
    background: #16a34a;
}

</style>
</head>

<body>

<div class="test-container">

<h2 class="test-title">âš“ Navy MCQ Test</h2>

<form action="submit_test.php" method="POST">

<?php while($q = mysqli_fetch_assoc($questions)) { ?>

<div class="question-box">

    <h3><?php echo $q['question']; ?></h3>

    <?php
    $options = mysqli_query($conn,
    "SELECT * FROM options WHERE question_id=".$q['id']);

    while($opt = mysqli_fetch_assoc($options)) {
    ?>

    <label class="option">
        <input type="radio" 
               name="answer[<?php echo $q['id']; ?>]" 
               value="<?php echo $opt['id']; ?>" required>
        <?php echo $opt['option_text']; ?>
    </label>

    <?php } ?>

</div>

<?php } ?>

<button type="submit" class="submit-btn">Submit Test</button>

</form>

</div>

</body>
</html>
