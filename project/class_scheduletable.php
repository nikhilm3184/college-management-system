<?php
require("database.php");


$sql = "CREATE TABLE IF NOT EXISTS class_schedule (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    instructor_id INT NOT NULL,
    day_of_week VARCHAR(10) NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    UNIQUE(course_id, day_of_week, start_time),
    FOREIGN KEY (course_id) REFERENCES Course(id) ON DELETE CASCADE,
    FOREIGN KEY (instructor_id) REFERENCES Employee(id) ON DELETE CASCADE
) ENGINE=InnoDB;";


if ($conn->query($sql) === TRUE) {
    echo "<h3>✅ Table 'class_schedule' created successfully!</h3>";
} else {
    echo "<h3>❌ Error creating table: " . $conn->error . "</h3>";
}

$conn->close();
?>
