<?php
session_start();
include("includes/config.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$subject_id = $_GET['id'];

// GET FILE
$data = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT r.file_path FROM resources r 
JOIN subjects s ON s.id = r.subject_id
WHERE s.id='$subject_id' LIMIT 1"
));

if(!$data){
    die("File not found");
}

$file = $data['file_path'];

// ✅ SAVE DOWNLOAD RECORD
mysqli_query($conn, "
INSERT INTO downloads (user_id, subject_id, file_path)
VALUES ('$user_id', '$subject_id', '$file')
");

// ✅ FORCE DOWNLOAD
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.basename($file).'"');
readfile($file);
exit();
?>
