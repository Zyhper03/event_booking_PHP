<?php
session_start();
session_unset();
session_destroy();

// Redirect to home page after logout
header("Location: index.php");
exit;
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Logging Out...</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    body {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      font-family: 'Inter', sans-serif;
      background-color: #f9f9f9;
    }
    .logout-message {
      text-align: center;
      padding: 2rem;
      border: 1px solid #eee;
      border-radius: 1rem;
      background: #fff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
  </style>
  <script>
    setTimeout(() => {
      window.location.href = 'index.php';
    }, 3000); // 3 seconds
  </script>
</head>
<body>
  <div class="logout-message">
    <h2>👋 You've been logged out</h2>
    <p>Redirecting to home page...</p>
  </div>
</body>
</html>
