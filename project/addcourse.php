<?php
session_start();
require('database.php');

$name = $fees = $duration = "";
$success = $error = "";


if (!isset($_SESSION['status']) || $_SESSION['status'] != 1) {
    die("Unauthorized access.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = htmlspecialchars(trim($_POST['course_name']));
    $fees = htmlspecialchars(trim($_POST['course_fees']));
    $duration = htmlspecialchars(trim($_POST['course_duration']));

    if ( empty($name) || empty($fees) || empty($duration)) {
        $error = "All fields are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO Course ( name, fees, duration) VALUES ( ?, ?, ?)");
        $stmt->bind_param("ssi",  $name, $fees, $duration);
        if ($stmt->execute()) {
            $success = "Course added successfully.";
            header("Location: course.php");
            exit();
        } else {
            $error = "Failed to add course. ID already exist.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Course</title>
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
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 6px;
            margin-top: 5px;
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
        <h2>Add New Course</h2>

        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            
            <label for="course_name">Course Name</label>
            <input type="text" id="course_name" name="course_name" required>

            <label for="course_fees">Course Fees</label>
            <input type="number" id="course_fees" name="course_fees" required>

            <label for="course_duration">Course Duration</label>
            <input type="text" id="course_duration" name="course_duration" required>

            <input type="submit" value="Add Course">
        </form>
    </div>
<div class="back-link-container">
    <a href="course.php" class="back-link">← Back to Course Page</a>
</div>


</body>
</html>
