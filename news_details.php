<?php
$conn = mysqli_connect("localhost", "root", "", "ncc_study_hub");

$id = intval($_GET['id']);

$query = "SELECT * FROM news WHERE id=$id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// FILE EXTENSION
$file = $row['image'];
$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo $row['title']; ?></title>

<style>
body{
    font-family:Arial;
    background:#0f172a;
    color:white;
}

.container{
    width:80%;
    max-width:900px;
    margin:40px auto;
}

img{
    width:100%;
    border-radius:10px;
}

iframe{
    width:100%;
    height:600px;
    border-radius:10px;
    border:none;
}

.download-btn{
    display:inline-block;
    margin-top:15px;
    padding:10px 20px;
    background:#2563eb;
    color:white;
    border-radius:6px;
    text-decoration:none;
}
</style>

</head>

<body>

<div class="container">

<h1><?php echo $row['title']; ?></h1>

<p><b>Category:</b> <?php echo strtoupper($row['category']); ?></p>

<br>

<?php if($ext == 'pdf'){ ?>

    <!-- ✅ PDF VIEW -->
    <iframe src="upload/<?php echo $file; ?>"></iframe>

    <a href="upload/<?php echo $file; ?>" target="_blank" class="download-btn">
        📄 Open Full PDF
    </a>

<?php } else { ?>

    <!-- ✅ IMAGE VIEW -->
    <img src="upload/<?php echo $file; ?>">

<?php } ?>

<br><br>

<p><?php echo $row['description']; ?></p>

</div>

</body>
</html>