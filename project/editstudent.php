<?php
session_start();
require('database.php');

if (!isset($_SESSION['status']) || $_SESSION['status'] != 1) {
    header("Location: students.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $conn->prepare("SELECT * FROM Student WHERE ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $age = intval($_POST['age']);
    $email = $_POST['email'];
    $course = $_POST['course'];
    $gender = $_POST['gender'];

    $stmt = $conn->prepare("UPDATE Student SET NAME = ?, AGE = ?, EMAIL = ?, COURSE = ?, GENDER = ? WHERE ID = ?");
    $stmt->bind_param("sisssi", $name, $age, $email, $course, $gender, $id);
    $stmt->execute();

    header("Location: students.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e0eafc, #cfdef3);
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.1);
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
            margin-top: 5px;
            border: 2px solid #ccc;
            border-radius: 6px;
        }

        .gender-options {
            margin-top: 10px;
        }

        .gender-options label {
            display: inline-block;
            margin-right: 20px;
            font-weight: normal;
        }

        input[type="radio"] {
            margin-right: 6px;
        }

        .buttons {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }

        .buttons button,
        .buttons a {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
        }

        .buttons button {
            background-color: #3498db;
            color: white;
        }

        .buttons button:hover {
            background-color: #2980b9;
        }

        .buttons a {
            background-color: #e74c3c;
            color: white;
        }

        .buttons a:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Student</h2>

        <?php if ($student): ?>
            <form method="post">
                <label>Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($student['NAME']) ?>" required>

                <label>Age:</label>
                <input type="number" name="age" value="<?= htmlspecialchars($student['AGE']) ?>" required>

                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($student['EMAIL']) ?>" required>

                <label>Course:</label>
                <input type="text" name="course" value="<?= htmlspecialchars($student['COURSE']) ?>" required>

                <label>Gender:</label>
                <div class="gender-options">
                    <label><input type="radio" name="gender" value="Male" <?= $student['GENDER'] === 'Male' ? 'checked' : '' ?>> Male</label>
                    <label><input type="radio" name="gender" value="Female" <?= $student['GENDER'] === 'Female' ? 'checked' : '' ?>> Female</label>
                    <label><input type="radio" name="gender" value="Other" <?= $student['GENDER'] === 'Other' ? 'checked' : '' ?>> Other</label>
                </div>

                <div class="buttons">
                    <button type="submit">Update</button>
                    <a href="students.php">Cancel</a>
                </div>
            </form>
        <?php else: ?>
            <p>Student not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
