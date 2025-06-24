<?php
require("database.php");
$sql = "CREATE TABLE User (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    EMAIL VARCHAR(100) UNIQUE NOT NULL,
    USERNAME VARCHAR(50) NOT NULL,
    PASSWORD VARCHAR(255) NOT NULL UNIQUE,
    STATUS TINYINT(1) DEFAULT 0 CHECK(STATUS IN (0,1))
);
";

if ($conn->query($sql) === TRUE) {
    echo "Table 'User' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
