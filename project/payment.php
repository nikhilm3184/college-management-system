<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require("database.php");

if (!isset($_SESSION['student_data'])) {
    header("Location: joiningstudent.php");
    exit();
}

$errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pay_now'])) {
    $data = $_SESSION['student_data'];
    $name = $data['name'];
    $age = $data['age'];
    $email = $data['email'];
    $course = $data['course'];
    $gender = $data['gender'];

    $payment_id = uniqid("pay_");
    $created_at = date("Y-m-d H:i:s");

    $paymentMethod = $_POST['payment_method'] ?? '';
    $cardNumber = $_POST['card_number'] ?? '';
    $cardHolder = $_POST['card_holder'] ?? '';
    $expiryDate = $_POST['expiry_date'] ?? '';
    $cvv = $_POST['cvv'] ?? '';

    if (!in_array($paymentMethod, ['debit', 'credit'])) {
        $errorMsg = "❌ Please choose Debit or Credit card.";
    } elseif (empty($cardNumber) || empty($cardHolder) || empty($expiryDate) || empty($cvv)) {
        $errorMsg = "❌ Please fill in all payment details.";
    } elseif (!preg_match("/^\d{16}$/", $cardNumber)) {
        $errorMsg = "❌ Invalid card number. Must be 16 digits.";
    } elseif (!preg_match("/^\d{3}$/", $cvv)) {
        $errorMsg = "❌ Invalid CVV. Must be 3 digits.";
    } elseif (!preg_match("/^(0[1-9]|1[0-2])\/\d{2}$/", $expiryDate)) {
        $errorMsg = "❌ Invalid expiry date format. Use MM/YY.";
    } else {
        
        $paymentSuccessful = true;

        if ($paymentSuccessful) {
            $sql = "INSERT INTO PendingStudents 
                (NAME, AGE, EMAIL, COURSE, GENDER, PAYMENT_STATUS, APPROVAL_STATUS, PAYMENT_ID, CREATED_AT)
                VALUES (?, ?, ?, ?, ?, 'done', 'pending', ?, ?)";

            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                $errorMsg = "❌ Prepare failed: " . $conn->error;
            } else {
                
                $stmt->bind_param("sisssss", $name, $age, $email, $course, $gender, $payment_id, $created_at);

                if ($stmt->execute()) {
                    unset($_SESSION['student_data']);
                    $stmt->close();
                    $conn->close();
                    header("Location: joiningstudent.php");
                    exit();
                } else {
                    $errorMsg = "❌ Database Error: " . $stmt->error;
                }
            }
        } else {
            $errorMsg = "❌ Payment failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Payment Page</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; padding: 30px; }
        .container { max-width: 450px; margin: auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input[type="text"], input[type="radio"] {
            width: 100%; padding: 8px; margin-top: 6px;
            border-radius: 4px; border: 1px solid #ccc; box-sizing: border-box;
        }
        .card-details { margin-top: 20px; }
        button {
            margin-top: 20px; width: 100%;
            padding: 10px; background: #28a745;
            color: white; font-size: 16px;
            border: none; border-radius: 4px;
            cursor: pointer;
        }
        button:hover { background: #218838; }
        .error {
            color: red; font-weight: bold;
            margin-top: 15px; text-align: center;
        }
        .fixed-amount {
            font-weight: bold;
            margin-top: 10px;
            font-size: 1.1em;
            text-align: center;
            color: #333;
        }
    </style>
    <script>
        function toggleCardDetails() {
            const method = document.querySelector('input[name="payment_method"]:checked');
            const cardDetails = document.getElementById('cardDetails');
            if (method && (method.value === 'debit' || method.value === 'credit')) {
                cardDetails.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
            }
        }

        window.onload = function() {
            toggleCardDetails(); 
            document.querySelectorAll('input[name="payment_method"]').forEach(el => {
                el.addEventListener('change', toggleCardDetails);
            });
        };
    </script>
</head>
<body>
<div class="container">
    <h2>Payment Page</h2>

    <div class="fixed-amount">Amount to Pay: <span style="color:#28a745;">$500</span></div>

    <form method="POST" action="">
        <label>Select Payment Method:</label>
        <label><input type="radio" name="payment_method" value="debit" required> Debit Card</label>
        <label><input type="radio" name="payment_method" value="credit"> Credit Card</label>

        <div id="cardDetails" class="card-details" style="display:none;">
            <label for="card_number">Card Number:</label>
            <input type="text" name="card_number" id="card_number" maxlength="16" placeholder="1234567812345678">

            <label for="card_holder">Card Holder Name:</label>
            <input type="text" name="card_holder" id="card_holder" placeholder="Full Name on Card">

            <label for="expiry_date">Expiry Date (MM/YY):</label>
            <input type="text" name="expiry_date" id="expiry_date" maxlength="5" placeholder="MM/YY">

            <label for="cvv">CVV:</label>
            <input type="text" name="cvv" id="cvv" maxlength="3" placeholder="123">
        </div>

        <button type="submit" name="pay_now">Pay Now</button>
    </form>

    <?php if ($errorMsg): ?>
        <div class="error"><?php echo htmlspecialchars($errorMsg); ?></div>
    <?php endif; ?>
</div>
</body>
</html>
