<?php
session_start();
require('database.php');

if (!isset($_SESSION['user_status']) && $_SESSION['user_status'] != 1) {
    header("Location: loginpage.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['department'])) {
    $department = htmlspecialchars($_POST['department']);
    header("Location: employeefilter.php?department=" . urlencode($department));
    exit();
}

$departments = [];
$result = $conn->query("SELECT DISTINCT department FROM Employee");
while ($row = $result->fetch_assoc()) {
    $departments[] = $row['department'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employees Page</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #f0f4ff, #e0f7f4);
            padding: 40px;
        }

        .result-container {
            max-width: 1100px;
            margin: auto;
            background: #ffffff;
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

        h3 {
            color: #3a3f5c;
            margin: 0;
        }

        .actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .add-btn, .filter-toggle-btn {
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

        .add-btn:hover, .filter-toggle-btn:hover {
            background-color: #074f8d;
        }

        .filter-form {
            display: none;
            margin-top: 15px;
            gap: 10px;
            align-items: center;
        }

        .filter-form.active {
            display: flex;
        }

        .filter-form label {
            font-weight: bold;
        }

        .filter-form select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .filter-form button {
            background-color: #00b894;
            color: white;
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .filter-form button:hover {
            background-color: #019270;
        }

        .result-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        .result-table th,
        .result-table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .result-table th {
            background-color: #6c5ce7;
            color: white;
        }

        .result-table tr:nth-child(even) {
            background-color: #f9f9ff;
        }

        .result-table tr:hover {
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
</head>
<body>
    
        <div class="header">
            <h3>Employees</h3>
            <div class="actions">
                <?php if ($_SESSION['user_status'] == 1): ?>
                                    <a class="add-btn" href="addemployee.php">+ Add Employee</a>

                    <button type="button" class="filter-toggle-btn" onclick="toggleFilter()"> Filter</button>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($_SESSION['status'] == 1): ?>
        <form method="post" class="filter-form" id="filterForm">
            <label for="department">Department:</label>
            <select name="department" id="department" required>
                <option value="">Select Department</option>
                <?php foreach ($departments as $dept): ?>
                    <option value="<?= htmlspecialchars($dept) ?>"><?= htmlspecialchars($dept) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Apply</button>
        </form>
        <?php endif; ?>

        <table class="result-table">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Department</th>
                <?php if ($_SESSION['user_status'] == 1): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM Employee");
            while ($row = $result->fetch_assoc()):
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['age']) ?></td>
                    <td><?= htmlspecialchars($row['department']) ?></td>
                    <?php if ($_SESSION['user_status'] == 1): ?>
                        <td class="action-buttons">
                            <a class="edit-btn" href="editemployee.php?id=<?= urlencode($row['id']) ?>">Edit</a>
                            <a class="delete-btn" href="deleteemployee.php?id=<?= urlencode($row['id']) ?>"
                               onclick="return confirm('Are you sure you want to delete this employee?');">Delete</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </table>

        <div class="back-link">
            <a href="homepage.php">← Back to Home Page</a>
        </div>
    

    <script>
        function toggleFilter() {
            const form = document.getElementById('filterForm');
            form.classList.toggle('active');
        }
    </script>
</body>
</html>
