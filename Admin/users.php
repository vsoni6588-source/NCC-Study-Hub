<?php
$conn = mysqli_connect("localhost", "root", "", "ncc_study_hub");

// DELETE USER
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM users WHERE id=$id");
}

// FILTERS
$search = $_GET['search'] ?? '';
$wingFilter = $_GET['wing'] ?? '';

// BASE QUERY
$query = "SELECT * FROM users WHERE 1";

// SEARCH FILTER
if(!empty($search)){
    $query .= " AND (fullname LIKE '%$search%' OR email LIKE '%$search%')";
}

// WING FILTER
if(!empty($wingFilter)){
    $query .= " AND LOWER(wing) = '".strtolower($wingFilter)."'";
}

// ORDER
$query .= " ORDER BY id DESC";

$result = mysqli_query($conn, $query);
$totalUsers = mysqli_num_rows($result);
?>

<style>

.user-header{
    margin-bottom:20px;
}

.user-card{
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

/* FILTER BAR */
.filter-bar{
    display:flex;
    gap:10px;
    margin-bottom:20px;
}

.filter-bar input,
.filter-bar select{
    padding:8px;
    border:1px solid #ccc;
    border-radius:6px;
}

.filter-bar button{
    padding:8px 15px;
    background:#2563eb;
    color:white;
    border:none;
    border-radius:6px;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
}

th, td{
    padding:12px;
    border-bottom:1px solid #eee;
}

th{
    background:#f8fafc;
}

/* BADGES */
.badge{
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
    color:white;
}

.badge.army{ background:#22c55e; }
.badge.navy{ background:#3b82f6; }
.badge.air{ background:#f59e0b; }

/* DELETE */
.delete-btn{
    color:red;
    font-weight:600;
    text-decoration:none;
}

/* TOTAL USERS CARD */
.user-total-card{
    width:100%;
    background: linear-gradient(135deg, #2563eb, #1e3a8a);
    color:white;
    padding:25px;
    border-radius:12px;
    text-align:center;
    margin:20px 0;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.user-total-card h2{
    margin:0;
    font-size:36px;
    font-weight:bold;
}

.user-total-card p{
    margin-top:5px;
    font-size:14px;
    opacity:0.9;
}
td{
    font-size:14px;
}

td:last-child{
    font-weight:500;
    color:#64748b;
}

</style>

<div class="user-header">
    <h2>👤 User Management</h2>
    <p>Manage and filter users easily</p>
</div>

<div class="user-card">

    <!-- FILTER SECTION -->
    <form method="GET" class="filter-bar">
        <input type="hidden" name="page" value="users">

        <input type="text" name="search" placeholder="Search name or email"
               value="<?php echo htmlspecialchars($search); ?>">

        <select name="wing">
            <option value="">All Wings</option>
            <option value="army" <?php if($wingFilter=='army') echo 'selected'; ?>>Army</option>
            <option value="navy" <?php if($wingFilter=='navy') echo 'selected'; ?>>Navy</option>
            <option value="airforce" <?php if($wingFilter=='airforce') echo 'selected'; ?>>Air Force</option>
        </select>

        <button type="submit">Filter</button>
    </form>

    <!-- STATS -->
    <div class="user-total-card">
    <h2><?php echo $totalUsers; ?></h2>
    <p>Total Users</p>
</div>

    <!-- TABLE -->
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Wing</th>
            <th>Rank</th>
            <th>Joined</th>
            <th>Action</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($result)) { ?>

        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['fullname']; ?></td>
            <td><?php echo $row['email']; ?></td>

            <td>
                <?php
                $wing = strtolower(trim($row['wing']));

                if($wing == 'army'){
                    echo "<span class='badge army'>Army</span>";
                }
                elseif($wing == 'navy'){
                    echo "<span class='badge navy'>Navy</span>";
                }
                elseif($wing == 'airforce' || $wing == 'air force'){
                    echo "<span class='badge air'>Air Force</span>";
                }
                else{
                    echo "<span class='badge'>".$row['wing']."</span>";
                }
                ?>
            </td>

            <td><?php echo $row['rank']; ?></td>
            <td>
            <?php 
                echo date("d M Y", strtotime($row['created_at'])); 
                    ?>
            </td>

            <td>
                <a href="layout.php?page=users&delete=<?php echo $row['id']; ?>" 
                   class="delete-btn"
                   onclick="return confirm('Delete this user?')">
                   Delete
                </a>
            </td>
        </tr>

        <?php } ?>

    </table>

</div>