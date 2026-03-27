<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="student_mailing_list.csv"');

$output = fopen('php://output', 'w');

fputcsv($output, ['Interest ID', 'Student Name', 'Email', 'Programme', 'Active', 'Registered At']);

$sql = "SELECT InterestedStudents.InterestID,
               InterestedStudents.StudentName,
               InterestedStudents.Email,
               InterestedStudents.Active,
               InterestedStudents.RegisteredAt,
               Programmes.ProgrammeName
        FROM InterestedStudents
        JOIN Programmes ON InterestedStudents.ProgrammeID = Programmes.ProgrammeID
        ORDER BY InterestedStudents.RegisteredAt DESC";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['InterestID'],
        $row['StudentName'],
        $row['Email'],
        $row['ProgrammeName'],
        $row['Active'],
        $row['RegisteredAt']
    ]);
}

fclose($output);
exit;
