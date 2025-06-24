<?php

require("database.php");

$sql="CREATE TABLE `exams` (
  `exam_id` INT AUTO_INCREMENT PRIMARY KEY,
  `exam_name` VARCHAR(100),
  `exam_date` DATE
)
";

if ($conn->query($sql) === TRUE) {
    echo "Table 'exams' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>


?>