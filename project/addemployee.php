<?php
session_start();
require('database.php');


if (!isset($_SESSION['status']) || $_SESSION['status'] != 1) {
    header("Location: loginpage.php"); 
    exit();
}

$error = '';
$success = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['employee_name']);
    $age = trim($_POST['employee_age']);
    $department = trim($_POST['employee_department']);

    
    if (empty($name) || empty($age) || empty($department)) {
        $error = "All fields are required.";
    } elseif (!is_numeric($age)) {
        $error = "Age must be a number.";
    } else {
        
        $stmt = $conn->prepare("INSERT INTO Employee ( name, age, department) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sis", $name , $age, $department);
            if ($stmt->execute()) {
                $success = "Employee added successfully.";
            } else {
                $error = "Error adding employee: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Database error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Employee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            padding: 40px;
        }

        .form-container {
            width: 420px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2d3436;
            margin-bottom: 25px;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input[type="submit"] {
            background-color: #0984e3;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #0652dd;
        }

        .message {
            text-align: center;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
        }

        .error {
            background-color: #ffe6e6;
            color: #d63031;
        }

        .success {
            background-color: #eaffea;
            color: #27ae60;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #0984e3;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add New Employee</h2>

        <?php if ($error): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php elseif ($success): ?>
            <div class="message success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            

            <label>Name:</label>
            <input type="text" name="employee_name" required>

            <label>Age:</label>
            <input type="number" name="employee_age" required min="18" max="100">

            <label>Department:</label>
            <input type="text" name="employee_department" required>

            <input type="submit" value="Add Employee">
        </form>

        <a class="back-link" href="employees.php">&larr; Back to Employees</a>
    </div>
</body>
</html>
