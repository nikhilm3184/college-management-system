<?php

require('database.php');

$sql = "CREATE TABLE IF NOT EXISTS `grades` (
  `grade_id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `exam_id` INT NOT NULL,
  `marks_obtained` DECIMAL(5,2) NOT NULL,
  FOREIGN KEY (`exam_id`) REFERENCES `exams`(`exam_id`)
)";


if ($conn->query($sql) === TRUE) {
    echo "Table 'grades' created successfully.";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();

?>
