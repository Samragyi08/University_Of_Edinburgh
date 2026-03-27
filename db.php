<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "university_of_edinburgh";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>