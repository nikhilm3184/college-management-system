<?php
require("database.php");


if (!isset($_GET['id'])) {
    
    header("Location: course.php");
    exit;
}

$id = (int) $_GET['id'];


$sql = "DELETE FROM Course WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    
    header("Location: course.php");
    exit;
} else {
    

    echo "Error deleting record: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
