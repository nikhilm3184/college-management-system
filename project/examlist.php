<?php
session_start();
require('database.php');



$successMsg = $_SESSION['exam_success'] ?? "";
unset($_SESSION['exam_success']);


$exams = $conn->query("SELECT * FROM exams ");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Exams List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f7;
            padding: 40px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .success {
            padding: 10px;
            background: #e0f7e9;
            border-left: 5px solid #27ae60;
            color: #2c7a4b;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        table th {
            background-color: #3498db;
            color: white;
        }

        .btn {
            text-decoration: none;
            padding: 6px 12px;
            margin: 2px;
            font-size: 14px;
            border-radius: 5px;
            color: white;
            display: inline-block;
        }

        .edit-btn {
            background-color: #27ae60;
        }

        .delete-btn {
            background-color: #e74c3c;
        }

        .add-btn {
            background-color: #2980b9;
            margin-bottom: 20px;
        }

        .back-btn {
            margin-top: 30px;
            background-color: #34495e;
            text-align: center;
            font-weight: bold;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .center {
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Exam List</h2>

        <?php if ($successMsg): ?>
            <div class="success"><?php echo $successMsg; ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_status']) && $_SESSION['user_status'] == 1): ?>
            <a href="addexm.php" class="add-btn"> Add Exam</a>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Exam ID</th>
                    <th>Exam Name</th>
                    <th>Exam Date</th>
                    <?php if (isset($_SESSION['user_status']) && $_SESSION['user_status'] == 1): ?>

                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($exam = $exams->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $exam['exam_id']; ?></td>
                        <td><?php echo htmlspecialchars($exam['exam_name']); ?></td>
                        <td><?php echo $exam['exam_date']; ?></td>
                        <?php if (isset($_SESSION['user_status']) && $_SESSION['user_status'] == 1): ?>

                            <td>
                                <a class="btn edit-btn" href="editexam.php?id=<?php echo $exam['exam_id']; ?>">Edit</a>
                                <a class="btn delete-btn" href="deleteexam.php?id=<?php echo $exam['exam_id']; ?>" onclick="return confirm('Delete this exam?');">Delete</a>
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