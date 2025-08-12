<?php
session_start();
require 'includes/db.php';

try {
    $stmt = $pdo->query("SELECT * FROM events ORDER BY date ASC");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $events = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>The Nextup Network - Home</title>
  <link rel="stylesheet" href="styles.css">
  <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>

<!-- ✅ Include Navbar -->
<?php include 'includes/header.php'; ?>

<!-- ✅ Hero Section -->
<section class="hero">
  <div class="hero-overlay"></div>
  <div class="container hero-content">
    <h1>Discover and Book<br> the Best Events in Goa</h1>
    <p>Explore concerts, comedy shows, festivals, and more – all in one place.</p>
    <a href="events.php" class="button-primary">Explore Events</a>
  </div>
</section>

<!-- ✅ Featured Events -->
<!-- Featured Events -->
<section class="featured-events">
  <div class="container">
    <h2 style="text-align:center;">Featured Events</h2>

    <div class="events-grid">
      <?php foreach ($events as $event): ?>
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="book.php?event_id=<?= $event['id'] ?>" class="event-card-link">
        <?php endif; ?>

        <div class="event-card" <?= isset($_SESSION['user_id']) ? '' : 'style="cursor: default;"' ?>>
          <img src="<?= htmlspecialchars($event['image']) ?>" alt="<?= htmlspecialchars($event['title']) ?>">
          <div class="event-card-content">
            <h3><?= htmlspecialchars($event['title']) ?></h3>
            <div class="event-details">
              <span><i data-lucide="calendar"></i> <?= date('d M Y', strtotime($event['date'])) ?></span>
              <span><i data-lucide="map-pin"></i> <?= htmlspecialchars($event['venue']) ?></span>
            </div>
          </div>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
          </a>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Why Choose Us -->
<section class="why-choose-us">
  <div class="container">
    <div class="section-header">
      <h2>Why Book with Us?</h2>
      <p>We bring Goa’s top events to your fingertips with a fast, secure, and simple booking experience.</p>
    </div>

    <div class="features-grid">
      <div class="feature">
        <div class="feature-icon"><i data-lucide="ticket"></i></div>
        <h3>Easy Booking</h3>
        <p>Book your tickets in just a few clicks.</p>
      </div>
      <div class="feature">
        <div class="feature-icon"><i data-lucide="star"></i></div>
        <h3>Top Events</h3>
        <p>Only the best events curated for you.</p>
      </div>
      <div class="feature">
        <div class="feature-icon"><i data-lucide="lock"></i></div>
        <h3>Secure Payments</h3>
        <p>Your data and payments are always protected.</p>
      </div>
    </div>
  </div>
</section>

<!-- ✅ Footer -->
<?php include 'includes/footer.php'; ?>

<!-- ✅ Scripts -->
<script src="script.js"></script>
<script>lucide.createIcons();</script>

</body>
</html>
