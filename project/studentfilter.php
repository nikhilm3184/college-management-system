<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

session_start();
if ($_SESSION['LOGINSTATUS'] != TRUE) {
    header('Location: login.php');
    exit();
}

require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course'])) {
    $course = $_POST['course'];

    $stmt = $conn->prepare("SELECT * FROM Student WHERE course = ?");
    $stmt->bind_param("s", $course);
    $stmt->execute();
    $result = $stmt->get_result();

    
    if (isset($_POST['download_csv']) && $result->num_rows > 0) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="students_' . urlencode($course) . '.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Name', 'Age', 'Email', 'Course', 'Gender']);

        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row['ID'],
                $row['NAME'],
                $row['AGE'],
                $row['EMAIL'],
                $row['COURSE'],
                $row['GENDER']
            ]);
        }

        fclose($output);
        $stmt->close();
        $conn->close();
        exit();
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Students in <?= htmlspecialchars($course) ?></title>
        <style>
            body {
                font-family: 'Segoe UI', sans-serif;
                background-color: #f0f2f5;
                padding: 30px;
            }

            h1 {
                color: #333;
                margin-bottom: 20px;
            }

            .download-form {
                margin-bottom: 20px;
            }

            .download-form button {
                background-color: #007BFF;
                color: white;
                padding: 10px 25px;
                font-size: 16px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }

            .download-form button:hover {
                background-color: #0056b3;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                background-color: white;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            th, td {
                padding: 12px 15px;
                border-bottom: 1px solid #ddd;
                text-align: left;
            }

            th {
                background-color: #343a40;
                color: white;
            }

            tr:hover {
                background-color: #f1f1f1;
            }

            .back-link {
                display: inline-block;
                margin-top: 25px;
                color: #28a745;
                text-decoration: none;
            }

            .back-link:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>

    <h1>Students from <?= htmlspecialchars($course) ?></h1>

    <?php if ($result && $result->num_rows > 0): ?>
        <form class="download-form" method="post">
            <input type="hidden" name="course" value="<?= htmlentities($course) ?>">
            <input type="hidden" name="download_csv" value="1">
            <button type="submit">Download CSV</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Gender</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['ID']) ?></td>
                        <td><?= htmlspecialchars($row['NAME']) ?></td>
                        <td><?= htmlspecialchars($row['AGE']) ?></td>
                        <td><?= htmlspecialchars($row['EMAIL']) ?></td>
                        <td><?= htmlspecialchars($row['COURSE']) ?></td>
                        <td><?= htmlspecialchars($row['GENDER']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No students found in this course.</p>
    <?php endif; ?>

    <a href="students.php" class="back-link">Back to Students Page</a>
    </body>
    </html>

    <?php
    $stmt->close();
    $conn->close();
    exit(); 
} else {
    header("Location: students.php");
    exit();
}
?>
