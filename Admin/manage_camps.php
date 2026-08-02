<?php
$conn = mysqli_connect("localhost", "root", "", "ncc_study_hub");

// ================= ADD CAMP =================
if(isset($_POST['add_camp'])){

    $title = $_POST['title'];
    $desc = $_POST['description'];

    // FILE
    if(isset($_FILES['file']) && $_FILES['file']['name'] != ""){

        $file = $_FILES['file']['name'];
        $tmp = $_FILES['file']['tmp_name'];

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        // ONLY PDF
        if($ext == "pdf"){

            $newName = time() . "_" . $file;
            $path = "uploads/camps/" . $newName;

            if(move_uploaded_file($tmp, $path)){

                mysqli_query($conn,
                "INSERT INTO camp_material (title, description, file_path)
                 VALUES ('$title','$desc','$path')");

                echo "<script>alert('Camp material added successfully');</script>";

            } else {
                echo "<p style='color:red;'>Upload failed!</p>";
            }

        } else {
            echo "<p style='color:red;'>Only PDF allowed!</p>";
        }

    } else {
        echo "<p style='color:red;'>Please select a file!</p>";
    }
}

// ================= DELETE =================
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);

    mysqli_query($conn, "DELETE FROM camp_material WHERE id=$id");

    echo "<script>alert('Deleted successfully'); window.location='layout.php?page=camps';</script>";
}
?>

<style>

.card{
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    margin-bottom:20px;
}

/* FORM */
input, textarea{
    width:100%;
    padding:10px;
    margin-bottom:10px;
    border-radius:6px;
    border:1px solid #ccc;
}

button{
    background:#2563eb;
    color:white;
    border:none;
    padding:10px 15px;
    border-radius:6px;
    cursor:pointer;
}

button:hover{
    background:#1d4ed8;
}

/* LIST */
.camp-item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px solid #ddd;
    padding:12px 0;
}

.camp-item h4{
    margin:0;
}

.actions a{
    margin-left:10px;
    text-decoration:none;
}

.delete{
    color:red;
}

.view{
    color:#2563eb;
}

small{
    color:#666;
}

</style>

<!-- ================= ADD FORM ================= -->

<div class="card">

<h2>📚 Add Camp Material</h2>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="title" placeholder="Material Title" required>

<textarea name="description" placeholder="Short Description"></textarea>

<input type="file" name="file" required>

<button name="add_camp">Upload Material</button>

</form>

</div>

<!-- ================= LIST ================= -->

<div class="card">

<h2>🪖 All Camp Materials</h2>

<?php
$result = mysqli_query($conn, "SELECT * FROM camp_material ORDER BY id DESC");

if(mysqli_num_rows($result) > 0){

while($row = mysqli_fetch_assoc($result)){
?>

<div class="camp-item">

<div>
<h4><?php echo $row['title']; ?></h4>
<small><?php echo substr($row['description'],0,50); ?>...</small>
</div>

<div class="actions">
<a href="<?php echo $row['file_path']; ?>" target="_blank" class="view">View</a>

<a href="layout.php?page=camps&delete=<?php echo $row['id']; ?>" 
class="delete"
onclick="return confirm('Delete this material?')">
Delete
</a>
</div>

</div>

<?php } } else { ?>

<p>No camp materials found.</p>

<?php } ?>

</div>