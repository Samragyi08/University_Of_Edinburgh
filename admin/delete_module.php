<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $conn->query("DELETE FROM Modules WHERE ModuleID = $id");
}

header("Location: modules.php");
exit;