<?php
require("database.php");

$sql = "CREATE TABLE Course (
id INT(6) AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(30) NOT NULL,
fees DECIMAL(10,2) NOT NULL,
duration INT(2) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
  echo "Employee Table created successfully";
} else {
  echo "Error creating table: " . $conn->error;
}

$conn->close();
?> 