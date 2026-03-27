<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT * FROM InterestedStudents WHERE InterestID = $id";
$result = $conn->query($sql);
$student = $result->fetch_assoc();

if (!$student) {
    die("Student record not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string(trim($_POST['StudentName']));
    $email = $conn->real_escape_string(trim($_POST['Email']));
    $programmeID = (int)$_POST['ProgrammeID'];
    $active = isset($_POST['Active']) ? 1 : 0;

    $updateSql = "UPDATE InterestedStudents
                  SET StudentName = '$name',
                      Email = '$email',
                      ProgrammeID = $programmeID,
                      Active = $active
                  WHERE InterestID = $id";

    if ($conn->query($updateSql)) {
        header("Location: students.php");
        exit;
    }
}

$programmes = $conn->query("SELECT ProgrammeID, ProgrammeName FROM Programmes ORDER BY ProgrammeName");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - UNIVERSITY OF EDINBURGH</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header class="topbar">
    <div class="container nav">
        <div class="brand">UNIVERSITY OF EDINBURGH</div>
        <nav class="nav-links">
            <a href="students.php">Back to Student Details</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </div>
</header>

<section class="section">
<div class="container">
<div class="panel result-panel">
    <h1 class="page-title">Edit Student Details</h1>

    <form method="POST">
        <label for="StudentName">Student Name</label>
        <input type="text" id="StudentName" name="StudentName" value="<?php echo htmlspecialchars($student['StudentName']); ?>" required>

        <label for="Email" style="margin-top:12px;">Email</label>
        <input type="email" id="Email" name="Email" value="<?php echo htmlspecialchars($student['Email']); ?>" required>

        <label for="ProgrammeID" style="margin-top:12px;">Programme</label>
        <select id="ProgrammeID" name="ProgrammeID" required>
            <?php while ($programme = $programmes->fetch_assoc()) { ?>
                <option value="<?php echo $programme['ProgrammeID']; ?>" <?php if ($student['ProgrammeID'] == $programme['ProgrammeID']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($programme['ProgrammeName']); ?>
                </option>
            <?php } ?>
        </select>

        <div style="margin-top:12px;">
            <label style="display:inline-flex; align-items:center; gap:8px; font-weight:normal;">
                <input type="checkbox" name="Active" <?php if ($student['Active']) echo 'checked'; ?>>
                Active
            </label>
        </div>

        <div style="margin-top:18px;">
            <button type="submit">Save Changes</button>
        </div>
    </form>
</div>
</div>
</section>
</body>
</html>