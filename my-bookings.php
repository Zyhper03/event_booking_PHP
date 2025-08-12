<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$userId = $_SESSION['user_id'];
$limit = 5; // bookings per page
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Count total bookings
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = ?");
$countStmt->execute([$userId]);
$totalBookings = $countStmt->fetchColumn();
$totalPages = ceil($totalBookings / $limit);

// Fetch bookings for current page
$stmt = $pdo->prepare("
  SELECT b.id as booking_id, b.tickets_booked, b.booked_at, e.title, e.date, e.venue, e.image
  FROM bookings b
  JOIN events e ON b.event_id = e.id
  WHERE b.user_id = ?
  ORDER BY b.booked_at DESC
  LIMIT ? OFFSET ?
");
$stmt->bindValue(1, $userId, PDO::PARAM_INT);
$stmt->bindValue(2, $limit, PDO::PARAM_INT);
$stmt->bindValue(3, $offset, PDO::PARAM_INT);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Bookings</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<section class="book-page">
  <div class="container">
    <h1>My Bookings</h1>

    <?php if (empty($bookings)): ?>
      <p>You have no bookings yet.</p>
    <?php else: ?>
      <div class="events-grid">
        <?php foreach ($bookings as $booking): ?>
          <div class="event-card" style="position: relative;">
            <img src="<?= htmlspecialchars($booking['image']) ?>" alt="<?= htmlspecialchars($booking['title']) ?>">
            <div class="event-card-content">
              <h3><?= htmlspecialchars($booking['title']) ?></h3>
              <p><strong>Date:</strong> <?= date('d M Y', strtotime($booking['date'])) ?></p>
              <p><strong>Venue:</strong> <?= htmlspecialchars($booking['venue']) ?></p>
              <p><strong>Tickets:</strong> <?= $booking['tickets_booked'] ?></p>
              <p><em>Booked on <?= date('d M Y, h:i A', strtotime($booking['booked_at'])) ?></em></p>

              <form method="POST" action="cancel-booking.php" onsubmit="return confirm('Are you sure you want to cancel this booking?');" style="margin-top: 1rem;">
                <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>">
                <button type="submit" class="button-primary" style="background-color: #c0392b;">Cancel Booking</button>
              </form>

              <button onclick="window.print()" class="button-primary" style="margin-top: 0.5rem;">🖨 Print</button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Pagination Controls -->
      <div style="margin-top: 2rem; text-align: center;">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <a href="?page=<?= $i ?>" class="button-primary" style="<?= $i === $page ? 'background: #000;' : '' ?> margin: 0 0.25rem;">
            <?= $i ?>
          </a>
        <?php endfor; ?>
      </div>
    <?php endif; ?>

    <a href="events.php" class="button-primary" style="margin-top: 2rem; display: inline-block;">← Back to Events</a>
  </div>
</section>

</body>
</html>
