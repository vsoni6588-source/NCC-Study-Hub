<?php
$conn = mysqli_connect("localhost", "root", "", "ncc_study_hub");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ================= ADD =================
if(isset($_POST['add'])){

    $title = $_POST['title'];
    $description = $_POST['description'];

    mysqli_query($conn, "INSERT INTO subjects (title, description, type)
    VALUES ('$title','$description','leadership')");

    $subject_id = mysqli_insert_id($conn);

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

    header("Location: layout.php?page=leadershipsubject");
    exit;
}

// ================= DELETE =================
if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    $res = mysqli_query($conn, "SELECT file_path FROM resources WHERE subject_id=$id");
    $file = mysqli_fetch_assoc($res);

    if($file && file_exists($file['file_path'])){
        unlink($file['file_path']);
    }

    mysqli_query($conn, "DELETE FROM resources WHERE subject_id=$id");
    mysqli_query($conn, "DELETE FROM subjects WHERE id=$id");

    header("Location: layout.php?page=leadershipsubject");
    exit;
}

// ================= EDIT =================
$editData = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $editData = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM subjects WHERE id=$id"));
}

// ================= UPDATE =================
if(isset($_POST['update'])){

    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    mysqli_query($conn, "UPDATE subjects 
        SET title='$title', description='$description' 
        WHERE id=$id");

    if(isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0){

        $file_name = $_FILES['pdf']['name'];
        $tmp_name = $_FILES['pdf']['tmp_name'];

        $upload_folder = "upload/";

        if(!is_dir($upload_folder)){
            mkdir($upload_folder, 0777, true);
        }

        $file_path = $upload_folder . time() . "_" . basename($file_name);

        if(move_uploaded_file($tmp_name, $file_path)){

            $old = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM resources WHERE subject_id=$id"));

            if($old){
                if(file_exists($old['file_path'])){
                    unlink($old['file_path']);
                }

                mysqli_query($conn, "UPDATE resources 
                SET file_name='$file_name', file_path='$file_path'
                WHERE subject_id=$id");

            } else {
                mysqli_query($conn, "INSERT INTO resources (subject_id, file_name, file_path)
                VALUES ('$id','$file_name','$file_path')");
            }
        }
    }

    header("Location: layout.php?page=leadershipsubject");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Leadership Management</title>

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
    background:#6c5ce7;
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
.edit{
    background:#00b894;
    color:#fff;
}
.delete{
    background:#d63031;
    color:#fff;
}
.view{
    background:#0984e3;
    color:#fff;
}
</style>

</head>
<body>

<div class="container">

<h2>Leadership Training Management</h2>

<form method="POST" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">

<input type="text" name="title" placeholder="Enter Title"
value="<?= $editData['title'] ?? '' ?>" required>

<textarea name="description" placeholder="Enter Description"><?= $editData['description'] ?? '' ?></textarea>

<input type="file" name="pdf" accept="application/pdf" <?= $editData ? '' : 'required'; ?>>

<?php if($editData){ ?>
<button name="update">Update</button>
<?php } else { ?>
<button name="add">Add</button>
<?php } ?>

</form>

<table>
<tr>
<th>Title</th>
<th>File</th>
<th>Edit</th>
<th>View</th>
<th>Delete</th>
</tr>

<?php
$result = mysqli_query($conn, "
SELECT s.*, r.file_name, r.file_path 
FROM subjects s
LEFT JOIN resources r ON s.id = r.subject_id
WHERE s.type='leadership'
");

while($row = mysqli_fetch_assoc($result)){
?>

<tr>
<td><?= $row['title']; ?></td>

<td><?= $row['file_name'] ?? 'No File'; ?></td>

<td>
<a class="edit" href="layout.php?page=leadershipsubject&edit=<?= $row['id']; ?>">Edit</a>
</td>

<td>
<?php if($row['file_path']){ ?>
<a class="view" href="<?= $row['file_path']; ?>" target="_blank">Open</a>
<?php } ?>
</td>

<td>
<a class="delete" href="layout.php?page=leadershipsubject&delete=<?= $row['id']; ?>" onclick="return confirm('Delete?')">Delete</a>
</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>