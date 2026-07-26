<?php
session_start();
include("includes/config.php");
?>

<!DOCTYPE html>
<html>
<head>
<title>NCC Exam Preparation</title>
<link rel="stylesheet" href="assets/css/style.css">

<style>

/* SECTION BOX */
.exam-section{
    margin-top:40px;
    padding:20px;
    border-radius:12px;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(10px);
}

/* SECTION HEADER */
.exam-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

/* BADGE */
.exam-badge{
    padding:6px 15px;
    border-radius:20px;
    font-size:13px;
    color:white;
}

/* COLORS */
.badge-A{ background:#3b82f6; }
.badge-B{ background:#f59e0b; }
.badge-C{ background:#22c55e; }

/* TITLE */
.exam-header h3{
    color:#fff;
    font-size:22px;
}

/* CARD IMPROVE */
.module-card{
    position:relative;
}

/* SMALL ICON */
.module-card::before{
    content:"✨";
    position:absolute;
    top:10px;
    right:15px;
    font-size:18px;
}

/* EMPTY TEXT */
.no-data{
    color:#ccc;
    padding:10px;
}

</style>

</head>

<body>

<?php include("includes/navbar.php"); ?>

<div class="container">

<h2 class="main-title"><b>NCC CERTIFICATE PREPARATION</b></h2>

<?php
$types = ['A','B','C'];

foreach($types as $type){

$result = mysqli_query($conn,
"SELECT * FROM exam_content WHERE type='$type' ORDER BY id DESC");
?>

<!-- ================= SECTION ================= -->
<div class="exam-section">

    <!-- HEADER -->
    <div class="exam-header">

        <h3><?php echo $type; ?> Certificate</h3>

        <span class="exam-badge badge-<?php echo $type; ?>">
            <?php echo $type; ?> LEVEL
        </span>

    </div>

    <!-- GRID -->
    <div class="grid-container">

    <?php
    if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_assoc($result)){
    ?>

    <div class="module-card">

        <h3><?php echo $row['title']; ?></h3>

        <p><?php echo substr($row['description'],0,80); ?>...</p>

        <a href="<?php echo $row['file_path']; ?>" 
           target="_blank" class="learn-link">
            Open Material â†’
        </a>

    </div>

    <?php } } else { ?>

    <p class="no-data">No content added yet.</p>

    <?php } ?>

    </div>

</div>

<?php } ?>

</div>

</body>
</html>
