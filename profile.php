<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Profile</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="container" style="padding: 3rem 0;">
  <h1>My Profile</h1>

  <form action="update-profile.php" method="POST" style="max-width: 400px;">
    <label>Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>

    <label>Phone:</label>
    <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">

    <button type="submit" class="button-primary">Update Profile</button>
  </form>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
