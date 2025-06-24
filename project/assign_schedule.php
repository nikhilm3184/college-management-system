<?php
require("database.php");

$courses = $conn->query("SELECT id, name FROM Course");
$instructors = $conn->query("SELECT id, name FROM Employee");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $course_id = $_POST['course_id'];
    $instructor_id = $_POST['instructor_id'];
    $day = $_POST['day'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $check = $conn->prepare("
        SELECT * FROM class_schedule 
        WHERE day_of_week = ? 
        AND (
            (start_time < ? AND end_time > ?)  
            OR (start_time < ? AND end_time > ?)
            OR (start_time >= ? AND end_time <= ?)
        )
        AND (instructor_id = ? OR course_id = ?)
    ");
    $check->bind_param("ssssssiii", $day, $end_time, $end_time, $start_time, $start_time, $start_time, $end_time, $instructor_id, $course_id);
    $check->execute();
    $conflicts = $check->get_result();

    if ($conflicts->num_rows > 0) {
        $message = "❌ Conflict detected! This time slot is already taken.";
    } else {
        $stmt = $conn->prepare("
            INSERT INTO class_schedule (course_id, instructor_id, day_of_week, start_time, end_time)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iisss", $course_id, $instructor_id, $day, $start_time, $end_time);
        if ($stmt->execute()) {
            header("Location: view_schedule.php?success=1");
            exit();
        } else {
            $message = "❌ Failed to assign schedule: " . $stmt->error;
        }
        $stmt->close();
    }

    $check->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assign Class Schedule</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }
        label, select, input {
            display: block;
            width: 100%;
            margin-top: 10px;
        }
        input[type="submit"] {
            margin-top: 20px;
            background: #007bff;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
        }
        .message {
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
            color: darkred;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Assign Class Schedule</h2>
        <form method="post">
            <label for="course">Select Course:</label>
            <select name="course_id" required>
                <option value="">-- Select Course --</option>
                <?php while($c = $courses->fetch_assoc()): ?>
                    <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label for="instructor">Select Instructor:</label>
            <select name="instructor_id" required>
                <option value="">-- Select Instructor --</option>
                <?php while($i = $instructors->fetch_assoc()): ?>
                    <option value="<?= $i['id'] ?>"><?= $i['name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label>Day of Week:</label>
            <select name="day" required>
                <option value="">-- Select Day --</option>
                <option>Monday</option>
                <option>Tuesday</option>
                <option>Wednesday</option>
                <option>Thursday</option>
                <option>Friday</option>
                <option>Saturday</option>
            </select>

            <label>Start Time:</label>
            <input type="time" name="start_time" required>

            <label>End Time:</label>
            <input type="time" name="end_time" required>

            <input type="submit" value="Assign Schedule">
        </form>

        <?php if (!empty($message)): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
