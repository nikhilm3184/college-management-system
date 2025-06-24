<?php
require("database.php");

$error = '';
$success = '';
$title = '';
$content = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if (empty($title) || empty($content)) {
        $error = "Please fill in both title and content.";
    } else {
        $stmt = $conn->prepare("INSERT INTO announcements (title, content) VALUES (?, ?)");
        if (!$stmt) {
            $error = "Prepare failed: " . $conn->error;
        } else {
            $stmt->bind_param("ss", $title, $content);
            if ($stmt->execute()) {
                $success = "Announcement added successfully!";
                
                $title = '';
                $content = '';
            } else {
                $error = "Error adding announcement: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add Announcement</title>
    <style>
        
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        form {
            background: white;
            padding: 20px;
            max-width: 600px;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #34495e;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
            resize: vertical;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus, textarea:focus {
            border-color: #2980b9;
            outline: none;
        }
        button {
            background-color: #2980b9;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #3498db;
        }
        a.button-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #7f8c8d;
            color: white;
            padding: 10px 16px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        a.button-link:hover {
            background-color: #95a5a6;
        }
        .message {
            max-width: 600px;
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .message.error {
            background-color: #e74c3c;
            color: white;
        }
        .message.success {
            background-color: #2ecc71;
            color: white;
        }
    </style>
</head>
<body>
    <h2>Add Announcement</h2>

    <?php if (!empty($error)): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="addannouncement.php">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required value="<?= htmlspecialchars($title) ?>">

        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="5" required><?= htmlspecialchars($content) ?></textarea>

        <button type="submit">Add Announcement</button>
    </form>

    <a class="button-link" href="viewannouncement.php">View Announcements</a>
</body>
</html>
