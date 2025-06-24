<?php
require('database.php');
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sid = (int)$_POST['student_id'];
    $eid = (int)$_POST['exam_id'];
    $marks = (float)$_POST['marks'];

    $sql = "INSERT INTO grades (student_id, exam_id, marks_obtained)
            VALUES ($sid, $eid, $marks)
            ON DUPLICATE KEY UPDATE marks_obtained = $marks";

    if ($conn->query($sql)) {
        header("Location: viewgrades.php?student_id=$sid");
        exit;
    } else {
        $message = "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enter Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6fbfc;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            margin: 40px auto;
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

        input[type="number"],
        select {
            width: 100%;
            padding: 8px 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        input[type="submit"] {
            margin-top: 20px;
            background-color: #27ae60;
            border: none;
            color: white;
            padding: 12px;
            width: 100%;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
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
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Enter Student Result</h2>

    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="student_id">Student ID:</label>
        <input type="number" id="student_id" name="student_id" required>

        <label for="exam_id">Select Exam:</label>
        <select id="exam_id" name="exam_id" required>
            <option value="">-- Select Exam --</option>
            <?php
            $result = $conn->query("SELECT exam_id, exam_name, exam_date FROM exams ORDER BY exam_date DESC");
            while ($row = $result->fetch_assoc()) {
                $examDisplay = htmlspecialchars($row['exam_name']) . " (" . $row['exam_date'] . ")";
                echo "<option value='{$row['exam_id']}'>{$examDisplay}</option>";
            }
            ?>
        </select>

        <label for="marks">Marks Obtained:</label>
        <input type="number" step="0.01" id="marks" name="marks" required>

        <input type="submit" value="Save Result & View">
    </form>
</div>

</body>
</html>
