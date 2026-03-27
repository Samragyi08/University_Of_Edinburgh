<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT Programmes.*, Levels.LevelName, Staff.Name AS LeaderName
        FROM Programmes
        LEFT JOIN Levels ON Programmes.LevelID = Levels.LevelID
        LEFT JOIN Staff ON Programmes.ProgrammeLeaderID = Staff.StaffID
        ORDER BY Programmes.ProgrammeID DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programmes - UNIVERSITY OF EDINBURGH</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header class="topbar">
    <div class="container nav">
        <div class="brand">UNIVERSITY OF EDINBURGH</div>
        <nav class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="programme_form.php">Add Programme</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </div>
</header>

<section class="section">
<div class="container">
<div class="panel">
    <h1 class="page-title">Programmes</h1>
    <a class="button" href="programme_form.php">Add New Programme</a>

    <div style="overflow-x:auto; margin-top:20px;">
        <table style="width:100%; border-collapse: collapse;">
            <tr style="background:#e8f1fc;">
                <th style="padding:12px; border:1px solid #d8e0ea;">ID</th>
                <th style="padding:12px; border:1px solid #d8e0ea;">Programme</th>
                <th style="padding:12px; border:1px solid #d8e0ea;">Level</th>
                <th style="padding:12px; border:1px solid #d8e0ea;">Leader</th>
                <th style="padding:12px; border:1px solid #d8e0ea;">Published</th>
                <th style="padding:12px; border:1px solid #d8e0ea;">Actions</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td style="padding:12px; border:1px solid #d8e0ea;"><?php echo $row['ProgrammeID']; ?></td>
                <td style="padding:12px; border:1px solid #d8e0ea;"><?php echo htmlspecialchars($row['ProgrammeName']); ?></td>
                <td style="padding:12px; border:1px solid #d8e0ea;"><?php echo htmlspecialchars($row['LevelName']); ?></td>
                <td style="padding:12px; border:1px solid #d8e0ea;"><?php echo htmlspecialchars($row['LeaderName']); ?></td>
                <td style="padding:12px; border:1px solid #d8e0ea;"><?php echo $row['Published'] ? 'Yes' : 'No'; ?></td>
                <td style="padding:12px; border:1px solid #d8e0ea; white-space:nowrap;">
                    <a href="programme_form.php?id=<?php echo $row['ProgrammeID']; ?>">Edit</a> |
                    <a href="delete_programme.php?id=<?php echo $row['ProgrammeID']; ?>" onclick="return confirm('Delete this programme?')">Delete</a>
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
