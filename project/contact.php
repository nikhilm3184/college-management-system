
<html>
<head>
  <title>Contact Us - College</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body, html {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to right, #2c3e50, #3498db);
      color: #fff;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 20px;
    }

    .contact-container {
      background: rgba(0, 0, 0, 0.75);
      padding: 40px;
      border-radius: 15px;
      max-width: 500px;
      width: 100%;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.6);
    }

    h1 {
      font-size: 2.5rem;
      margin-bottom: 20px;
      color: #f1f1f1;
    }

    p {
      font-size: 1.1rem;
      margin: 12px 0;
    }

    .main-btn {
      display: inline-block;
      margin-top: 25px;
      padding: 12px 24px;
      background-color: #00b894;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
      text-decoration: none;
      transition: background 0.3s ease;
    }

    .main-btn:hover {
      background-color: #019875;
    }

    @media (max-width: 600px) {
      h1 {
        font-size: 2rem;
      }

      p {
        font-size: 1rem;
      }

      .main-btn {
        width: 100%;
      }
    }
  </style>
</head>
<body>

  <div class="contact-container">
    <h1>Contact Us</h1>
    <p><strong>📞 Administration Office:</strong> +91 98765 43210</p>
    <p><strong>☎️ Landline Number:</strong> 040-12345678</p>
    <p><strong>📧 Email:</strong> contact@yourcollege.edu.in</p>
    <a href="mainpage.php" class="main-btn">Back to Main Page</a>
  </div>

</body>
</html>
