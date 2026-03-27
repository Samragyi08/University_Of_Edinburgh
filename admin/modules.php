<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT Modules.*, Staff.Name AS LeaderName
        FROM Modules
        LEFT JOIN Staff ON Modules.ModuleLeaderID = Staff.StaffID
        ORDER BY Modules.ModuleID DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modules - UNIVERSITY OF EDINBURGH</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header class="topbar">
    <div class="container nav">
        <div class="brand">UNIVERSITY OF EDINBURGH</div>
        <nav class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="module_form.php">Add Module</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </div>
</header>

<section class="section">
<div class="container">
<div class="panel">
    <h1 class="page-title">Modules</h1>
    <a class="button" href="module_form.php">Add New Module</a>

    <div style="overflow-x:auto; margin-top:20px;">
        <table style="width:100%; border-collapse: collapse;">
            <tr style="background:#e8f1fc;">
                <th style="padding:12px; border:1px solid #d8e0ea;">ID</th>
                <th style="padding:12px; border:1px solid #d8e0ea;">Module</th>
                <th style="padding:12px; border:1px solid #d8e0ea;">Leader</th>
                <th style="padding:12px; border:1px solid #d8e0ea;">Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td style="padding:12px; border:1px solid #d8e0ea;"><?php echo $row['ModuleID']; ?></td>
                <td style="padding:12px; border:1px solid #d8e0ea;"><?php echo htmlspecialchars($row['ModuleName']); ?></td>
                <td style="padding:12px; border:1px solid #d8e0ea;"><?php echo htmlspecialchars($row['LeaderName']); ?></td>
                <td style="padding:12px; border:1px solid #d8e0ea; white-space:nowrap;">
                    <a href="module_form.php?id=<?php echo $row['ModuleID']; ?>">Edit</a> |
                    <a href="delete_module.php?id=<?php echo $row['ModuleID']; ?>" onclick="return confirm('Delete this module?')">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>
</div>
</section>
</body>
</html>
