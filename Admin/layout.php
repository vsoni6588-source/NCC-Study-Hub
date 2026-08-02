<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: Alogin.php');
    exit();
}

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: Alogin.php');
    exit();
}

$page = $_GET['page'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html>
<head>
    <title>NCC Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/astyle.css">
</head>
<body>

<div class="main">

<!-- SIDEBAR -->
<div class="sidebar">
    <h2 class="logo">NCC STUDY HUB</h2>

    <ul>

        <li class="<?= ($page == 'subjects') ? 'active' : '' ?>">
            <a href="layout.php?page=subjects">📁 Manage Subjects</a>
        </li>

        <li class="<?= ($page == 'news') ? 'active' : '' ?>">
            <a href="layout.php?page=news">📰 Manage News</a>
        </li>

        <li class="<?= ($page == 'manage_test') ? 'active' : '' ?>">
            <a href="layout.php?page=manage_test">📝 Manage Test</a>
        </li>

        <li class="<?= ($page == 'manage_exam') ? 'active' : '' ?>">
            <a href="layout.php?page=manage_exam">📝 Manage exam</a>
        </li>

        <li class="<?= ($page == 'camps') ? 'active' : '' ?>">
            <a href="layout.php?page=camps">🏕 Manage Camps</a>
        </li>

        <li class="<?= ($page == 'users') ? 'active' : '' ?>">
            <a href="layout.php?page=users">👤 User Management</a>
        </li>

    </ul>

    <div class="admin">
        <p>Admin</p>
        <small>admin@example.com</small>
        <a href="layout.php?logout=1" class="logout">Logout</a>
    </div>
</div>

<!-- CONTENT -->
<div class="content">

<?php
if ($page == 'subjects') {
    include 'manage_subject.php';
} elseif ($page == 'armysubject') {
    include 'armysubject.php';
}elseif ($page == 'navysubject') {
    include 'navysubject.php';
}elseif ($page == 'airsubject') {
    include 'airsubject.php';
}elseif ($page == 'trainingsubject') {
    include 'trainingsubject.php';
}elseif ($page == 'drill_gohsubject') {
    include 'drill_gohsubject.php';
}elseif ($page == 'news') {
    include 'news_manage.php';
}elseif ($page == 'manage_test') {
    include 'manage_test.php';
}elseif ($page == 'manage_exam') {
    include 'manage_exam.php';
}elseif ($page == 'camps') {
    include 'manage_camps.php';
}elseif ($page == 'leadershipsubject') {
    include 'leadershipsubject.php';
}
elseif ($page == 'users') {
    include 'users.php';
} elseif ($page == 'settings') {
    include 'settings.php';
} elseif ($page == 'reports') {
    include 'reports.php';
}
?>

</div>

</div>

</body>
</html>