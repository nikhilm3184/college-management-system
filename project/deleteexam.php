<?php
session_start();
require('database.php');

if (!isset($_SESSION['user_status']) || $_SESSION['user_status'] != 1) {
    header('Location: exams_list.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare("DELETE FROM exams WHERE exam_id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();

$_SESSION['exam_success'] = 'Exam deleted successfully.';
header('Location: examlist.php');
exit;
?>
