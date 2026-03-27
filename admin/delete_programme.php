<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] != 'admin') {
    die("Only admin can delete programmes.");
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $conn->query("DELETE FROM Programmes WHERE ProgrammeID = $id");
}

header("Location: programmes.php");
exit;