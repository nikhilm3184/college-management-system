<?php
require("database.php");
$sql = "CREATE TABLE announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);
";

if ($conn->query($sql) === TRUE) {
    echo "Table 'User' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
