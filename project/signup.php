<?php
session_start();
require 'database.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$message = '';
$emailErr = $usernameErr = $passwordErr = '';
$email = $username = $password = '';

function test_input($data)
{
  return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = test_input($_POST["email"] ?? '');
  $username = test_input($_POST["username"] ?? '');
  $password = test_input($_POST["password"] ?? '');


  if (empty($email)) {
    $emailErr = "Email is required";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $emailErr = "Email is not valid";
  }


  if (empty($username)) {
    $usernameErr = "Username is required";
  } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $username)) {
    $usernameErr = "Only letters and white space allowed";
  }


  if (empty($password)) {
    $passwordErr = "Password is required";
  } elseif (!preg_match("/^[a-zA-Z0-9]*$/", $password)) {
    $passwordErr = "Only alphanumeric characters allowed";
  }

  if (empty($emailErr) && empty($usernameErr) && empty($passwordErr)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $stmt = $conn->prepare("SELECT * FROM User WHERE USERNAME = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $message = "Username already exists.";
    } else {
      $status = 0;
      $stmt = $conn->prepare("INSERT INTO User (EMAIL, USERNAME, PASSWORD, STATUS) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("sssi", $email, $username, $hashed_password, $status);

      if ($stmt->execute()) {
        header("Location: loginpage.php");
        exit();
      } else {
        $message = "Signup failed. Please try again.";
      }
      $stmt->close();
    }
    $stmt->close();
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Signup Page</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

    body,
    html {
      height: 100%;
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #667eea, #764ba2);
      display: flex;
      justify-content: center;
      align-items: center;
      color: #fff;
    }

    .signup-container {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 15px;
      box-shadow: 0 0 30px rgba(255, 255, 255, 0.2);
      width: 380px;
      padding: 40px 30px;
      backdrop-filter: blur(12px);
      position: relative;
      overflow: hidden;
    }

    .signup-container::before {
      content: "";
      position: absolute;
      top: -40%;
      left: -40%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle at center, rgba(255, 255, 255, 0.15), transparent 70%);
      animation: rotateBG 12s linear infinite;
      z-index: 0;
      border-radius: 50%;
    }

    @keyframes rotateBG {
      from {
        transform: rotate(0deg);
      }

      to {
        transform: rotate(360deg);
      }
    }

    h1 {
      text-align: center;
      margin-bottom: 35px;
      font-weight: 600;
      letter-spacing: 1.2px;
      position: relative;
      z-index: 1;
    }

    form {
      position: relative;
      z-index: 1;
    }

    .input-group {
      position: relative;
      margin-bottom: 30px;
    }

    input {
      width: 100%;
      padding: 14px 14px 14px 12px;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.15);
      color: #fff;
      outline: none;
      transition: background 0.3s ease;
      font-weight: 400;
      letter-spacing: 0.05em;
    }

    input:focus {
      background: rgba(255, 255, 255, 0.3);
    }

    label {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: rgba(255, 255, 255, 0.7);
      pointer-events: none;
      transition: all 0.3s ease;
      font-size: 16px;
      font-weight: 400;
      letter-spacing: 0.05em;
      user-select: none;
    }

    input:focus+label,
    input:not(:placeholder-shown)+label {
      top: 6px;
      left: 12px;
      font-size: 12px;
      color: #e0e0e0;
      background: rgba(0, 0, 0, 0.15);
      padding: 0 6px;
      border-radius: 4px;
    }

    .signup-btn {
      width: 100%;
      padding: 14px;
      border: none;
      border-radius: 10px;
      background: linear-gradient(135deg, #ff6a00, #ee0979);
      color: #fff;
      font-size: 18px;
      font-weight: 700;
      cursor: pointer;
      box-shadow: 0 6px 15px rgba(238, 9, 121, 0.6);
      transition: box-shadow 0.3s ease;
      letter-spacing: 0.1em;
    }

    .signup-btn:hover {
      box-shadow: 0 8px 25px rgba(238, 9, 121, 0.9);
      background: linear-gradient(135deg, #ee0979, #ff6a00);
    }

    .error-message {
      color: #ff6b6b;
      margin-bottom: 20px;
      text-align: center;
      font-weight: 600;
    }

    input:-webkit-autofill {
      box-shadow: 0 0 0 1000px rgba(255, 255, 255, 0.15) inset !important;
      -webkit-text-fill-color: #fff !important;
    }
  </style>
</head>

<body>

  <div class="signup-container">
    <h1>Create Account</h1>

    <?php if ($message): ?>
      <div class="error-message"><?= htmlspecialchars($message) ?><div>
    <?php endif; ?>

    <form  method="POST" autocomplete="off" novalidate>
      <div class="input-group">
        <input type="email" id="email" name="email" placeholder=" " value="<?= htmlspecialchars($email) ?>" required autocomplete="email" />
        <label for="email">Email Address</label>
        <?php if ($emailErr): ?>
          <div class="error-message"><?= htmlspecialchars($emailErr) ?></div>
        <?php endif; ?>
      </div>

      <div class="input-group">
        <input type="text" id="username" name="username" placeholder=" " value="<?= htmlspecialchars($username) ?>" required autocomplete="username" />
        <label for="username">Username</label>
        <?php if ($usernameErr): ?>
          <div class="error-message"><?= htmlspecialchars($usernameErr) ?></div>
        <?php endif; ?>
      </div>

      <div class="input-group">
        <input type="password" id="password" name="password" placeholder=" " required autocomplete="new-password" />
        <label for="password">Password</label>
        <?php if ($passwordErr): ?>
          <div class="error-message"><?= htmlspecialchars($passwordErr) ?></div>
        <?php endif; ?>
      </div>

      <button type="submit" class="signup-btn">Sign Up</button>
    </form>
  </div>

</body>

</html>