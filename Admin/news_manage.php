<?php
$conn = mysqli_connect("localhost", "root", "", "ncc_study_hub");

// ADD NEWS
if(isset($_POST['add_news'])){

    $title = $_POST['title'];
    $desc = $_POST['description'];
    $category = $_POST['category'];

    // ✅ CHECK FILE EXISTS FIRST
    if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){

        $file = $_FILES['file']['name'];
        $tmp = $_FILES['file']['tmp_name'];

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        // ALLOWED TYPES
        $allowed = ['jpg','jpeg','png','webp','pdf'];

        if(in_array($ext, $allowed)){

            $upload_dir = "../upload/";
            if(!is_dir($upload_dir)){
                mkdir($upload_dir, 0777, true);
            }

            $newName = time() . "_" . basename($file);
            $upload_path = $upload_dir . $newName;

            if(move_uploaded_file($tmp, $upload_path)){

                mysqli_query($conn, "
                INSERT INTO news (title, description, image, category)
                VALUES ('$title', '$desc', '$newName', '$category')
                ");

            } else {
                echo "<p class='error'>Upload failed!</p>";
            }

        } else {
            echo "<p class='error'>Only Image or PDF allowed!</p>";
        }

    } else {
        echo "<p class='error'>Please select a file!</p>";
    }
}

// DELETE NEWS
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM news WHERE id=$id");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage News</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* BACKGROUND */
body{
    background:#0f172a;
    font-family:'Segoe UI';
    margin:0;
    color:white;
}

/* CONTAINER */
.admin-container{
    width:90%;
    max-width:1000px;
    margin:40px auto;
}

/* CARD */
.card{
    background:#1e293b;
    padding:25px;
    border-radius:12px;
    margin-bottom:25px;
    box-shadow:0 10px 25px rgba(0,0,0,0.3);
}

/* TITLE */
.card h2{
    margin-bottom:15px;
}

/* INPUTS */
input, textarea, select{
    width:100%;
    padding:10px;
    margin-bottom:12px;
    border:none;
    border-radius:6px;
    background:#334155;
    color:white;
}

input::placeholder,
textarea::placeholder{
    color:#94a3b8;
}

/* FILE INPUT */
input[type="file"]{
    background:#1e293b;
}

/* BUTTON */
button{
    width:100%;
    padding:12px;
    background:#2563eb;
    border:none;
    border-radius:6px;
    color:white;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:#1d4ed8;
}

/* ERROR */
.error{
    background:#dc2626;
    padding:10px;
    border-radius:6px;
    margin-bottom:10px;
}

/* NEWS LIST */
.news-item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px;
    border-bottom:1px solid #334155;
}

/* LEFT */
.news-left h3{
    margin:0;
    font-size:16px;
}

.news-left span{
    font-size:12px;
    color:#94a3b8;
}

/* FILE TYPE ICON */
.file-icon{
    font-size:18px;
    margin-right:10px;
}

/* DELETE */
.delete-btn{
    background:#ef4444;
    padding:6px 12px;
    border-radius:5px;
    text-decoration:none;
    color:white;
    font-size:13px;
}

.delete-btn:hover{
    background:#dc2626;
}

</style>
</head>

<body>

<div class="admin-container">

<!-- ADD NEWS -->
<div class="card">
<h2>➕ Add News / Notice</h2>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="title" placeholder="Enter title" required>

<textarea name="description" placeholder="Short description (optional)"></textarea>

<select name="category">
<option value="news">News</option>
<option value="alert">Alert</option>
<option value="exam">Exam</option>
<option value="update">Update</option>
</select>

<!-- FILE -->
<input type="file" name="file" required>

<p style="font-size:12px;color:#94a3b8;">
Upload Image (JPG, PNG) or PDF Notice
</p>

<button name="add_news">Publish</button>

</form>
</div>

<!-- ALL NEWS -->
<div class="card">
<h2>📰 All News</h2>

<?php
$result = mysqli_query($conn, "SELECT * FROM news ORDER BY id DESC");

while($row = mysqli_fetch_assoc($result)) {

$ext = strtolower(pathinfo($row['image'], PATHINFO_EXTENSION));
?>

<div class="news-item">

<div class="news-left">

<?php if($ext == 'pdf'){ ?>
<i class="fa fa-file-pdf file-icon" style="color:#ef4444;"></i>
<?php } else { ?>
<i class="fa fa-image file-icon" style="color:#38bdf8;"></i>
<?php } ?>

<h3><?php echo $row['title']; ?></h3>
<span><?php echo strtoupper($row['category']); ?></span>

</div>

<a href="layout.php?page=news&delete=<?php echo $row['id']; ?>" 
class="delete-btn">Delete</a>

</div>

<?php } ?>

</div>

</div>

</body>
</html>