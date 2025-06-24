<?php
session_start();
require('database.php');

if (!isset($_SESSION['status']) || $_SESSION['status'] != 1) {
    header("Location: loginpage.php");
    exit();
}

if (isset($_GET['department'])) {
    $department = htmlspecialchars($_GET['department']);
    $stmt = $conn->prepare("SELECT * FROM Employee WHERE department = ?");
    $stmt->bind_param("s", $department);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    header("Location: employees.php");
    exit();
}

if (isset($_POST['download_csv'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="employees_' . urlencode($department) . '.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Name', 'Age', 'Department']);
    
    $stmt->execute();
    $download_result = $stmt->get_result();
    while ($row = $download_result->fetch_assoc()) {
        fputcsv($output, [$row['id'], $row['name'], $row['age'], $row['department']]);
    }
    fclose($output);
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employees in <?= htmlspecialchars($department) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #f0f4ff, #e0f7f4);
            padding: 40px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 25px;
        }

        form {
            text-align: right;
            margin-bottom: 20px;
        }

        button[name="download_csv"] {
            background-color: #00b894;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
        }

        button[name="download_csv"]:hover {
            background-color: #019270;
        }

        .result-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
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

        .back-link {
            margin-top: 30px;
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
    
        <h1>Employees in <?= htmlspecialchars($department) ?></h1>

        <form method="post">
            <button type="submit" name="download_csv"> Download</button>
        </form>

        <table class="result-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Department</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['age']) ?></td>
                        <td><?= htmlspecialchars($row['department']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="back-link">
            <a href="employees.php">← Back to Employees Page</a>
        </div>
    
</body>
</html>
