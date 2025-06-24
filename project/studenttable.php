<?php
require("database.php");
$sql = "CREATE TABLE Student (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    NAME VARCHAR(20) NOT NULL,
    AGE INT(2) NOT NULL,
    EMAIL VARCHAR(100) UNIQUE NOT NULL,
    COURSE VARCHAR(20) NOT NULL,
    GENDER VARCHAR(20) NOT NULL
);
";

if ($conn->query($sql) === TRUE) {
    echo "Table 'Student' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
