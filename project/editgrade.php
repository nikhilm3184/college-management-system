<?php
require('database.php');
session_start();

if (!isset($_GET['grade_id'])) {
    die("Grade ID not provided.");
}

$grade_id = (int)$_GET['grade_id'];
$message = "";


$stmt = $conn->prepare("SELECT * FROM grades WHERE grade_id = ?");
$stmt->bind_param("i", $grade_id);
$stmt->execute();
$result = $stmt->get_result();
$grade = $result->fetch_assoc();
$stmt->close();

if (!$grade) {
    die("❌ Grade not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $marks = (float)$_POST['marks'];

    $update = $conn->prepare("UPDATE grades SET marks_obtained = ? WHERE grade_id = ?");
    $update->bind_param("di", $marks, $grade_id);

    if ($update->execute()) {
        header("Location: viewgrades.php?student_id=" . $grade['student_id']);
        exit;
    } else {
        $message = "❌ Update failed: " . $update->error;
    }

    $update->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Grade</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6fbfc;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
            color: #34495e;
        }

        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        input[type="submit"] {
            margin-top: 25px;
            background-color: #2980b9;
            border: none;
            color: white;
            padding: 12px;
            width: 100%;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #21618c;
        }

        .message {
            margin-top: 15px;
            padding: 10px;
            background-color: #fdecea;
            border-left: 5px solid #e74c3c;
            color: #c0392b;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Grade</h2>

    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <form method="post">
        <p><strong>Student ID:</strong> <?= $grade['student_id'] ?></p>
        <p><strong>Exam ID:</strong> <?= $grade['exam_id'] ?></p>

        <label for="marks">Marks Obtained:</label>
        <input type="number" step="0.01" id="marks" name="marks" value="<?= $grade['marks_obtained'] ?>" required>

        <input type="submit" value="Update Grade">
    </form>
</div>

</body>
</html>
