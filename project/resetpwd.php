<?php
session_start();
require('database.php');

$message = "";

if (!isset($_SESSION['reset_user'])) {
    header("Location: forgotpwd.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = htmlspecialchars(trim($_POST['newPassword']));
    $confirmPassword = htmlspecialchars(trim($_POST['confirmPassword']));

    if (empty($newPassword) || empty($confirmPassword)) {
        $message = "Please fill out both password fields.";
    } elseif ($newPassword !== $confirmPassword) {
        $message = "Passwords do not match.";
    } elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*#?&]{8,}$/', $newPassword)) {
        $message = "Password must be at least 8 characters, contain letters and numbers.";
    } else {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $username = $_SESSION['reset_user'];

        $stmt = $conn->prepare("UPDATE User SET PASSWORD = ? WHERE USERNAME = ?");
        $stmt->bind_param("ss", $hashed, $username);
        if ($stmt->execute()) {
            unset($_SESSION['reset_user']);
            $message = "Password reset successfully! <a href='loginpage.php'>Login now</a>";
        } else {
            $message = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body {
            background: linear-gradient(120deg, #8f94fb, #4e54c8);
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
        input[type="password"] {
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
            color: green;
            text-align: center;
        }
        .message.error {
            color: red;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Reset Your Password</h2>
    <form method="POST">
        <input type="password" name="newPassword" placeholder="New Password" required>
        <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
        <button type="submit">Reset Password</button>
    </form>
    <?php if ($message): ?>
        <div class="message <?= strpos($message, 'successfully') === false ? 'error' : '' ?>"><?= $message ?></div>
    <?php endif; ?>
</div>
</body>
</html>
