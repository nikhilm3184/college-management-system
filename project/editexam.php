<?php
session_start();
require('database.php');


if (!isset($_SESSION['user_status']) || $_SESSION['user_status'] != 1) {
    header('Location: exams_list.php');
    exit;
}


$exam_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;


$stmt = $conn->prepare("SELECT * FROM exams WHERE exam_id = ?");
$stmt->bind_param("i", $exam_id);
$stmt->execute();
$result = $stmt->get_result();
$exam = $result->fetch_assoc();

if (!$exam) {
    $_SESSION['exam_success'] = "Exam not found.";
    header("Location: exams_list.php");
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['exam_name']);
    $date = $_POST['exam_date'];

    if (empty($name) || empty($date)) {
        $error = "All fields are required.";
    } else {
        $update = $conn->prepare("UPDATE exams SET exam_name = ?, exam_date = ? WHERE exam_id = ?");
        $update->bind_param("ssi", $name, $date, $exam_id);

        if ($update->execute()) {
            $_SESSION['exam_success'] = "Exam updated successfully.";
            header("Location: examlist.php");
            exit;
        } else {
            $error = "Update failed: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Exam</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f7;
            padding: 40px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            background-color: #27ae60;
            color: white;
            padding: 10px;
            border: none;
            margin-top: 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .error {
            padding: 10px;
            background: #fdecea;
            border-left: 5px solid #e74c3c;
            color: #c0392b;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .back {
            margin-top: 20px;
            text-align: center;
        }

        .back a {
            color: #2980b9;
            text-decoration: none;
        }

        .back a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Exam</h2>

    <?php if (!empty($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post">
        <label for="exam_name">Exam Name:</label>
        <input type="text" id="exam_name" name="exam_name" value="<?php echo htmlspecialchars($exam['exam_name']); ?>" required>

        <label for="exam_date">Exam Date:</label>
        <input type="date" id="exam_date" name="exam_date" value="<?php echo htmlspecialchars($exam['exam_date']); ?>" required>

        <button type="submit" class="btn">Update Exam</button>
    </form>

    <div class="back">
        <a href="examlist.php">← Back to Exam List</a>
    </div>
</div>

</body>
</html>
