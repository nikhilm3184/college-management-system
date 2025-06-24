<?php
require("database.php");

if (!isset($_GET['id'])) {
    die("No announcement ID specified.");
}

$id = intval($_GET['id']);


$stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: view_announcements.php?msg=deleted");
    exit;
} else {
    echo "Error deleting announcement: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
