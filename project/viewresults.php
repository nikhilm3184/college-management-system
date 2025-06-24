<?php
require('database.php');

if (!isset($_GET['student_id']) || !is_numeric($_GET['student_id'])) {
    die("❌ Invalid or missing student ID.");
}

$student_id = (int)$_GET['student_id'];


$studentName = "Student #$student_id";
$nameQuery = $conn->query("SELECT NAME FROM Student WHERE ID = $student_id");
if ($nameRow = $nameQuery->fetch_assoc()) {
    $studentName = $nameRow['NAME'];
}


$stmt = $conn->prepare("
    SELECT e.exam_name, e.exam_date, g.marks_obtained
    FROM grades g
    INNER JOIN exams e ON g.exam_id = e.exam_id
    WHERE g.student_id = ?
    ORDER BY e.exam_date DESC
");
$stmt->bind_param('i', $student_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($exam_name, $exam_date, $marks);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Results for <?php echo htmlspecialchars($studentName); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fb;
            padding: 40px;
            max-width: 800px;
            margin: auto;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 30px;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f9ff;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            font-size: 18px;
            color: #777;
        }

        .back-link {
            margin-top: 30px;
            text-align: center;
        }

        .back-link a {
            text-decoration: none;
            background: #34495e;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
        }

        .back-link a:hover {
            background-color: #2c3e50;
        }
    </style>
</head>
<body>

<h2>Exam Results for <?php echo htmlspecialchars($studentName); ?></h2>

<?php if ($stmt->num_rows > 0): ?>
    <table>
        <tr>
            <th>Exam Name</th>
            <th>Exam Date</th>
            <th>Marks Obtained</th>
        </tr>
        <?php while ($stmt->fetch()): ?>
            <tr>
                <td><?php echo htmlspecialchars($exam_name); ?></td>
                <td><?php echo htmlspecialchars($exam_date); ?></td>
                <td><?php echo htmlspecialchars($marks); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <div class="no-data">No exam results found for this student.</div>
<?php endif; ?>

<div>
 