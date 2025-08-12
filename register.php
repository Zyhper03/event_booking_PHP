<?php
require 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);

        // ✅ Redirect user to index with guide message
        header("Location: index.php?registered=1");
        exit();
    } catch (PDOException $e) {
        $error = "Email already exists or invalid input.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register - The Nextup Network</title>
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
      <h1>Register</h1>
      <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
      <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>

      <form method="POST" class="contact-form">
        <div class="form-group">
          <label>Name</label>
          <input type="text" name="name" placeholder="anish" required />
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" placeholder="email@" required />
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" placeholder="gsd4356" required />
        </div>
        <button type="submit" class="button-primary">Register</button>
      </form>
    </div>
  </section>
</body>
</html>
