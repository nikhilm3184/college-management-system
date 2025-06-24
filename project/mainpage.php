<!DOCTYPE html>
<html lang="en">

<>
  
  <title>College Cover Page</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

    * {
      box-sizing: border-box;
    }

    body,
    html {
      margin: 0;
      height: 100%;
      font-family: 'Poppins', sans-serif;
      color: #fff;
      overflow-x: hidden;
    }


    body {
      background-image: url('https://media.istockphoto.com/id/173938173/photo/modern-institute-building-exterior.jpg?s=612x612&w=0&k=20&c=ckujDy7_0hBWeEo1Qm9HBRJ0_TTSUZ4ynPB8CF5r1aY=');
      background-size: cover;
      background-position: center;
      position: relative;
    }

    body::before {
      content: "";
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.6);
      z-index: 0;
    }

    header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 70px;
      background: rgba(20, 20, 40, 0.85);
      display: flex;
      align-items: center;
      padding: 0 30px;
      z-index: 10;
      justify-content: space-between;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.7);
    }

    .college-info {
      max-width: 60%;
    }

    .college-info h1 {
      margin: 0;
      font-weight: 700;
      font-size: 1.8rem;
      letter-spacing: 1.5px;
    }

    .college-info p {
      margin: 4px 0 0 0;
      font-size: 0.95rem;
      color: #ddd;
      font-weight: 500;
    }


    nav.top-nav {
      display: flex;
      gap: 18px;
    }

    nav.top-nav a {
      color: #eee;
      text-decoration: none;
      font-weight: 600;
      font-size: 1rem;
      padding: 8px 16px;
      border-radius: 6px;
      background: rgba(255 255 255 / 0.15);
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    nav.top-nav a:hover {
      background: #764ba2;
      color: #fff;
    }


    main {
      position: relative;
      top: 70px;
      height: calc(100vh - 70px);
      display: flex;
      align-items: center;
      padding: 0 40px;
      z-index: 5;
    }


    .quote-section {
      flex: 1;
      max-width: 60%;
      padding-right: 50px;
    }

    .quote-section blockquote {
      font-size: 2.3rem;
      font-weight: 600;
      line-height: 1.3;
      border-left: 6px solid #764ba2;
      padding-left: 20px;
      font-style: italic;
      color: #e0d9ff;
    }

    .quote-section cite {
      display: block;
      margin-top: 15px;
      font-size: 1.3rem;
      color: #d1c4ff;
      font-weight: 600;
    }



    @media (max-width: 900px) {
      main {
        flex-direction: column;
        text-align: center;
        padding: 20px;
        height: auto;
      }

      .quote-section {
        max-width: 100%;
        padding-right: 0;
        margin-bottom: 40px;
        
      }
    }
  </style>
</head>

<body>
  <header>
    <div class="college-info">
      <h1>Sri Chaitnya College</h1>
      <p>Grades Approved by NAAC &nbsp;|&nbsp; Accredited Since 1995</p>
    </div>

    <nav class="top-nav" aria-label="Primary navigation">
      <a href="homepage.php">Home</a>
      <a href="about.html">About</a>
      <a href="contact.php">Contact</a>
      <a href="logout.php">Logout</a>
    </nav>
  </header>

  <main>
    <section class="quote-section" aria-label="Motivational quote">
      <blockquote>
        "Education is the most powerful weapon which you can use to change the world."
      </blockquote>
      <cite>— Nelson Mandela</cite>
    </section>
  </main>

</body>

</html>