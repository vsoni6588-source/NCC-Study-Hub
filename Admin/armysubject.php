<?php
$conn = mysqli_connect("localhost", "root", "", "ncc_study_hub");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// GET wing_id
$wing_id = isset($_GET['wing_id']) ? (int)$_GET['wing_id'] : 0;

if ($wing_id == 0) {
    die("Invalid Wing ID");
}

// GET WING NAME
$wingData = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM subjects 
WHERE type='wing' AND wing_id = $wing_id"));
$wing_name = $wingData['name'] ?? 'ARMY';


// ✅ ADD SUBJECT (UPDATED)
if (isset($_POST['add'])) {

    $title = mysqli_real_escape_string($conn, $_POST['subject_name']);
    $wing_id_post = (int)$_POST['wing_id'];

    // 1️⃣ Insert subject
    mysqli_query($conn, "INSERT INTO subjects (title, type, wing_id)
    VALUES ('$title', 'wing', '$wing_id_post')");

    $subject_id = mysqli_insert_id($conn);

    // 2️⃣ Upload PDF → store in resources table
    if (!empty($_FILES['pdf']['name'])) {

        $file = $_FILES['pdf']['name'];
        $tmp = $_FILES['pdf']['tmp_name'];

        $path = "upload/" . $file;
        move_uploaded_file($tmp, $path);

        mysqli_query($conn, "INSERT INTO resources (subject_id, file_name, file_path)
VALUES ($subject_id, '$file', '$path')");
    }
}


// ✅ DELETE SUBJECT (UPDATED)
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    // DELETE FILES
    $res = mysqli_query($conn, "SELECT file_path FROM resources WHERE subject_id=$id");
    while($file = mysqli_fetch_assoc($res)){
        if(file_exists($file['file_path'])){
            unlink($file['file_path']);
        }
    }

    mysqli_query($conn, "DELETE FROM subjects WHERE id=$id");
}


if (isset($_POST['update'])) {

    $id = (int)$_POST['id'];
    $title = mysqli_real_escape_string($conn, $_POST['subject_name']);

    // UPDATE SUBJECT TITLE
    mysqli_query($conn, "UPDATE subjects 
    SET title='$title' 
    WHERE id=$id");

    // CHECK NEW PDF
    if (!empty($_FILES['pdf']['name'])) {

        $file = $_FILES['pdf']['name'];
        $tmp = $_FILES['pdf']['tmp_name'];

        // MAIN UPLOAD FOLDER
        $path = "upload/" . time() . "_" . $file;

        move_uploaded_file($tmp, $path);

        // CHECK OLD PDF
        $old = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT * FROM resources WHERE subject_id=$id"));

        if($old){

            // DELETE OLD FILE
            if(file_exists($old['file_path'])){
                unlink($old['file_path']);
            }

            // UPDATE DATABASE
            mysqli_query($conn, "UPDATE resources 
            SET file_name='$file',
                file_path='$path'
            WHERE subject_id=$id");

        } else {

            // INSERT NEW PDF
            mysqli_query($conn, "INSERT INTO resources 
            (subject_id, file_name, file_path)
            VALUES ($id, '$file', '$path')");
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Subjects</title>
    <link rel="stylesheet" href="astyle.css">
</head>
<body>

<h2>Manage Subjects - <?= $wing_name ?></h2>

<div class="card">

<!-- ✅ ADD FORM -->
<form method="POST" enctype="multipart/form-data">

    <input type="text" name="subject_name" placeholder="Subject Name" required>

    <!-- ✅ VERY IMPORTANT -->
    <input type="hidden" name="wing_id" value="<?= $wing_id ?>">

    <input type="file" name="pdf" required>

    <button name="add" class="btn add-btn">Add</button>
</form>

<br>

<!-- ✅ TABLE -->
<table>
<tr>
    <th>Subject</th>
    <th>PDF</th>
    <th>Edit</th>
    <th>View</th>
    <th>Delete</th>
</tr>

<?php
// ✅ SHOW ONLY CURRENT WING SUBJECTS
$result = mysqli_query($conn, "SELECT * FROM subjects WHERE wing_id = $wing_id");

while($row = mysqli_fetch_assoc($result)){
?>

<tr>
    <td><?= $row['title'] ?></td>

    <td>
<?php
$pdfs = mysqli_query($conn, "SELECT * FROM resources WHERE subject_id=".$row['id']);
while($pdf = mysqli_fetch_assoc($pdfs)){
    echo $pdf['file_name'] . "<br>";
}
?>
</td>

    <td>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">

            <input type="text" name="subject_name" value="<?= $row['title'] ?>">

            <input type="file" name="pdf">

            <button name="update" class="btn edit">Update</button>
        </form>
    </td>

    <td>
        <?php
$onePdf = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM resources WHERE subject_id=".$row['id']." LIMIT 1"));
?>

<a href="<?= $onePdf['file_path'] ?? '#' ?>" target="_blank" class="btn view">View</a>
    </td>

    <td>
        <a href="layout.php?page=armysubject&wing_id=<?= $wing_id ?>&delete=<?= $row['id'] ?>"
           onclick="return confirm('Are you sure?')" 
           class="btn delete">Delete</a>
    </td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>