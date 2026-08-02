<?php
$conn = mysqli_connect("localhost", "root", "", "ncc_study_hub");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


// ================= ADD =================
if(isset($_POST['add'])){

    $title = $_POST['title'];
    $description = $_POST['description'];

    // INSERT SUBJECT with type drill&goh
    mysqli_query($conn, "INSERT INTO subjects (title, description, type)
            VALUES ('$title', '$description', 'drill&goh')");

    $subject_id = mysqli_insert_id($conn);

    // FILE UPLOAD
    if(isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0){

        $file_name = $_FILES['pdf']['name'];
        $tmp_name = $_FILES['pdf']['tmp_name'];

        $upload_folder = "upload/";

        if(!is_dir($upload_folder)){
            mkdir($upload_folder, 0777, true);
        }

        $file_path = $upload_folder . time() . "_" . basename($file_name);

        if(move_uploaded_file($tmp_name, $file_path)){

            mysqli_query($conn, "INSERT INTO resources (subject_id, file_name, file_path)
                VALUES ('$subject_id', '$file_name', '$file_path')");
        }
    }

    header("Location: layout.php?page=drill_gohsubject");
    exit;
}


// ================= DELETE =================
if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    // GET FILE
    $res = mysqli_query($conn, "SELECT file_path FROM resources WHERE subject_id=$id");
    $file = mysqli_fetch_assoc($res);

    if($file && file_exists($file['file_path'])){
        unlink($file['file_path']);
    }

    mysqli_query($conn, "DELETE FROM resources WHERE subject_id=$id");
    mysqli_query($conn, "DELETE FROM subjects WHERE id=$id");

    header("Location: layout.php?page=drill_gohsubject");
    exit;
}


// ================= EDIT FETCH =================
$editData = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $editQuery = mysqli_query($conn, "SELECT * FROM subjects WHERE id=$id");
    $editData = mysqli_fetch_assoc($editQuery);
}


// ================= UPDATE =================
if(isset($_POST['update'])){

    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    // UPDATE SUBJECT
    mysqli_query($conn, "UPDATE subjects 
        SET title='$title', description='$description' 
        WHERE id=$id");

    // CHECK NEW FILE
    if(!empty($_FILES['pdf']['name']) && $_FILES['pdf']['error'] == 0){

        // DELETE OLD
        $old = mysqli_query($conn, "SELECT file_path FROM resources WHERE subject_id=$id");
        $oldFile = mysqli_fetch_assoc($old);

        if($oldFile && file_exists($oldFile['file_path'])){
            unlink($oldFile['file_path']);
        }

        // UPLOAD NEW
        $file_name = $_FILES['pdf']['name'];
        $tmp_name = $_FILES['pdf']['tmp_name'];

        $upload_folder = "upload/";

        if(!is_dir($upload_folder)){
            mkdir($upload_folder, 0777, true);
        }

        $file_path = $upload_folder . time() . "_" . basename($file_name);

        if(move_uploaded_file($tmp_name, $file_path)){

            mysqli_query($conn, "UPDATE resources 
                SET file_name='$file_name', file_path='$file_path'
                WHERE subject_id=$id");
        }
    }

    header("Location: layout.php?page=drill_gohsubject");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Drill & GOH Subjects</title>

<style>
body{
    font-family: Arial;
    background:#f4f6fb;
}
.container{
    width:90%;
    margin:auto;
}
h2{
    text-align:center;
    margin:20px 0;
}
form{
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    margin-bottom:30px;
}
input, textarea{
    width:100%;
    padding:10px;
    margin:10px 0;
    border-radius:6px;
    border:1px solid #ccc;
}
button{
    padding:10px 20px;
    border:none;
    border-radius:6px;
    background:#00b894;
    color:#fff;
    cursor:pointer;
}
table{
    width:100%;
    background:#fff;
    border-collapse:collapse;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
table th, table td{
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:center;
}
a{
    text-decoration:none;
    padding:5px 10px;
    border-radius:5px;
}
.edit{
    background:#00b894;
    color:#fff;
}
.delete{
    background:#d63031;
    color:#fff;
}
</style>

</head>
<body>

<div class="container">

<h2>Manage Drill & GOH Subjects</h2>

<!-- FORM -->
<form method="POST" enctype="multipart/form-data">

    <input type="hidden" name="id" value="<?php echo $editData['id'] ?? ''; ?>">

    <input type="text" name="title" placeholder="Enter Subject Title"
        value="<?php echo $editData['title'] ?? ''; ?>" required>

    <textarea name="description" placeholder="Enter Description"><?php echo $editData['description'] ?? ''; ?></textarea>

    <input type="file" name="pdf" accept="application/pdf" <?php echo $editData ? '' : 'required'; ?>>

    <?php if($editData){ ?>
        <button name="update">Update Subject</button>
    <?php } else { ?>
        <button name="add">Add Subject</button>
    <?php } ?>

</form>

<!-- TABLE -->
<table>
<tr>
    <th>Subject</th>
    <th>PDF Name</th>
    <th>Edit</th>
    <th>View</th>
    <th>Delete</th>
</tr>

<?php
$query = "SELECT s.*, r.file_name, r.file_path 
          FROM subjects s
          LEFT JOIN resources r ON s.id = r.subject_id
          WHERE s.type='drill&goh'";

$result = mysqli_query($conn, $query);

while($row = mysqli_fetch_assoc($result)){
?>

<tr>
    <td><?php echo $row['title']; ?></td>

    <td><?php echo $row['file_name'] ? $row['file_name'] : "No File"; ?></td>

    <td>
        <a class="edit" href="layout.php?page=drill_gohsubject&edit=<?php echo $row['id']; ?>">Edit</a>
    </td>

    <td>
        <?php if($row['file_path']){ ?>
            <a href="<?php echo $row['file_path']; ?>" target="_blank">Open</a>
        <?php } ?>
    </td>

    <td>
        <a class="delete" href="layout.php?page=drill_gohsubject&delete=<?php echo $row['id']; ?>" 
           onclick="return confirm('Delete this subject?')">Delete</a>
    </td>
</tr>

<?php } ?>
</table>

</div>

</body>
</html>