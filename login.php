<?php
session_start();
require 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - The Nextup Network</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <nav class="navbar">
    <div class="container">
      <a href="index.php" class="logo">The Nextup Network</a>
    </div>
  </nav>

  <section class="contact-page">
    <div class="container">
      <h1>Login</h1>
      <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

      <form method="POST" class="contact-form">
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" placeholder="email@" required />
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" placeholder="athd2434" required />
        </div>
        <button type="submit" class="button-primary">Login</button>
      </form>
    </div>
  </section>
</body>
</html>

