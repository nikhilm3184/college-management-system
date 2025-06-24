<?php
session_start();
require('database.php');

if (!isset($_SESSION['status']) || $_SESSION['status'] != 1) {
    header("Location: students.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM Student WHERE ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: students.php");
exit();
