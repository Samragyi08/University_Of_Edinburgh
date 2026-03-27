<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - UNIVERSITY OF EDINBURGH</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header class="topbar">
    <div class="container nav">
        <div class="brand">UNIVERSITY OF EDINBURGH</div>
        <nav class="nav-links">
            <a href="../index.php">View Website</a>
            <a href="programmes.php">Programmes</a>
            <a href="modules.php">Modules</a>
            <a href="students.php">Student Details</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </div>
</header>

<section class="section">
    <div class="container">
        <div class="panel">
            <h1 class="page-title">Admin Dashboard</h1>
            <p class="small">Logged in as <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> (<?php echo htmlspecialchars($_SESSION['role']); ?>)</p>

            <div class="admin-feature-grid" style="margin-top:20px;">
                <div class="info-box">
                    <h3>Programmes</h3>
                    <p>Add, edit, delete, and publish or unpublish programmes.</p>
                    <a class="button" href="programmes.php">Manage Programmes</a>
                </div>

                <div class="info-box">
                    <h3>Modules</h3>
                    <p>Add, edit, and delete modules and assign leaders.</p>
                    <a class="button" href="modules.php">Manage Modules</a>
                </div>

                <div class="info-box">
                    <h3>Student Details</h3>
                    <p>View, edit, delete, and export mailing list details.</p>
                    <a class="button" href="students.php">Manage Students</a>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>