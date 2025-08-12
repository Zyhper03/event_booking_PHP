<?php
session_start();

require 'includes/db.php';

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $message = trim($_POST['message']);

    if ($name && $email && $message) {
        try {
            $stmt = $pdo->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, '', ?)");
            $stmt->execute([$name, $email, $message]);
            $success = "Your message has been sent successfully!";
        } catch (PDOException $e) {
            $error = "Something went wrong. Please try again.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact - The Nextup Network</title>
  <link rel="stylesheet" href="styles.css">
  <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>

<?php include 'includes/header.php'; ?>

<!-- Contact Section -->
<section class="contact-page">
  <div class="container">
    <h1>Contact Us</h1>
    <p class="contact-intro">Have questions about an event or need support? We're here to help! Fill out the form below and we'll get back to you as soon as possible.</p>

    <?php if ($success): ?>
      <div class="alert-success" style="color: green; padding: 10px 0;"><?= $success ?></div>
    <?php elseif ($error): ?>
      <div class="alert-error" style="color: red; padding: 10px 0;"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="contact-form">
      <div class="form-group">
        <label for="name">Name *</label>
        <input type="text" id="name" name="name" required>
      </div>
      <div class="form-group">
        <label for="email">Email *</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="message">Message *</label>
        <textarea id="message" name="message" rows="4" required></textarea>
      </div>
      <button type="submit" class="button-primary">Send Message</button>
    </form>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="script.js"></script>
</body>
</html>
