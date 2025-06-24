<?php
require("database.php");
session_start();


if (!isset($_SESSION['LOGINSTATUS']) || $_SESSION['LOGINSTATUS'] !== TRUE) {
    header('Location: admin.php'); 
    exit();
}


if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
} elseif (isset($_POST['user_id'])) {
    $id = intval($_POST['user_id']);
} else {
    die("No user ID provided.");
}


$sql = "SELECT * FROM User WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['edit'])) {
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($password)) {
        
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE User SET email=?, username=?, password=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $email, $username, $passwordHash, $id);
    } else {
        
        $sql = "UPDATE User SET email=?, username=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $email, $username, $id);
    }

    if ($stmt->execute()) {
        header("Location: admin.php");  
        exit;
    } else {
        echo "Update failed: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 25px;
            max-width: 400px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 6px;
            box-sizing: border-box;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
            background: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        a.back {
            display: block;
            margin-top: 15px;
            text-align: center;
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit User</h2>
    <form method="POST" action="">
        
        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['ID']) ?>" />

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['EMAIL']) ?>" required />

        <label>Username:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['USERNAME']) ?>" required />

        <label>Password: <small>(leave blank to keep current password)</small></label>
        <input type="password" name="password" />

        <button type="submit" name="edit" value="edit">Update User</button>
    </form>
    <a class="back" href="admin.php">← Back to User List</a>
</div>
</body>
</html>

<?php $conn->close(); ?>
