<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = false;

$module = [
    'ModuleName' => '',
    'ModuleLeaderID' => '',
    'Description' => '',
    'Image' => ''
];

if ($id > 0) {
    $isEdit = true;
    $result = $conn->query("SELECT * FROM Modules WHERE ModuleID = $id");
    if ($result && $result->num_rows > 0) {
        $module = $result->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string(trim($_POST['ModuleName']));
    $leader = (int)$_POST['ModuleLeaderID'];
    $description = $conn->real_escape_string(trim($_POST['Description']));
    $image = $conn->real_escape_string(trim($_POST['Image']));

    if ($id > 0) {
        $sql = "UPDATE Modules SET
                ModuleName = '$name',
                ModuleLeaderID = $leader,
                Description = '$description',
                Image = '$image'
                WHERE ModuleID = $id";
    } else {
        $sql = "INSERT INTO Modules
                (ModuleName, ModuleLeaderID, Description, Image)
                VALUES
                ('$name', $leader, '$description', '$image')";
    }

    if ($conn->query($sql)) {
        header("Location: modules.php");
        exit;
    }
}

$staff = $conn->query("SELECT * FROM Staff ORDER BY Name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module Form - UNIVERSITY OF EDINBURGH</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header class="topbar">
    <div class="container nav">
        <div class="brand">UNIVERSITY OF EDINBURGH</div>
        <nav class="nav-links">
            <a href="modules.php">Back to Modules</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </div>
</header>

<section class="section">
<div class="container">
<div class="panel result-panel" style="max-width:850px;">
    <h1 class="page-title"><?php echo $isEdit ? 'Edit Module' : 'Add New Module'; ?></h1>

    <form method="POST">
        <label for="ModuleName">Module Name</label>
        <input type="text" id="ModuleName" name="ModuleName" value="<?php echo htmlspecialchars($module['ModuleName']); ?>" required>

        <label for="ModuleLeaderID" style="margin-top:12px;">Module Leader</label>
        <select id="ModuleLeaderID" name="ModuleLeaderID" required>
            <option value="">Select leader</option>
            <?php while ($person = $staff->fetch_assoc()) { ?>
                <option value="<?php echo $person['StaffID']; ?>" <?php if ($module['ModuleLeaderID'] == $person['StaffID']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($person['Name']); ?>
                </option>
            <?php } ?>
        </select>

        <label for="Description" style="margin-top:12px;">Description</label>
        <textarea id="Description" name="Description" rows="6" required><?php echo htmlspecialchars($module['Description']); ?></textarea>

        <label for="Image" style="margin-top:12px;">Image URL</label>
        <input type="text" id="Image" name="Image" value="<?php echo htmlspecialchars($module['Image']); ?>">

        <div style="margin-top:18px;">
            <button type="submit">Save Module</button>
        </div>
    </form>
</div>
</div>
</section>
</body>
</html>
