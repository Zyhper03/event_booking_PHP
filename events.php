<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'includes/db.php';

// Fetch events from DB
$stmt = $pdo->query("SELECT * FROM events ORDER BY date ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Events - The Nextup Network</title>
  <link rel="stylesheet" href="styles.css" />
  <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>

<!-- Navigation -->
<?php include 'includes/header.php'; ?>

<!-- Events Section -->
<section class="events-page">
  <div class="container">
    <h1>Upcoming Events</h1>

    <!-- Category Filter -->
    <div class="category-filters">
      <button class="category-btn active" data-category="all">All Events</button>
      <button class="category-btn" data-category="concerts"><i data-lucide="music"></i> Concerts</button>
      <button class="category-btn" data-category="comedy"><i data-lucide="mic"></i> Comedy</button>
    </div>

    <!-- Events Grid -->
    <div class="events-grid" id="eventsGrid">
      <?php if (empty($events)) : ?>
        <p style='color:red; font-weight: bold;'>No events found in database.</p>
      <?php else: ?>
        <?php foreach ($events as $event): 
          $ticketLeft = $event['available_tickets'];
          $ticketTotal = $event['total_tickets'];
          $percentage = $ticketTotal ? ($ticketLeft / $ticketTotal) * 100 : 0;
        ?>
        <a href="book.php?event_id=<?= $event['id'] ?>" class="event-card-link">
          <div class="event-card" data-category="<?= strtolower($event['category']) ?>">
            <img src="<?= htmlspecialchars($event['image']) ?>" alt="<?= htmlspecialchars($event['title']) ?>">
            <div class="event-card-content">
              <h3><?= htmlspecialchars($event['title']) ?></h3>
              <div class="event-details">
                <span><i data-lucide="calendar"></i> <?= date('d M Y', strtotime($event['date'])) ?></span>
                <span><i data-lucide="map-pin"></i> <?= htmlspecialchars($event['venue']) ?></span>
              </div>
              <div class="ticket-progress">
                <div class="progress-bar">
                  <div class="progress" style="width: <?= $percentage ?>%"></div>
                </div>
                <p><?= $ticketLeft ?> tickets left</p>
              </div>
            </div>
          </div>
        </a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Footer -->
<?php include 'includes/footer.php'; ?>

<!-- Scripts -->
<script src="script.js"></script>
<script>lucide.createIcons();</script>
</body>
</html>
