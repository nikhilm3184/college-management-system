<?php
require('database.php');
session_start();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sid = (int)$_POST['student_id'];
    $eid = (int)$_POST['exam_id'];
    $marks = (float)$_POST['marks'];

    $sql = "INSERT INTO grades (student_id, exam_id, marks_obtained)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE marks_obtained = VALUES(marks_obtained)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iid", $sid, $eid, $marks);

    if ($stmt->execute()) {
        header("Location: viewgrades.php?student_id=$sid");
        exit;
    } else {
        $message = "❌ Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Grade</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            color:rgb(78, 91, 104);
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
            color: #34495e;
        }

        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            transition: border-color 0.3s ease;
        }

        input[type="number"]:focus,
        select:focus {
            border-color: #3498db;
            outline: none;
        }

        input[type="submit"] {
            margin-top: 25px;
            background-color: #27ae60;
            border: none;
            color: white;
            padding: 12px;
            width: 100%;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #1e8449;
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
    <h2>Add Grade</h2>

    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <form method="post">
        <label for="student_id">Student ID:</label>
        <input type="number" name="student_id" id="student_id" required>

        <label for="exam_id">Select Exam:</label>
        <select name="exam_id" id="exam_id" required>
            <option value="">-- Select Exam --</option>
            <?php
            $result = $conn->query("SELECT exam_id, exam_name, exam_date FROM exams ORDER BY exam_date DESC");
            while ($row = $result->fetch_assoc()) {
                $examDisplay = htmlspecialchars($row['exam_name']) . " ({$row['exam_date']})";
                echo "<option value='{$row['exam_id']}'>{$examDisplay}</option>";
            }
            ?>
        </select>

        <label for="marks">Marks Obtained:</label>
        <input type="number" name="marks" id="marks" required>

        <input type="submit" value="Save">
    </form>
</div>

</body>
</html>
