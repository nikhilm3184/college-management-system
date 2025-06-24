<?php
require('database.php');  

$sql = "CREATE TABLE PendingStudents (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    NAME VARCHAR(20) NOT NULL,
    AGE INT(2) NOT NULL,
    EMAIL VARCHAR(100) UNIQUE NOT NULL,
    COURSE VARCHAR(20) NOT NULL,
    GENDER VARCHAR(20) NOT NULL,
    PAYMENT_STATUS ENUM('pending', 'done') DEFAULT 'pending',
    APPROVAL_STATUS ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    PAYMENT_ID VARCHAR(100) NULL,
    CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";

if ($conn->query($sql) === TRUE) {
    echo "Table 'PendingStudents' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
