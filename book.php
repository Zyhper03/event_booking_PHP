<?php
session_start();
require 'includes/db.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Get event ID from URL
$eventId = $_GET['event_id'] ?? null;

if (!$eventId) {
    die("No event selected.");
}

// Fetch event details from DB
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$eventId]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    die("Event not found.");
}

// Booking form handler
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tickets = (int) $_POST['tickets'];
    $userId = $_SESSION['user_id'];

    if ($tickets <= 0 || $tickets > $event['available_tickets']) {
        $error = "Invalid number of tickets.";
    } else {
        // Save booking details in session for payment
        $_SESSION['last_booking'] = [
            'event_id' => $eventId,
            'event_title' => $event['title'],
            'tickets' => $tickets,
            'price_per_ticket' => $event['price'] ?? 500, // fallback
            'total_amount' => ($event['price'] ?? 500) * $tickets
        ];

        // Redirect to checkout
        header("Location: checkout.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Event - <?= htmlspecialchars($event['title']) ?></title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<nav class="navbar">
  <div class="container">
    <a href="index.php" class="logo">The Nextup Network</a>
  </div>
</nav>

<section class="book-page">
  <div class="container">
    <h1>Book Tickets</h1>

    <?php if ($error): ?>
      <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <div class="book-content">
      <div class="event-card book-event">
        <img src="<?= htmlspecialchars($event['image']) ?>" alt="<?= htmlspecialchars($event['title']) ?>">
        <div class="event-card-content">
          <h3><?= htmlspecialchars($event['title']) ?></h3>
          <p><strong>Date:</strong> <?= date('d M Y', strtotime($event['date'])) ?></p>
          <p><strong>Venue:</strong> <?= htmlspecialchars($event['venue']) ?></p>
          <p><strong>Tickets Left:</strong> <?= $event['available_tickets'] ?></p>
        </div>
      </div>

      <form method="POST" class="book-form">
        <label for="tickets">Number of Tickets</label>
        <input type="number" name="tickets" id="tickets" placeholder="1" min="1" max="<?= $event['available_tickets'] ?>" required>
        <button type="submit" class="button-primary">Confirm Booking</button>
      </form>
    </div>
  </div>
</section>

</body>
</html>
