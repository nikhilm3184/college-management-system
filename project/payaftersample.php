<?php
require('database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $name = $_POST['name'];
    $age = intval($_POST['age']);
    $email = $_POST['email'];
    $course = $_POST['course'];
    $gender = $_POST['gender'];
    
    
    $paymentId = " "; 

    
    $stmt = $conn->prepare("INSERT INTO PendingStudents (NAME, AGE, EMAIL, COURSE, GENDER, PAYMENT_STATUS, APPROVAL_STATUS, PAYMENT_ID) VALUES (?, ?, ?, ?, ?, 'done', 'pending', ?)");
    $stmt->bind_param("sissss", $name, $age, $email, $course, $gender, $paymentId);

    if ($stmt->execute()) {
        echo "Registration submitted successfully! Waiting for admin approval.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
