<?php include 'db.php'; ?>

<?php
$message = '';
$type = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $programme_id = (int)$_POST['programme_id'];
    $email = $conn->real_escape_string(trim($_POST['email']));

    if ($programme_id <= 0 || $email == '') {
        $message = 'Please enter your email address.';
        $type = 'error';
    } else {
        $sql = "UPDATE InterestedStudents
                SET Active = 0
                WHERE ProgrammeID = $programme_id AND Email = '$email'";

        $conn->query($sql);

        if ($conn->affected_rows > 0) {
            $message = 'Your interest has been withdrawn successfully.';
        } else {
            $message = 'No registration was found for that email.';
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
    <title>Withdraw Interest</title>
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
            <h1 class="page-title">Withdrawal Result</h1>
            <div class="notice <?php echo $type; ?>"><?php echo htmlspecialchars($message); ?></div>
            <a class="button" href="index.php">Back to homepage</a>
        </div>
    </div>
</section>
</body>
</html>