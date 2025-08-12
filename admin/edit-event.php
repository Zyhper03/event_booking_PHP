<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$eventId = $_GET['id'] ?? null;
if (!$eventId) {
    die("Event ID missing.");
}

$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$eventId]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    die("Event not found.");
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

    if (!$title || !$venue || !$category || !$image || !$date || $total < 1 || $available < 0 || $price < 0) {
        $error = "All fields are required.";
    } else {
        $stmt = $pdo->prepare("UPDATE events SET title = ?, venue = ?, category = ?, image = ?, date = ?, total_tickets = ?, available_tickets = ?, price = ? WHERE id = ?");
        $stmt->execute([$title, $venue, $category, $image, $date, $total, $available, $price, $eventId]);

        header("Location: dashboard.php?edited=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Event</title>
  <link rel="stylesheet" href="../styles.css">
</head>
<body>

<section class="book-page">
  <div class="container">
    <h1>✏️ Edit Event</h1>
    <a href="dashboard.php" class="button-primary">← Back to Dashboard</a>

    <?php if ($success): ?>
      <p style="color: green;"><?= $success ?></p>
    <?php elseif ($error): ?>
      <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    
    <form method="POST" class="book-form" style="max-width: 500px;">
      <label>Event Title</label>
      <input type="text" name="title" value="<?= htmlspecialchars($event['title']) ?>" required>

      <label>Venue</label>
      <input type="text" name="venue" value="<?= htmlspecialchars($event['venue']) ?>" required>

      <label>Category</label>
      <input type="text" name="category" value="<?= htmlspecialchars($event['category']) ?>" required>

      <label>Image URL</label>
      <input type="url" name="image" value="<?= htmlspecialchars($event['image']) ?>" required>

      <label>Date</label>
      <input type="date" name="date" value="<?= $event['date'] ?>" required>

      <label>Total Tickets</label>
      <input type="number" name="total_tickets" value="<?= $event['total_tickets'] ?>" required min="1">

      <label>Available Tickets</label>
      <input type="number" name="available_tickets" value="<?= $event['available_tickets'] ?>" required min="0">

      <label>Ticket Price (₹)</label>
      <input type="number" name="price" value="<?= htmlspecialchars($event['price']) ?>" required min="0">

      <button type="submit" class="button-primary">Update Event</button>
    </form>
  </div>
</section>

</body>
</html>
