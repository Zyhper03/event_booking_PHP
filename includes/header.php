<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/db.php';

// Fetch user data if logged in
$userData = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT name, email, role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Store in session if not already
    if ($userData) {
        $_SESSION['user_name'] = $userData['name'];
        $_SESSION['user_email'] = $userData['email'];
        $_SESSION['role'] = $userData['role'];
    }
}
?>

<nav class="navbar">
  <div class="container navbar-inner">
    <a href="index.php" class="logo">The Nextup Network</a>

    <button class="hamburger" id="hamburgerBtn" aria-label="Menu">
      <i data-lucide="menu"></i>
    </button>

    <div class="nav-links" id="navLinks">
      <a href="index.php" class="nav-link">Home</a>
      <a href="events.php" class="nav-link">Events</a>
      <a href="about.php" class="nav-link">About</a>
      <a href="contact.php" class="nav-link">Contact</a>
    </div>

    <div class="nav-auth">
      <?php if (isset($_SESSION['user_id'])): ?>
        <div class="profile-dropdown">
          <button class="profile-button" id="profileIcon" style="background: none; border: none;">
            <i data-lucide="user"></i>
          </button>
          <div class="dropdown-menu" id="profileDropdown">
            <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></strong><br>
            <small><?php echo htmlspecialchars($_SESSION['user_email'] ?? 'user@example.com'); ?></small>
            <hr>
            <?php if ($_SESSION['role'] === 'admin'): ?>
              <a href="admin/admin-dashboard.php">Admin Dashboard</a>
              <a href="admin/add-event.php">Add Event</a>
              <a href="admin/contact-submissions.php">Contact Messages</a>
            <?php else: ?>
              <a href="my-bookings.php">My Bookings</a>
            <?php endif; ?>
            <a href="/event-booking/logout.php">Logout</a>
          </div>
        </div>
      <?php else: ?>
        <a href="login.php" class="button-primary-outline">Login</a>
        <a href="register.php" class="button-secondary">Register</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const hamburgerBtn = document.getElementById("hamburgerBtn");
    const navLinks = document.getElementById("navLinks");

    if (hamburgerBtn && navLinks) {
      hamburgerBtn.addEventListener("click", function () {
        navLinks.classList.toggle("show-nav");
      });
    }
  });
</script>
