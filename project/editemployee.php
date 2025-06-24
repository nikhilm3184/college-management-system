<?php
session_start();
require('database.php');

if (!isset($_SESSION['status']) || $_SESSION['status'] != 1) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['employee_name']);
    $age = htmlspecialchars($_POST['employee_age']);
    $department = htmlspecialchars($_POST['employee_department']);

    $stmt = $conn->prepare("UPDATE Employee SET name = ?, age = ?, department = ? WHERE id = ?");
    $stmt->bind_param("siss", $name, $age, $department, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: employees.php");
    exit();
}


$stmt = $conn->prepare("SELECT * FROM Employee WHERE id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();
$stmt->close();

if (!$employee) {
    echo "Employee not found.";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Employee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 40px;
        }

        .form-container {
            width: 400px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background: #00b894;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background: #019875;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Employee</h2>
        <form action="" method="POST">
            <label>Name:</label>
            <input type="text" name="employee_name" value="<?php echo htmlspecialchars($employee['name']); ?>" required>

            <label>Age:</label>
            <input type="number" name="employee_age" value="<?php echo htmlspecialchars($employee['age']); ?>" required>

            <label>Department:</label>
            <input type="text" name="employee_department" value="<?php echo htmlspecialchars($employee['department']); ?>" required>

            <input type="submit" value="Update Employee">
        </form>
                <a class="back-link" href="employees.php">&larr; Back to Employees</a>

    </div>
</body>
</html>
