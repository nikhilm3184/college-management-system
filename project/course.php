<?php
session_start();
require('database.php');


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['status']) && $_SESSION['user_status'] == 1) {
    $id = htmlspecialchars(trim($_POST['course_id']));
    $name = htmlspecialchars(trim($_POST['course_name']));
    $fees = htmlspecialchars(trim($_POST['course_fees']));
    $duration = htmlspecialchars(trim($_POST['course_duration']));

    $stmt = $conn->prepare("INSERT INTO Course (id, name, fees, duration) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $id, $name, $fees, $duration);
    $stmt->execute();
    $stmt->close();
}


$result = $conn->query("SELECT * FROM Course");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Management</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #ece9e6, #ffffff);
            padding: 30px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        h2 {
            color: #2c3e50;
            margin: 0;
        
        }

        .add-btn {
            background-color: #27ae60;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .add-btn:hover {
            background-color: #219150;
        }

        .result-table {
            width: 100%;
            border-collapse: collapse;
        }

        .result-table th,
        .result-table td {
            padding: 12px 15px;
            border: 1px solid #ccc;
            text-align: left;
        }

        .result-table th {
            background-color: #3498db;
            color: white;
        }

        .result-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .action-buttons a {
            text-decoration: none;
            padding: 6px 12px;
            margin-right: 5px;
            border-radius: 4px;
            font-size: 14px;
        }

        .edit-btn {
            background-color: #f39c12;
            color: white;
        }

        .edit-btn:hover {
            background-color: #e67e22;
        }

        .delete-btn {
            background-color: #e74c3c;
            color: white;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        .back-link {
            margin-top: 40px;
            text-align: center;
        }

        .back-link a {
            display: inline-block;
            text-decoration: none;
            background-color: #34495e;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
        }

        .back-link a:hover {
            background-color: #2c3e50;
        }
    </style>
</head>
<body>

        <div class="top-bar">
            <h2>Available Courses</h2>
            <?php if (isset($_SESSION['user_status']) && $_SESSION['user_status'] == 1): ?>
                <a href="addcourse.php" class="add-btn"> Add Course</a>
            <?php endif; ?>
        </div>

        <table class="result-table">
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Fees</th>
                <th>Duration(In Years)</th>
                <?php if (isset($_SESSION['user_status']) && $_SESSION['user_status'] == 1): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>

            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['fees']); ?></td>
                    <td><?php echo htmlspecialchars($row['duration']); ?></td>
                    <?php if (isset($_SESSION['user_status']) && $_SESSION['user_status'] == 1): ?>
                        <td class="action-buttons">
                            <a class="edit-btn" href="editcourse.php?id=<?php echo urlencode($row['id']); ?>">Edit</a>
                            <a class="delete-btn" href="deletecourse.php?id=<?php echo urlencode($row['id']); ?>"
                               onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </table>

        <div class="back-link">
            <a href="homepage.php">← Back to Home Page</a>
        </div>


</body>
</html>

<?php $conn->close(); ?>
