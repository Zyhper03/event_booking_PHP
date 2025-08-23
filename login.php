<?php
session_start();
require 'includes/db.php';

$error = ""; // ✅ always define first

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - The Nextup Network</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <div class="container" style="max-width:400px; margin:4rem auto;">
    <h2>Login</h2>

    <?php if (!empty($error)): ?>   <!-- ✅ only show if set -->
      <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
      </div>

      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
      </div>

      <button type="submit" class="button-primary">Login</button>
    </form>

    <p style="margin-top:1rem;">Don't have an account? 
      <a href="register.php">Register here</a>
    </p>
  </div>

  <?php include 'includes/footer.php'; ?>
</body>
</html>
