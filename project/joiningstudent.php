<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = trim($_POST['name'] ?? '');
    $age = intval($_POST['age'] ?? 0);
    $email = trim($_POST['email'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $gender = trim($_POST['gender'] ?? '');

    if ($name && $age > 0 && $email && $course && $gender) {
        
        $_SESSION['student_data'] = [
            'name' => $name,
            'age' => $age,
            'email' => $email,
            'course' => $course,
            'gender' => $gender
        ];

        
        header("Location: payment.php");
        exit();
    } else {
        $errorMsg = "⚠️ Please fill in all required fields correctly.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Joining Form</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            margin: 0; padding: 0;
        }
        .container {
            width: 450px;
            background: #fff;
            margin: 80px auto;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        h2 { text-align: center; color: #333; }
        label { font-weight: bold; display: block; margin-top: 15px; }
        input[type="text"], input[type="number"], input[type="email"] {
            width: 100%; padding: 8px; margin-top: 5px;
            border: 1px solid #ccc; border-radius: 4px;
        }
        .gender-options { margin-top: 8px; }
        .gender-options label { font-weight: normal; margin-right: 15px; }
        input[type="radio"] { margin-right: 6px; }
        input[type="submit"] {
            margin-top: 20px; width: 100%; padding: 10px;
            background-color: #007BFF; border: none; color: white;
            font-size: 16px; border-radius: 4px; cursor: pointer;
        }
        input[type="submit"]:hover { background-color: #0056b3; }
        .error {
            color: red; margin-top: 10px; font-weight: bold; text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Student Joining Form</h2>
        <?php if (!empty($errorMsg)): ?>
            <div class="error"><?= htmlspecialchars($errorMsg) ?></div>
        <?php endif; ?>
        <form method="post" action="joiningstudent.php">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" min="1" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="course">Course:</label>
            <input type="text" id="course" name="course" required>

            <label>Gender:</label>
            <div class="gender-options">
                <label><input type="radio" name="gender" value="Male" required> Male</label>
                <label><input type="radio" name="gender" value="Female"> Female</label>
                <label><input type="radio" name="gender" value="Other"> Other</label>
            </div>

            <input type="submit" value="Proceed to Payment">
        </form>
    </div>
</body>
</html>
