<?php
session_start();
require('database.php');

if (!isset($_SESSION['status']) || $_SESSION['status'] != 1) {
    header("Location: loginpage.php");
    exit();
}

$id = $_GET['id'] ?? '';

if ($id) {
    $stmt = $conn->prepare("DELETE FROM Employee WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: employees.php");
exit();
