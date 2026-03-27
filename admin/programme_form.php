<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] != 'admin') {
    die("Only admin can manage programmes.");
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = false;

$programme = [
    'ProgrammeName' => '',
    'LevelID' => '',
    'ProgrammeLeaderID' => '',
    'Description' => '',
    'Image' => '',
    'Published' => 1
];

if ($id > 0) {
    $isEdit = true;
    $result = $conn->query("SELECT * FROM Programmes WHERE ProgrammeID = $id");
    if ($result && $result->num_rows > 0) {
        $programme = $result->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string(trim($_POST['ProgrammeName']));
    $level = (int)$_POST['LevelID'];
    $leader = (int)$_POST['ProgrammeLeaderID'];
    $description = $conn->real_escape_string(trim($_POST['Description']));
    $image = $conn->real_escape_string(trim($_POST['Image']));
    $published = isset($_POST['Published']) ? 1 : 0;

    if ($id > 0) {
        $sql = "UPDATE Programmes SET
                ProgrammeName = '$name',
                LevelID = $level,
                ProgrammeLeaderID = $leader,
                Description = '$description',
                Image = '$image',
                Published = $published
                WHERE ProgrammeID = $id";
    } else {
        $sql = "INSERT INTO Programmes
                (ProgrammeName, LevelID, ProgrammeLeaderID, Description, Image, Published)
                VALUES
                ('$name', $level, $leader, '$description', '$image', $published)";
    }

    if ($conn->query($sql)) {
        header("Location: programmes.php");
        exit;
    }
}

$levels = $conn->query("SELECT * FROM Levels ORDER BY LevelID");
$staff = $conn->query("SELECT * FROM Staff ORDER BY Name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programme Form - UNIVERSITY OF EDINBURGH</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header class="topbar">
    <div class="container nav">
        <div class="brand">UNIVERSITY OF EDINBURGH</div>
        <nav class="nav-links">
            <a href="programmes.php">Back to Programmes</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </div>
</header>

<section class="section">
<div class="container">
<div class="panel result-panel" style="max-width:850px;">
    <h1 class="page-title"><?php echo $isEdit ? 'Edit Programme' : 'Add New Programme'; ?></h1>

    <form method="POST">
        <label for="ProgrammeName">Programme Name</label>
        <input type="text" id="ProgrammeName" name="ProgrammeName" value="<?php echo htmlspecialchars($programme['ProgrammeName']); ?>" required>

        <label for="LevelID" style="margin-top:12px;">Level</label>
        <select id="LevelID" name="LevelID" required>
            <option value="">Select level</option>
            <?php while ($level = $levels->fetch_assoc()) { ?>
                <option value="<?php echo $level['LevelID']; ?>" <?php if ($programme['LevelID'] == $level['LevelID']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($level['LevelName']); ?>
                </option>
            <?php } ?>
        </select>

        <label for="ProgrammeLeaderID" style="margin-top:12px;">Programme Leader</label>
        <select id="ProgrammeLeaderID" name="ProgrammeLeaderID" required>
            <option value="">Select leader</option>
            <?php while ($person = $staff->fetch_assoc()) { ?>
                <option value="<?php echo $person['StaffID']; ?>" <?php if ($programme['ProgrammeLeaderID'] == $person['StaffID']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($person['Name']); ?>
                </option>
            <?php } ?>
        </select>

        <label for="Description" style="margin-top:12px;">Description</label>
        <textarea id="Description" name="Description" rows="6" required><?php echo htmlspecialchars($programme['Description']); ?></textarea>

        <label for="Image" style="margin-top:12px;">Image URL</label>
        <input type="text" id="Image" name="Image" value="<?php echo htmlspecialchars($programme['Image']); ?>">

        <div style="margin-top:12px;">
            <label style="display:inline-flex; align-items:center; gap:8px; font-weight:normal;">
                <input type="checkbox" name="Published" <?php if ($programme['Published']) echo 'checked'; ?>>
                Published
            </label>
        </div>

        <div style="margin-top:18px;">
            <button type="submit">Save Programme</button>
        </div>
    </form>
</div>
</div>
</section>
</body>
</html>