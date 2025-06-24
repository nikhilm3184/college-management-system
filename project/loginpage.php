<?php
session_start();
require('database.php');

$username = $password = "";
$usernameErr = $passwordErr = $message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function clean_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $username = clean_input($_POST['username'] ?? '');
    $password = clean_input($_POST['password'] ?? '');

    if (empty($username)) {
        $usernameErr = "Username is required";
    }

    if (empty($password)) {
        $passwordErr = "Password is required";
    }

    if (empty($usernameErr) && empty($passwordErr)) {
        $stmt = $conn->prepare("SELECT ID, PASSWORD, STATUS, IS_AUTHORIZED FROM User WHERE USERNAME = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            if (password_verify($password, $data['PASSWORD'])) {
                if ($data['IS_AUTHORIZED'] == 1) {
                    $_SESSION['LOGINSTATUS'] = true;
                    $_SESSION['user-id'] = $data['ID'];
                    $_SESSION['username'] = $username;
                    $_SESSION['user_status'] = $data['STATUS']; 

                    header("Location: mainpage.php");
                    exit();
                } else {
                    $message = "You don't have authority to login.";
                }
            } else {
                $message = "Invalid username or password.";
            }
        } else {
            $message = "Invalid username or password.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <style>
        body, html {
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url("https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTpFVvwhxfvkzTfUFXW_VAHClVU3WhutftVlQ&s");
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(3px);
        }

        form {
            background: rgba(0, 0, 0, 0.64);
            padding: 25px;
            border-radius: 10px;
            width: 350px;
        }

        h2 {
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: none;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        input::placeholder {
            color: #eee;
        }

        .login-btn {
            background: linear-gradient(to right, #8f94fb, #4e54c8);
            border: none;
            color: white;
            padding: 12px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-btn:hover {
            background: linear-gradient(to right, #4e54c8, #8f94fb);
        }

        .signup-link {
            margin-top: 15px;
            color: #fff;
            text-align: center;
        }

        .signup-link a {
            color: #8f94fb;
            text-decoration: none;
        }

        .error-message {
            color: #ffb3b3;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Login</h2>

        <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($username) ?>" />
        <?php if ($usernameErr): ?><div class="error-message"><?= $usernameErr ?></div><?php endif; ?>

        <input type="password" name="password" placeholder="Password" />
        <?php if ($passwordErr): ?><div class="error-message"><?= $passwordErr ?></div><?php endif; ?>

        <button type="submit" class="login-btn">Login</button>

        <?php if (!empty($message)): ?>
            <div class="error-message"><?= $message ?></div>
        <?php endif; ?>

        <div class="signup-link">
            Don't have an account? <a href="signup.php">Sign up</a><br>
            <a href="forgotpwd.php">Forgot password?</a>
        </div>
    </form>
</body>
</html>
