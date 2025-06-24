<?php
require('database.php');
session_start();

if (!isset($_GET['grade_id'])) {
    die("Grade ID not provided.");
}

$grade_id = (int)$_GET['grade_id'];

$stmt = $conn->prepare("SELECT student_id FROM grades WHERE grade_id = ?");
$stmt->bind_param("i", $grade_id);
$stmt->execute();
$stmt->bind_result($student_id);
$stmt->fetch();
$stmt->close();

if (!$student_id) {
    die("Grade not found.");
}


$delete = $conn->prepare("DELETE FROM grades WHERE grade_id = ?");
$delete->bind_param("i", $grade_id);

if ($delete->execute()) {
    header("Location: viewgrades.php?student_id=$student_id");
    exit;
} else {
    echo "❌ Error deleting grade: " . $delete->error;
}

$delete->close();
?>
