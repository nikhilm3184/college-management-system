<?php
session_start();
require('database.php');

$result = $conn->query("SELECT * FROM Student");

$coursesResult = $conn->query("SELECT DISTINCT COURSE FROM Student");
$courses = [];
while ($courseRow = $coursesResult->fetch_assoc()) {
    $courses[] = $courseRow['COURSE'];
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Students</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #f0f4ff, #e0f7f4);
            padding: 40px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        h2 {
            color: #2d3436;
            margin: 0;
        }

        .buttons-group {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 10px;
        }

        .add-btn,
        .filter-btn,
        .apply-btn {
            background-color: #0984e3;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .add-btn:hover,
        .filter-btn:hover,
        .apply-btn:hover {
            background-color: #0652dd;
        }

        .filter-form {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-form select {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #6c5ce7;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9ff;
        }

        table tr:hover {
            background-color: #eef1f9;
        }

        .action-buttons a {
            text-decoration: none;
            padding: 6px 12px;
            margin-right: 5px;
            border-radius: 4px;
            font-size: 14px;
            color: white;
        }

        .edit-btn {
            background-color: #00cec9;
        }

        .edit-btn:hover {
            background-color: #00b8b0;
        }

        .delete-btn {
            background-color: #d63031;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        .result-btn {
            background-color: #6c5ce7;
        }

        .result-btn:hover {
            background-color: #5e50d6;
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
            transition: background-color 0.3s ease;
        }

        .back-link a:hover {
            background-color: #2c3e50;
        }
    </style>

    <script>
        function toggleFilterForm() {
            const form = document.getElementById("filter-form");
            form.style.display = form.style.display === "flex" ? "none" : "flex";
        }
    </script>
</head>

<body>

    <div class="header">
        <h2>Students</h2>
        <?php if (isset($_SESSION['user_status']) && $_SESSION['user_status'] == 1): ?>
            <div class="buttons-group">
                <a class="add-btn" href="addstudent.php">+ Add Student</a>
                <button class="filter-btn" onclick="toggleFilterForm()">Filter</button>
            </div>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['user_status']) && $_SESSION['user_status'] == 1): ?>
        <form id="filter-form" class="filter-form" action="studentfilter.php" method="POST" style="display: none;">
            <label for="course">Course:</label>
            <select name="course" id="course" required>
                <option value="" disabled selected>Select Course</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo htmlspecialchars($course); ?>"><?php echo htmlspecialchars($course); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="apply-btn">Apply</button>
        </form>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Email</th>
            <th>Course</th>
            <th>Gender</th>
            <?php if (isset($_SESSION['user_status']) && $_SESSION['user_status'] == 1): ?>
                <th>Actions</th>
            <?php endif; ?>
            <th>Result</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['ID']); ?></td>
                <td><?php echo htmlspecialchars($row['NAME']); ?></td>
                <td><?php echo htmlspecialchars($row['AGE']); ?></td>
                <td><?php echo htmlspecialchars($row['EMAIL']); ?></td>
                <td><?php echo htmlspecialchars($row['COURSE']); ?></td>
                <td><?php echo htmlspecialchars($row['GENDER']); ?></td>

                <?php if (isset($_SESSION['user_status']) && $_SESSION['user_status'] == 1): ?>
                    <td class="action-buttons">
                        <a class="edit-btn" href="editstudent.php?id=<?php echo urlencode($row['ID']); ?>">Edit</a>
                        <a class="delete-btn" href="deletestudent.php?id=<?php echo urlencode($row['ID']); ?>" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                    </td>
                <?php endif; ?>

                <td>
                    <a class="result-btn" href="viewrslts.php?student_id=<?php echo urlencode($row['ID']); ?>">Result</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <div class="back-link">
        <a href="homepage.php">← Back to Home Page</a>
    </div>

</body>

</html>
