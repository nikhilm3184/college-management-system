<?php
session_start();
require("database.php");

$userStatus = $_SESSION['user_status'] ?? 0;

if ($userStatus == 1 && isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: viewannouncement.php?msg=deleted");
    exit;
}

$sql = "SELECT * FROM announcements ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>View Announcements</title>
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
        a.button {
            display: inline-block;
            background-color: #2980b9;
            color: white;
            padding: 10px 16px;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
        }
        a.button:hover {
            background-color: #3498db;
        }
        .announcement {
            background-color: white;
            border: 1px solid #ddd;
            padding: 15px 20px;
            margin-bottom: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .announcement h3 {
            margin-top: 0;
            color: #34495e;
        }
        .announcement small {
            color: #7f8c8d;
        }
        .announcement p {
            white-space: pre-line;
            line-height: 1.5;
            margin: 15px 0;
        }
        .delete-link {
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.2s ease;
        }
        .delete-link:hover {
            color: #c0392b;
        }
        .message {
            padding: 10px 15px;
            background-color: #2ecc71;
            color: white;
            border-radius: 4px;
            margin-bottom: 20px;
            width: fit-content;
        }
        
        .bottom-buttons {
            margin-top: 30px;
            text-align: center;
        }
        a.home-button {
            background-color: #27ae60;
            color: white;
            padding: 12px 24px;
            font-size: 1.1rem;
            text-decoration: none;
            border-radius: 6px;
            box-shadow: 0 4px 8px rgba(39, 174, 96, 0.4);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            display: inline-block;
        }
        a.home-button:hover {
            background-color: #2ecc71;
            box-shadow: 0 6px 12px rgba(46, 204, 113, 0.6);
        }
    </style>
</head>
<body>

    <h2>Announcements</h2>

    <?php if ($userStatus == 1): ?>
        <a class="button" href="addannouncement.php">Add New Announcement</a><br><br>
    <?php endif; ?>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="message">Announcement deleted successfully.</div>
    <?php endif; ?>

    <?php
    if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
    ?>
            <div class="announcement">
                <h3><?= htmlspecialchars($row['title']) ?></h3>
                <small>Posted on: <?= htmlspecialchars($row['created_at']) ?></small><br><br>
                <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                <?php if ($userStatus == 1): ?>
                    <a class="delete-link" href="viewannouncement.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this announcement?');">Delete</a>
                <?php endif; ?>
            </div>
    <?php
        endwhile;
    else:
        echo "<p>No announcements found.</p>";
    endif;

    $conn->close();
    ?>

    <div class="bottom-buttons">
        <a class="home-button" href="homepage.php">Back to Home Page</a>
    </div>

</body>
</html>
