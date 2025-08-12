<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $venue = $_POST['venue'];
    $category = $_POST['category'];
    $image = $_POST['image'];
    $date = $_POST['date'];
    $total = (int) $_POST['total_tickets'];
    $available = (int) $_POST['available_tickets'];
    $price = (float) $_POST['price'];

    if (!$title || !$venue || !$category || !$image || !$date || $total < 1 || $available < 1 || $price < 0) {
        $error = "All fields are required.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO events (title, venue, category, image, date, total_tickets, available_tickets, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $venue, $category, $image, $date, $total, $available, $price]);

        header("Location: dashboard.php?added=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Event</title>
  <link rel="stylesheet" href="../styles.css">
</head>
<body>

<section class="book-page">
  <div class="container">
    <h1>➕ Add New Event</h1>
    <a href="dashboard.php" class="button-primary">← Back to Dashboard</a>

    <?php if ($success): ?>
      <p style="color: green;"><?= $success ?></p>
    <?php elseif ($error): ?>
      <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" class="book-form" style="max-width: 500px;">
      <label>Event Title</label>
      <input type="text" name="title" required>

      <label>Venue</label>
      <input type="text" name="venue" required>

      <label>Category (e.g. concerts, comedy)</label>
      <input type="text" name="category" required>

      <label>Image URL</label>
      <input type="url" name="image" required>

      <label>Date</label>
      <input type="date" name="date" required>

      <label>Total Tickets</label>
      <input type="number" name="total_tickets" required min="1">

      <label>Available Tickets</label>
      <input type="number" name="available_tickets" required min="1">

      <label>Ticket Price (₹)</label>
      <input type="number" name="price" placeholder="250" min="0" required>

      <button type="submit" class="button-primary">Add Event</button>
    </form>
  </div>
</section>

</body>
</html>
