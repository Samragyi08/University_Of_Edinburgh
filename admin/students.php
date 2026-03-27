<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';
$messageType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentName = trim($_POST['StudentName']);
    $email = trim($_POST['Email']);
    $programmeID = (int)$_POST['ProgrammeID'];

    if ($studentName != '' && $email != '' && $programmeID > 0) {
        $studentName = $conn->real_escape_string($studentName);
        $email = $conn->real_escape_string($email);

        $insertSql = "INSERT INTO InterestedStudents (ProgrammeID, StudentName, Email, Active)
                      VALUES ($programmeID, '$studentName', '$email', 1)
                      ON DUPLICATE KEY UPDATE StudentName = '$studentName', Active = 1";

        if ($conn->query($insertSql)) {
            $message = "Student details added successfully.";
            $messageType = 'success';
        } else {
            $message = "Could not add student details.";
            $messageType = 'error';
        }
    } else {
        $message = "Please fill in all fields.";
        $messageType = 'error';
    }
}

$sql = "SELECT InterestedStudents.InterestID,
               InterestedStudents.StudentName,
               InterestedStudents.Email,
               InterestedStudents.RegisteredAt,
               InterestedStudents.Active,
               Programmes.ProgrammeName
        FROM InterestedStudents
        JOIN Programmes ON InterestedStudents.ProgrammeID = Programmes.ProgrammeID
        ORDER BY InterestedStudents.RegisteredAt DESC";

$result = $conn->query($sql);
$programmes = $conn->query("SELECT ProgrammeID, ProgrammeName FROM Programmes ORDER BY ProgrammeName");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details - UNIVERSITY OF EDINBURGH</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header class="topbar">
    <div class="container nav">
        <div class="brand">UNIVERSITY OF EDINBURGH</div>
        <nav class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="export_students.php">Export CSV</a>
            <a href="../logout.php">Logout</a>
        </nav>
    </div>
</header>

<section class="section">
<div class="container">

    <div class="panel">
        <h1 class="page-title">Student Details</h1>
        <p class="small">Manage student registrations for programme interest.</p>
    </div>

    <div class="panel">
        <h2>Add Student Details</h2>

        <?php if ($message != '') { ?>
            <div class="notice <?php echo $messageType; ?>"><?php echo htmlspecialchars($message); ?></div>
        <?php } ?>

        <form method="POST">
            <label for="StudentName">Student Name</label>
            <input type="text" id="StudentName" name="StudentName" required>

            <label for="Email" style="margin-top:12px;">Email</label>
            <input type="email" id="Email" name="Email" required>

            <label for="ProgrammeID" style="margin-top:12px;">Programme</label>
            <select id="ProgrammeID" name="ProgrammeID" required>
                <option value="">Select programme</option>
                <?php while ($programme = $programmes->fetch_assoc()) { ?>
                    <option value="<?php echo $programme['ProgrammeID']; ?>">
                        <?php echo htmlspecialchars($programme['ProgrammeName']); ?>
                    </option>
                <?php } ?>
            </select>

            <div style="margin-top:18px;">
                <button type="submit">Add Student</button>
                <a class="button" href="export_students.php" style="margin-left:10px;">Export Mailing List</a>
            </div>
        </form>
    </div>

    <div class="panel">
        <h2>Registered Students</h2>

        <div style="overflow-x:auto; margin-top:20px;">
            <table style="width:100%; border-collapse: collapse;">
                <tr style="background:#e8f1fc;">
                    <th style="padding:12px; border:1px solid #d8e0ea;">ID</th>
                    <th style="padding:12px; border:1px solid #d8e0ea;">Student Name</th>
                    <th style="padding:12px; border:1px solid #d8e0ea;">Email</th>
                    <th style="padding:12px; border:1px solid #d8e0ea;">Programme</th>
                    <th style="padding:12px; border:1px solid #d8e0ea;">Active</th>
                    <th style="padding:12px; border:1px solid #d8e0ea;">Registered At</th>
                    <th style="padding:12px; border:1px solid #d8e0ea;">Actions</th>
                </tr>

                <?php if ($result && $result->num_rows > 0) { ?>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td style="padding:12px; border:1px solid #d8e0ea;"><?php echo $row['InterestID']; ?></td>
                            <td style="padding:12px; border:1px solid #d8e0ea;"><?php echo htmlspecialchars($row['StudentName']); ?></td>
                            <td style="padding:12px; border:1px solid #d8e0ea;"><?php echo htmlspecialchars($row['Email']); ?></td>
                            <td style="padding:12px; border:1px solid #d8e0ea;"><?php echo htmlspecialchars($row['ProgrammeName']); ?></td>
                            <td style="padding:12px; border:1px solid #d8e0ea;"><?php echo $row['Active'] ? 'Yes' : 'No'; ?></td>
                            <td style="padding:12px; border:1px solid #d8e0ea;"><?php echo htmlspecialchars($row['RegisteredAt']); ?></td>
                            <td style="padding:12px; border:1px solid #d8e0ea; white-space:nowrap;">
                                <a href="edit_student.php?id=<?php echo $row['InterestID']; ?>">Edit</a> |
                                <a href="delete_student.php?id=<?php echo $row['InterestID']; ?>" onclick="return confirm('Are you sure you want to delete this student record?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="7" style="padding:12px; border:1px solid #d8e0ea;">No student records found.</td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

</div>
</section>
</body>
</html>