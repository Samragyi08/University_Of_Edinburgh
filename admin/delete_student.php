<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $conn->query("DELETE FROM InterestedStudents WHERE InterestID = $id");
}

header("Location: students.php");
exit;