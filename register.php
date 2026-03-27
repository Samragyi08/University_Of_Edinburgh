<?php include 'db.php'; ?>

<?php
$message = '';
$type = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $programme_id = (int)$_POST['programme_id'];
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));

    if ($programme_id <= 0 || $name == '' || $email == '') {
        $message = 'Please fill in all fields.';
        $type = 'error';
    } else {
        $sql = "INSERT INTO InterestedStudents (ProgrammeID, StudentName, Email, Active)
                VALUES ($programme_id, '$name', '$email', 1)
                ON DUPLICATE KEY UPDATE StudentName = '$name', Active = 1";

        if ($conn->query($sql)) {
            $message = 'Your interest has been registered successfully.';
        } else {
            $message = 'There was a problem saving your registration.';
            $type = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Interest</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topbar">
    <div class="container nav">
        <div class="brand">UNIVERSITY OF EDINBURGH</div>
        <nav class="nav-links">
            <a href="index.php">Home</a>
        </nav>
    </div>
</header>

<section class="section">
    <div class="container">
        <div class="panel result-panel">
            <h1 class="page-title">Registration Result</h1>
            <div class="notice <?php echo $type; ?>"><?php echo htmlspecialchars($message); ?></div>
            <a class="button" href="index.php">Back to homepage</a>
        </div>
    </div>
</section>
</body>
</html>