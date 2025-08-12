<?php 
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$eventId = $_GET['event_id'] ?? null;
$tickets = $_GET['tickets'] ?? 0;
$isPaid = isset($_GET['paid']) && $_GET['paid'] == 1;

if (!$eventId || $tickets <= 0) {
    die("Invalid booking request.");
}

// Fetch event
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$eventId]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    die("Event not found.");
}

// Check if enough tickets are available
if ($event['available_tickets'] < $tickets) {
    die("Not enough tickets available.");
}

// Insert booking into `bookings` table
$stmt = $pdo->prepare("
    INSERT INTO bookings (user_id, event_id, tickets_booked, booked_at)
    VALUES (?, ?, ?, NOW())
");
$stmt->execute([$_SESSION['user_id'], $eventId, $tickets]);

// Update `events` table to reduce available tickets
$stmt = $pdo->prepare("UPDATE events SET available_tickets = available_tickets - ? WHERE id = ?");
$stmt->execute([$tickets, $eventId]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking Confirmed - <?= htmlspecialchars($event['title']) ?></title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .confirmation-wrapper {
      text-align: center;
      padding: 4rem 1rem;
    }

    .checkmark-circle {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background: <?= $isPaid ? '#4BB543' : '#f59e0b' ?>;
      display: inline-block;
      line-height: 100px;
      margin-bottom: 1rem;
      animation: pop 0.3s ease-out;
    }

    .checkmark-circle i {
      color: white;
      font-size: 2rem;
    }

    @keyframes pop {
      0% { transform: scale(0.5); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }

    .confirmation-message h1 {
      font-size: 2rem;
      margin-bottom: 0.5rem;
      color: #333;
    }

    .confirmation-message p {
      font-size: 1.1rem;
      color: #555;
    }

    .confirmed-event {
      margin-top: 3rem;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .confirmed-event img {
      width: 100%;
      max-width: 500px;
      border-radius: 1rem;
      object-fit: cover;
    }

    .event-info {
      margin-top: 1.5rem;
      max-width: 500px;
      text-align: left;
    }

    .confirmation-actions {
      margin-top: 2rem;
    }

    .confirmation-actions a {
      margin: 0.5rem;
      text-decoration: none;
      padding: 0.75rem 1.5rem;
      background-color: var(--primary);
      color: white;
      border-radius: 0.5rem;
      display: inline-block;
      transition: background-color 0.2s;
    }

    .confirmation-actions a:hover {
      background-color: #333;
    }

    @media (max-width: 768px) {
      .event-info { padding: 0 1rem; text-align: center; }
    }
  </style>
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
</head>
<body>

<section class="confirmation-wrapper">
  <div class="checkmark-circle">
    <i data-lucide="<?= $isPaid ? 'check' : 'clock' ?>"></i>
  </div>

  <div class="confirmation-message">
    <h1><?= $isPaid ? 'Payment Successful!' : 'Booking Confirmed (Unpaid)' ?></h1>
    <p>
      You’ve successfully booked <strong><?= htmlspecialchars($tickets) ?></strong> ticket(s)
      <?= $isPaid ? 'with payment completed.' : 'but payment was not processed.' ?>
    </p>
  </div>

  <div class="confirmed-event">
    <img src="<?= htmlspecialchars($event['image']) ?>" alt="<?= htmlspecialchars($event['title']) ?>">
    <div class="event-info">
      <h2><?= htmlspecialchars($event['title']) ?></h2>
      <p><strong>Date:</strong> <?= date('d M Y', strtotime($event['date'])) ?></p>
      <p><strong>Venue:</strong> <?= htmlspecialchars($event['venue']) ?></p>
    </div>
  </div>

  <div class="confirmation-actions">
    <a href="events.php">🎟 Back to Events</a>
    <a href="my-bookings.php">📋 My Bookings</a>
  </div>
</section>

<script>
  lucide.createIcons();
  confetti({ particleCount: 120, spread: 100, origin: { y: 0.6 } });
</script>

</body>
</html>
