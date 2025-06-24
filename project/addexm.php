<?php
session_start();
require('database.php');

if (!isset($_SESSION['user_status']) || $_SESSION['user_status'] != 1) {
    header('Location: exams_list.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['exam_name']);
    $date = $_POST['exam_date'];

    if (empty($name) || empty($date)) {
        $error = 'All fields are required.';
    } else {
        $stmt = $conn->prepare("INSERT INTO exams (exam_name, exam_date) VALUES (?, ?)");
        $stmt->bind_param('ss', $name, $date);
        if ($stmt->execute()) {
            $_SESSION['exam_success'] = "Exam '$name' added successfully.";
            header('Location: exams_list.php');
            exit;
        } else {
            $error = 'Database error: ' . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Add Exam</title>
    <style>
        body {
            font-family: Arial;
            background: #eef2f7;
            padding: 40px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 15px;
        }

        input[type=text],
        input[type=date] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn {
            margin-top: 20px;
            padding: 10px;
            background: #2980b9;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .error {
            background: #fdecea;
            border-left: 5px solid #e74c3c;
            padding: 10px;
            color: #c0392b;
            border-radius: 6px;
        }

        .back {
            margin-top: 15px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Add New Exam</h2>
        <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post">
            <label>Exam Name:</label>
            <input type="text" name="exam_name" required>
            <label>Exam Date:</label>
            <input type="date" name="exam_date" required>
            <button class="btn" type="submit">Add Exam</button>
        </form>
        <div class="back"><a href="examlist.php">&#8592; Back</a></div>
    </div>
</body>

</html>