<?php
session_start();
require('database.php');

if (!isset($_SESSION['status']) || $_SESSION['status'] != 1) {
    die("Unauthorized access.");
}

$name = $age = $email = $course = $gender = "";
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['student_name']));
    $age = htmlspecialchars(trim($_POST['student_age']));
    $email = htmlspecialchars(trim($_POST['student_email']));
    $course = htmlspecialchars(trim($_POST['student_course']));
    $gender = htmlspecialchars(trim($_POST['student_gender']));

    if (empty($name) || empty($age) || empty($email) || empty($course) || empty($gender)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!is_numeric($age) || $age <= 0) {
        $error = "Age must be a positive number.";
    } else {
        $stmt = $conn->prepare("INSERT INTO Student (name, age, email, course, gender) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisss", $name, $age, $email, $course, $gender);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: students.php");
            exit();
        } else {
            $error = "Failed to add student. Please try again.";
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #ece9e6, #ffffff);
            padding: 30px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
        }

        input[type="text"],
        input[type="number"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 6px;
            margin-top: 5px;
        }

        .gender-options {
            margin-top: 5px;
        }

        .gender-options label {
            display: inline-block;
            margin-right: 20px;
            font-weight: normal;
        }

        input[type="radio"] {
            margin-right: 6px;
        }

        input[type="submit"] {
            background-color: #27ae60;
            color: white;
            padding: 12px;
            margin-top: 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #219150;
        }

        .message {
            text-align: center;
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
        }

        .success {
            background-color: #dff0d8;
            color: #3c763d;
            border-left: 6px solid #3c763d;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 6px solid #f5c6cb;
        }

        .back-link-container {
            text-align: center;
            margin-top: 40px;
        }

        .back-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #34495e;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .back-link:hover {
            background-color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Student</h2>

        <?php if (!empty($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="student_name">Name</label>
            <input type="text" id="student_name" name="student_name" required>

            <label for="student_age">Age</label>
            <input type="number" id="student_age" name="student_age" required>

            <label for="student_email">Email</label>
            <input type="email" id="student_email" name="student_email" required>

            <label for="student_course">Course</label>
            <input type="text" id="student_course" name="student_course" required>

            <label>Gender</label>
            <div class="gender-options">
                <label><input type="radio" name="student_gender" value="Male" required> Male</label>
                <label><input type="radio" name="student_gender" value="Female"> Female</label>
                <label><input type="radio" name="student_gender" value="Other"> Other</label>
            </div>

            <input type="submit" value="Add Student">
        </form>
    </div>

    <div class="back-link-container">
        <a href="students.php" class="back-link">← Back to Students Page</a>
    </div>
</body>
</html>
