<?php
session_start();
require('database.php');

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function clean_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $username = clean_input($_POST['username'] ?? '');
    $email = clean_input($_POST['email'] ?? '');

    if (empty($username) || empty($email)) {
        $message = "Both fields are required.";
    } else {
        $stmt = $conn->prepare("SELECT EMAIL FROM User WHERE USERNAME = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (strcasecmp($user['EMAIL'], $email) === 0) {
                $_SESSION['reset_user'] = $username;
                header("Location: resetpwd.php");
                exit();
            } else {
                $message = "The email doesn't match our records.";
            }
        } else {
            $message = "Username not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify User</title>
    <style>
        body {
            background: linear-gradient(120deg, #4e54c8, #8f94fb);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 450px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #4e54c8;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .message {
            margin-top: 15px;
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Forgot Password</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Enter your username" required>
        <input type="email" name="email" placeholder="Enter your registered email" required>
        <button type="submit">Verify</button>
    </form>
    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>
</div>
</body>
</html>
