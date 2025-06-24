<?php
require('database.php');
session_start();

if (!isset($_GET['student_id'])) {
    die("Student ID not provided.");
}

$student_id = (int)$_GET['student_id'];


$user_id = $_SESSION['user_id'] ?? null;
$user_status = $_SESSION['user_status'] ?? null;

if (!$user_status && $user_id) {
    $stmt = $conn->prepare("SELECT status FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($user_status);
    $stmt->fetch();
    $stmt->close();

    
    $_SESSION['user_status'] = $user_status;
}


$sql = "SELECT g.grade_id, g.marks_obtained, e.exam_name, e.exam_date
        FROM grades g
        JOIN exams e ON g.exam_id = e.exam_id
        WHERE g.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef6f9;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            background:whitesmoke;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #2980b9;
            color: white;
        }

        .btn {
            padding: 6px 12px;
            margin-right: 5px;
            text-decoration: none;
            color: white;
            border-radius: 4px;
            font-size: 14px;
        }

        .edit-btn {
            background-color: #f39c12;
        }

        .delete-btn {
            background-color: #c0392b;
        }

        .add-btn {
            background-color: #27ae60;
            display: inline-block;
            margin-bottom: 15px;
        }

        .back-btn {
            background-color: #34495e;
            display: inline-block;
            margin-top: 20px;
            padding: 10px 16px;
        }

        .center {
            text-align: center;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Results for Student ID: <?= htmlspecialchars($student_id) ?></h2>

        <?php if ($user_status == 1): ?>
            <a href="addgrade.php" class="btn add-btn">➕ Add Grade</a>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Exam Name</th>
                    <th>Exam Date</th>
                    <th>Marks Obtained</th>
                                        <?php if (isset($_SESSION['user_status']) && $_SESSION['user_status'] == 1): ?>

                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['exam_name']) ?></td>
                        <td><?= htmlspecialchars($row['exam_date']) ?></td>
                        <td><?= htmlspecialchars($row['marks_obtained']) ?></td>
                        <?php if ($user_status == 1): ?>
                            <td>
                                <a class="btn edit-btn" href="editgrade.php?grade_id=<?= $row['grade_id'] ?>">✏️ Edit</a>
                                <a class="btn delete-btn" href="deletegrade.php?grade_id=<?= $row['grade_id'] ?>" onclick="return confirm('Are you sure you want to delete this result?')">🗑️ Delete</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="center">
            <a href="homepage.php" class="btn back-btn">← Back to Home Page</a>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
