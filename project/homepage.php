<?php
session_start();


if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = ['STATUS' => 1]; 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>College Home Page</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

    * {
      box-sizing: border-box;
    }

    body,
    html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: 'Poppins', sans-serif;
      overflow: hidden;
      color: white;
    }

    body {
      background-image: url('https://images.unsplash.com/photo-1571260899304-425eee4c7efc?auto=format&fit=crop&w=1650&q=80');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      position: relative;
    }

    body::before {
      content: "";
      position: absolute;
      inset: 0;
      background-color: rgba(0, 0, 0, 0.6);
      z-index: 0;
    }

    .back {
      position: absolute;
      top: 20px;
      left: 20px;
      z-index: 2;
    }

    .back a {
      text-decoration: none;
      background: linear-gradient(135deg, #ff758c, #ff7eb3);
      padding: 10px 20px;
      color: white;
      font-weight: 600;
      font-size: 1rem;
      border-radius: 25px;
      box-shadow: 0 4px 12px rgba(255, 120, 150, 0.4);
      transition: background 0.3s ease, transform 0.2s ease;
    }

    .back a:hover {
      background: linear-gradient(135deg, #ff7eb3, #ff758c);
      transform: translateY(-2px);
      box-shadow: 0 6px 18px rgba(255, 120, 150, 0.6);
    }

    .main-content {
      position: relative;
      height: 100vh;
      display: flex;
      justify-content: flex-start;
      align-items: center;
      padding-left: 50px;
      z-index: 1;
    }

    .button-container {
      display: flex;
      flex-direction: column;
      gap: 25px;
    }

    .button-container button {
      background: linear-gradient(135deg, #8f94fb, #4e54c8);
      border: none;
      padding: 16px 28px;
      border-radius: 0 30px 30px 0;
      color: white;
      font-weight: 600;
      font-size: 1.1rem;
      cursor: pointer;
      box-shadow: 0 5px 15px rgba(79, 79, 191, 0.5);
      transition: background 0.3s ease, transform 0.2s ease;
      width: 180px;
      text-align: left;
    }

    .button-container button:hover {
      background: linear-gradient(135deg, #4e54c8, #8f94fb);
      transform: translateX(10px);
      box-shadow: 0 8px 25px rgba(79, 79, 191, 0.7);
    }

    
    .admin-buttons {
      position: absolute;
      top: 80px;
      left: 20px;
      z-index: 2;
      display: flex;
      gap: 15px;
    }

    .admin-btn {
      background: linear-gradient(135deg, #00c6ff, #0072ff);
      padding: 12px 24px;
      color: white;
      border-radius: 30px;
      font-size: 1rem;
      font-weight: 600;
      border: none;
      cursor: pointer;
      box-shadow: 0 4px 10px rgba(0, 114, 255, 0.4);
      transition: background 0.3s ease, transform 0.2s ease;
    }

    .admin-btn:hover {
      background: linear-gradient(135deg, #0072ff, #00c6ff);
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(0, 114, 255, 0.6);
    }

    @media (max-width: 768px) {
      .main-content {
        justify-content: center;
        padding-left: 0;
        padding-top: 80px;
      }

      .button-container {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
      }

      .button-container button {
        width: auto;
        border-radius: 30px;
        text-align: center;
        padding: 12px 20px;
      }

      .back a {
        font-size: 0.9rem;
        padding: 8px 16px;
      }

      .admin-buttons {
        position: relative;
        top: auto;
        left: 50%;
        transform: translateX(-50%);
        margin-top: 10px;
        flex-wrap: wrap;
        justify-content: center;
      }

      .admin-btn {
        margin-bottom: 10px;
      }
    }
  </style>
</head>

<body>

  
  <div class="back">
    <a href="mainpage.php">← Back to Main Page</a>
  </div>

  
  <?php
  if ($_SESSION['user_status'] == 1) {
    echo '
      <div class="admin-buttons">
        <a href="admin.php"><button class="admin-btn">ADMIN</button></a>
        <a href="assign_schedule.php"><button class="admin-btn">Schedule</button></a>
                <a href="createevnt.php"><button class="admin-btn">Exam</button></a>
                <a href="gradescdle.php"><button class="admin-btn">Marks</button></a>

      </div>
    ';
  }
  ?>

  
  <div class="main-content">
    <div class="button-container">
      <button onclick="window.location.href='course.php'">Courses</button>
      <button onclick="window.location.href='students.php'">Students</button>
      <button onclick="window.location.href='employees.php'">Employees</button>
      <button onclick="window.location.href='achievementspage.html'">Achievements</button>
      <button onclick="window.location.href='joiningstudent.php'">Joining Form</button>
      <button onclick="window.location.href='viewannouncement.php'">Announcement</button>

    </div>
  </div>

</body>

</html>
