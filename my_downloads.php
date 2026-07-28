<?php
session_start();
include("includes/config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ DELETE DOWNLOAD
if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];

    mysqli_query($conn, "
    DELETE FROM downloads 
    WHERE id='$delete_id' AND user_id='$user_id'
    ");

    header("Location: my_downloads.php");
    exit();
}

// ✅ FETCH DOWNLOADS (FIXED WITH FILE PATH)
$result = mysqli_query($conn, "
SELECT d.*, s.title, s.type, r.file_path 
FROM downloads d
JOIN subjects s ON d.subject_id = s.id
LEFT JOIN resources r ON s.id = r.subject_id
WHERE d.user_id='$user_id'
ORDER BY d.downloaded_at DESC
");

$totalDownloads = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html>
<head>
<title>My Downloads</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

body{
    margin:0;
    background:#0f172a;
    color:white;
    font-family:'Segoe UI';
}

/* CONTAINER */
.download-container{
    width:90%;
    max-width:1100px;
    margin:120px auto 40px;
}

/* HEADER */
.download-header{
    background:#1e293b;
    padding:20px;
    border-radius:12px;
    margin-bottom:25px;
}

.download-header h2{
    margin:0;
}

.download-header p{
    color:#94a3b8;
}

/* STATS */
.download-stats{
    display:flex;
    gap:20px;
    margin-bottom:30px;
}

.stat-card{
    flex:1;
    background:#1e293b;
    padding:20px;
    border-radius:12px;
    text-align:center;
}

.stat-card h3{
    margin:0;
    font-size:26px;
}

/* LIST */
.download-list{
    display:flex;
    flex-direction:column;
    gap:15px;
}

.download-card{
    background:#1e293b;
    padding:15px;
    border-radius:10px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    transition:0.3s;
}

.download-card:hover{
    transform:translateY(-3px);
}

/* LEFT */
.left{
    display:flex;
    align-items:center;
    gap:15px;
}

.icon{
    font-size:22px;
    color:#38bdf8;
}

/* TEXT */
.text h4{
    margin:0;
}

.text span{
    font-size:12px;
    color:#94a3b8;
}

/* OPEN BUTTON */
.download-btn{
    background:#2563eb;
    padding:8px 15px;
    border-radius:6px;
    color:white;
    text-decoration:none;
}

.download-btn:hover{
    background:#1d4ed8;
}

/* DELETE BUTTON (SMALL ICON) */
.delete-btn{
    background:#ef4444;
    padding:8px 10px;
    border-radius:6px;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    font-size:14px;
}

.delete-btn:hover{
    background:#dc2626;
}

/* BUTTON GROUP */
.btn-group{
    display:flex;
    gap:8px;
}

/* EMPTY */
.empty{
    text-align:center;
    margin-top:40px;
    color:#94a3b8;
}

</style>
</head>

<body>

<?php include("includes/navbar.php"); ?>

<div class="download-container">

<!-- HEADER -->
<div class="download-header">
<h2>📥 My Downloads</h2>
<p>Track all study materials you have downloaded</p>
</div>

<!-- STATS -->
<div class="download-stats">

<div class="stat-card">
<h3><?php echo $totalDownloads; ?></h3>
<p>Total Downloads</p>
</div>

<div class="stat-card">
<h3><?php echo date("M Y"); ?></h3>
<p>Current Activity</p>
</div>

</div>

<!-- LIST -->
<div class="download-list">

<?php
if($totalDownloads > 0){
    while($row = mysqli_fetch_assoc($result)){
?>

<div class="download-card">

<div class="left">
<div class="icon"><i class="fa fa-file-pdf"></i></div>

<div class="text">
<h4><?php echo $row['title']; ?></h4>
<span>
<?php echo strtoupper($row['type']); ?> â€¢ 
Downloaded on <?php echo date("d M Y", strtotime($row['downloaded_at'])); ?>
</span>
</div>
</div>

<!-- BUTTONS -->
<div class="btn-group">

<a href="<?php echo $row['file_path']; ?>" class="download-btn" target="_blank">
Open
</a>

<a href="my_downloads.php?delete=<?php echo $row['id']; ?>" 
class="delete-btn"
onclick="return confirm('Remove this download?')">
<i class="fa fa-trash"></i>
</a>

</div>

</div>

<?php } } else { ?>

<div class="empty">
<i class="fa fa-download" style="font-size:40px;"></i>
<p>No downloads yet. Start learning and download materials.</p>
</div>

<?php } ?>

</div>

</div>

</body>
</html>
