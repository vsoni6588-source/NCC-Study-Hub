<?php
$conn = mysqli_connect("localhost","root","","ncc_study_hub");

// ADD CONTENT
if(isset($_POST['add'])){
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $type = $_POST['type'];

    $file = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];

    $path = "upload/" . basename($file);

    if(move_uploaded_file($tmp, $path)){
        mysqli_query($conn,
        "INSERT INTO exam_content (title, description, file_path, type)
         VALUES ('$title','$desc','$path','$type')");
    } else {
        echo "<p style='color:red;'>File upload failed!</p>";
    }
}

// DELETE
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM exam_content WHERE id=$id");
}
?>

<style>

/* CARD */
.admin-card{
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    margin-bottom:25px;
}

/* TITLE */
.admin-card h2{
    margin-bottom:15px;
}

/* FORM */
.admin-card input,
.admin-card textarea,
.admin-card select{
    width:100%;
    padding:10px;
    margin-bottom:10px;
    border:1px solid #ddd;
    border-radius:6px;
}

/* BUTTON */
.admin-card button{
    background:#2563eb;
    color:white;
    padding:10px 15px;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

.admin-card button:hover{
    background:#1d4ed8;
}

/* TABLE */
.exam-table{
    width:100%;
    border-collapse:collapse;
}

.exam-table th, .exam-table td{
    padding:12px;
    border-bottom:1px solid #eee;
    text-align:left;
}

.exam-table th{
    background:#f1f5f9;
}

/* BADGE */
.badge{
    padding:4px 10px;
    border-radius:20px;
    color:white;
    font-size:12px;
}

.badge.A{background:#22c55e;}
.badge.B{background:#f59e0b;}
.badge.C{background:#ef4444;}

/* DELETE */
.delete-btn{
    color:red;
    text-decoration:none;
    font-size:14px;
}

.delete-btn:hover{
    text-decoration:underline;
}

</style>

<!-- ADD FORM -->
<div class="admin-card">
<h2>📚 Add Exam Content</h2>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="title" placeholder="Enter Title" required>

<textarea name="description" placeholder="Enter Description"></textarea>

<select name="type">
    <option value="A">A Certificate</option>
    <option value="B">B Certificate</option>
    <option value="C">C Certificate</option>
</select>

<input type="file" name="file" required>

<button name="add">Add Content</button>

</form>
</div>

<!-- LIST -->
<div class="admin-card">
<h2>📋 All Exam Content</h2>

<table class="exam-table">

<tr>
<th>Title</th>
<th>Type</th>
<th>Action</th>
</tr>

<?php
$result = mysqli_query($conn, "SELECT * FROM exam_content ORDER BY id DESC");

while($row = mysqli_fetch_assoc($result)){
?>

<tr>
<td><?php echo $row['title']; ?></td>

<td>
<span class="badge <?php echo $row['type']; ?>">
<?php echo $row['type']; ?>
</span>
</td>

<td>
<a href="layout.php?page=manage_exam&delete=<?php echo $row['id']; ?>" class="delete-btn">
Delete
</a>
</td>

</tr>

<?php } ?>

</table>

</div>