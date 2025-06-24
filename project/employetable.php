<?php
require("database.php");

$sql = "CREATE TABLE Employee (
id INT(6) AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(30) NOT NULL,
age INT(2) NOT NULL,
department VARCHAR(20) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
  echo "Employee Table created successfully";
} else {
  echo "Error creating table: " . $conn->error;
}

$conn->close();
?> 