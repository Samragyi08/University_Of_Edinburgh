<?php
session_start();
include '../db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username == '' || $password == '') {
        $error = "Please enter username and password.";
    } else {
        $sql = "SELECT * FROM AdminUsers WHERE Username = '$username' AND Password = '$password'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            $_SESSION['admin_id'] = $admin['AdminID'];
            $_SESSION['username'] = $admin['Username'];
            $_SESSION['role'] = $admin['Role'];

            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid login.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - UNIVERSITY OF EDINBURGH</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header class="topbar">
    <div class="container nav">
        <div class="brand">UNIVERSITY OF EDINBURGH</div>
        <nav class="nav-links">
            <a href="../index.php">Home</a>
        </nav>
    </div>
</header>

<section class="section">
    <div class="container">
        <div class="panel result-panel">
            <h1 class="page-title">Admin Login</h1>

            <?php if ($error != '') { ?>
                <div class="notice error"><?php echo htmlspecialchars($error); ?></div>
            <?php } ?>

            <form method="POST">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>

                <label for="password" style="margin-top: 12px;">Password</label>
                <input type="password" name="password" id="password" required>

                <div style="margin-top: 18px;">
                    <button type="submit">Login</button>
                </div>
            </form>

            <p class="small" style="margin-top: 20px;">Demo login: admin / admin123</p>
        </div>
    </div>
</section>
</body>
</html>