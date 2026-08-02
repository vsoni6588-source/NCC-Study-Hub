<?php
include("../includes/config.php");

/* =====================
   CSV UPLOAD
===================== */
if(isset($_POST['upload_csv'])){

    $wing_id = $_POST['wing_id'];

    if($_FILES['csv_file']['error'] == 0){

        $file = fopen($_FILES['csv_file']['tmp_name'], "r");
        fgetcsv($file); // skip header

        while(($row = fgetcsv($file)) !== FALSE){

            $question = mysqli_real_escape_string($conn, $row[0]);

            mysqli_query($conn,
            "INSERT INTO questions (question, wing_id)
             VALUES ('$question','$wing_id')");

            $question_id = mysqli_insert_id($conn);

            for($i=1; $i<=4; $i++){
                $opt = mysqli_real_escape_string($conn, $row[$i]);
                $correct = ($row[5] == $i) ? 1 : 0;

                mysqli_query($conn,
                "INSERT INTO options (question_id, option_text, is_correct)
                 VALUES ('$question_id','$opt','$correct')");
            }
        }

        fclose($file);
        echo "<script>alert('CSV Uploaded Successfully');</script>";
    }
}

/* =====================
   ADD QUESTION
===================== */
if(isset($_POST['add_question'])){

    $question = mysqli_real_escape_string($conn, $_POST['question']);
    $wing_id = $_POST['wing_id'];

    mysqli_query($conn,
    "INSERT INTO questions (question, wing_id)
     VALUES ('$question','$wing_id')");

    $qid = mysqli_insert_id($conn);

    for($i=1;$i<=4;$i++){
        $opt = mysqli_real_escape_string($conn, $_POST['option'.$i]);
        $correct = ($_POST['correct']==$i)?1:0;

        mysqli_query($conn,
        "INSERT INTO options (question_id, option_text, is_correct)
         VALUES ('$qid','$opt','$correct')");
    }

    header("Location: manage_test.php");
}

/* =====================
   DELETE
===================== */
if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    mysqli_query($conn, "DELETE FROM options WHERE question_id=$id");
    mysqli_query($conn, "DELETE FROM questions WHERE id=$id");

    header("Location: manage_test.php");
}

/* =====================
   EDIT FETCH
===================== */
$edit = null;
$options_edit = [];

if(isset($_GET['edit'])){

    $id = $_GET['edit'];

    $edit = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM questions WHERE id=$id"));

    $opt_res = mysqli_query($conn,
    "SELECT * FROM options WHERE question_id=$id");

    while($row = mysqli_fetch_assoc($opt_res)){
        $options_edit[] = $row;
    }
}

/* =====================
   UPDATE
===================== */
if(isset($_POST['update_question'])){

    $id = $_POST['id'];
    $question = mysqli_real_escape_string($conn, $_POST['question']);

    mysqli_query($conn,
    "UPDATE questions SET question='$question' WHERE id=$id");

    $opt_res = mysqli_query($conn,
    "SELECT * FROM options WHERE question_id=$id");

    $i = 1;
    while($opt = mysqli_fetch_assoc($opt_res)){

        $newOpt = mysqli_real_escape_string($conn, $_POST['option'.$i]);
        $correct = ($_POST['correct']==$i)?1:0;

        mysqli_query($conn,
        "UPDATE options SET 
         option_text='$newOpt',
         is_correct='$correct'
         WHERE id=".$opt['id']);

        $i++;
    }

    header("Location: manage_test.php");
}

/* =====================
   FETCH ALL
===================== */
$result = mysqli_query($conn,
"SELECT * FROM questions ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Test</title>

<style>
body{font-family:Arial;background:#f4f6fb;padding:20px;}
.card{background:#fff;padding:20px;border-radius:10px;margin-bottom:20px;}
input,select,textarea{width:100%;padding:10px;margin:8px 0;}
button{padding:10px;background:#22c55e;color:white;border:none;border-radius:6px;}
table{width:100%;background:#fff;border-collapse:collapse;}
th,td{padding:10px;border-bottom:1px solid #ddd;}
a{padding:5px 10px;border-radius:5px;text-decoration:none;color:white;}
.edit{background:#00b894;}
.delete{background:#d63031;}
</style>

</head>
<body>

<h2>📂 Upload CSV</h2>

<div class="card">
<form method="POST" enctype="multipart/form-data">
<select name="wing_id">
<option value="1">Army</option>
<option value="2">Navy</option>
<option value="3">Air Force</option>
<option value="4">Leadership</option>
</select>
<input type="file" name="csv_file" required>
<button name="upload_csv">Upload CSV</button>
</form>
</div>

<h2><?php echo $edit ? "✏️ Edit Question" : "➕ Add Question"; ?></h2>

<div class="card">
<form method="POST">

<input type="hidden" name="id" value="<?php echo $edit['id'] ?? ''; ?>">

<textarea name="question" required><?php echo $edit['question'] ?? ''; ?></textarea>

<select name="wing_id">
<option value="1">Army</option>
<option value="2">Navy</option>
<option value="3">Air Force</option>
<option value="4">Leadership</option>
</select>

<?php
for($i=1;$i<=4;$i++){

$val = $edit ? $options_edit[$i-1]['option_text'] : '';
?>

<input type="text" name="option<?php echo $i; ?>" value="<?php echo $val; ?>" required>

<?php } ?>

<select name="correct">
<?php for($i=1;$i<=4;$i++){ ?>
<option value="<?php echo $i; ?>"
<?php if($edit && $options_edit[$i-1]['is_correct']) echo "selected"; ?>>
Correct Option <?php echo $i; ?>
</option>
<?php } ?>
</select>

<?php if($edit){ ?>
<button name="update_question">Update</button>
<?php } else { ?>
<button name="add_question">Add</button>
<?php } ?>

</form>
</div>

<h2>📋 All Questions</h2>

<table>

<tr>
<th>Question</th>
<th>Wing</th>
<th>Actions</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>
<td><?php echo $row['question']; ?></td>
<td>
<?php
echo ($row['wing_id']==1) ? "Army" :
     (($row['wing_id']==2) ? "Navy" :
     (($row['wing_id']==3) ? "Air Force" :
     "Leadership"));
?>
</td>

<td>
<a class="edit" href="manage_test.php?edit=<?php echo $row['id']; ?>">Edit</a>
<a class="delete" href="manage_test.php?delete=<?php echo $row['id']; ?>"
onclick="return confirm('Delete this question?')">Delete</a>
</td>
</tr>

<?php } ?>

</table>

</body>
</html>
