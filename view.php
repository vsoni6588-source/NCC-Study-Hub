<?php
session_start();
include("includes/config.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$subject_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// GET SUBJECT + PDF
$query = "SELECT s.*, r.file_path 
          FROM subjects s
          LEFT JOIN resources r ON s.id = r.subject_id
          WHERE s.id='$subject_id'";

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// CHECK COMPLETED
$check = mysqli_query($conn,
"SELECT * FROM user_progress 
WHERE user_id='$user_id' AND subject_id='$subject_id'");

$isCompleted = mysqli_num_rows($check) > 0;

// HANDLE COMPLETE
if(isset($_POST['mark_done'])){
    if(!$isCompleted){
        mysqli_query($conn,
        "INSERT INTO user_progress (user_id, subject_id, status)
         VALUES ('$user_id', '$subject_id', 'completed')");
    }
    header("Location: view.php?id=".$subject_id);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo $data['title']; ?></title>

<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

body{
    margin:0;
    font-family:'Segoe UI';
    background:#0f172a;
    color:white;
}

/* CONTAINER */
.view-container{
    width:90%;
    max-width:1100px;
    margin:120px auto 40px;
}

/* HEADER */
.header{
    background:#1e293b;
    padding:20px;
    border-radius:12px;
    margin-bottom:20px;
}

.header h2{
    margin:0;
}

/* PDF BOX */
.pdf-box{
    background:#1e293b;
    padding:15px;
    border-radius:12px;
}

/* BUTTONS */
.actions{
    margin-top:20px;
    display:flex;
    gap:15px;
    flex-wrap:wrap;
}

.btn{
    padding:10px 18px;
    border-radius:6px;
    text-decoration:none;
    color:white;
    font-weight:500;
}

/* VIEW */
.view-btn{
    background:#2563eb;
}

.view-btn:hover{
    background:#1d4ed8;
}

/* DOWNLOAD */
.download-btn{
    background:#22c55e;
}

.download-btn:hover{
    background:#16a34a;
}

/* COMPLETE */
.complete-btn{
    background:#f59e0b;
    border:none;
    cursor:pointer;
}

.complete-btn.done{
    background:#22c55e;
    cursor:default;
}

</style>
</head>

<body>

<?php include("includes/navbar.php"); ?>

<div class="view-container">

<!-- HEADER -->
<div class="header">
<h2><?php echo $data['title']; ?></h2>
<p>Study material with smart tracking</p>
</div>

<!-- PDF VIEW -->
<div class="pdf-box">

<iframe src="<?php echo $data['file_path']; ?>" 
width="100%" height="500px" style="border:none; border-radius:8px;"></iframe>

</div>

<!-- ACTIONS -->
<div class="actions">

<!-- VIEW -->
<a href="<?php echo $data['file_path']; ?>" target="_blank" class="btn view-btn">
    👁️ View Full PDF
</a>

<!-- DOWNLOAD (TRACKED) -->
<a href="download.php?id=<?php echo $subject_id; ?>" class="btn download-btn">
    📥 Download PDF
</a>

<!-- MARK COMPLETE -->
<form method="POST" style="display:inline;">
<?php if(!$isCompleted){ ?>
    <button type="submit" name="mark_done" class="btn complete-btn">
        ✔ Mark as Completed
    </button>
<?php } else { ?>
    <button type="button" class="btn complete-btn done">
        ✔ Completed
    </button>
<?php } ?>
</form>

</div>

</div>

</body>
</html>
