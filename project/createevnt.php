<?php
session_start();
require('database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['exam_name']);
    $date = $_POST['exam_date'];

    $sql = "INSERT INTO exams (exam_name, exam_date) VALUES ('$name', '$date')";
    if ($conn->query($sql)) {
        $_SESSION['exam_success'] = "✅ Exam added successfully.";
        header("Location: examlist.php");
        exit;
    } else {
        $error = "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Exam</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f6fa;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 8px 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        input[type="submit"] {
            margin-top: 20px;
            background-color: #3498db;
            border: none;
            color: white;
            padding: 12px;
            width: 100%;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }

        .error {
            margin-top: 15px;
            padding: 10px;
            background-color: #fdd;
            border-left: 5px solid #c00;
            color: #c00;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Create Exam</h2>

    <?php if (!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="exam_name">Exam Name:</label>
        <input type="text" id="exam_name" name="exam_name" required>

        <label for="exam_date">Exam Date:</label>
        <input type="date" id="exam_date" name="exam_date" required>

        <input type="submit" value="Create Exam">
    </form>
</div>

</body>
</html>
