<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About - The Nextup Network</title>
  <link rel="stylesheet" href="styles.css">
  <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>

<?php include 'includes/header.php'; ?>

<!-- About Section -->
<section class="about-page">
  <div class="container">
    <h1>About The Nextup Network</h1>
    <div class="about-content">
      <p>Welcome to The Nextup Network, your premier destination for discovering and booking the most exciting events in Goa. We curate the finest selection of concerts, comedy shows, and cultural experiences that capture the vibrant spirit of Goa.</p>
      <p>Our platform connects event organizers with audiences who are eager to experience the best of Goa's entertainment scene. From beachside music festivals to intimate comedy nights, we're your trusted partner in creating unforgettable memories.</p>
    </div>
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-number">100+</div>
        <div class="stat-label">Monthly Events</div>
      </div>
      <div class="stat-card">
        <div class="stat-number">50k+</div>
        <div class="stat-label">Happy Attendees</div>
      </div>
      <div class="stat-card">
        <div class="stat-number">25+</div>
        <div class="stat-label">Venue Partners</div>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

<script src="script.js"></script>
<script>lucide.createIcons();</script>
</body>
</html>
