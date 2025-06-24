<?php
require("database.php");

$result = $conn->query("
    SELECT cs.day_of_week, cs.start_time, cs.end_time, 
           c.name AS course, e.name AS instructor
    FROM class_schedule cs
    JOIN Course c ON cs.course_id = c.id
    JOIN Employee e ON cs.instructor_id = e.id
    ORDER BY 
        FIELD(cs.day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'),
        cs.start_time
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Class Timetable</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef1f5;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin-top: 40px;
            color: #333;
        }

        table {
            margin: 30px auto;
            width: 90%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 14px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background: #007bff;
            color: white;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .back-container {
            text-align: center;
            margin: 30px 0 50px;
        }

        .back-button {
            display: inline-block;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 600;
            background: linear-gradient(135deg, #00b4db, #0083b0);
            color: white;
            border: none;
            border-radius: 30px;
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background: linear-gradient(135deg, #0083b0, #00b4db);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

    <h2>Weekly Class Timetable</h2>

    <table>
        <tr>
            <th>Day</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Course</th>
            <th>Instructor</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['day_of_week']) ?></td>
                <td><?= date("h:i A", strtotime($row['start_time'])) ?></td>
                <td><?= date("h:i A", strtotime($row['end_time'])) ?></td>
                <td><?= htmlspecialchars($row['course']) ?></td>
                <td><?= htmlspecialchars($row['instructor']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <div class="back-container">
        <a class="back-button" href="homepage.php">← Back to Homepage</a>
    </div>

</body>
</html>
