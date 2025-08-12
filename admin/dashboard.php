<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch events
$stmt = $pdo->query("SELECT * FROM events ORDER BY date ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../styles.css">
  <style>
    .alert-success {
      background-color: #d1fae5;
      color: #065f46;
      padding: 1rem 1.5rem;
      border: 1px solid #10b981;
      border-radius: 0.5rem;
      margin-bottom: 1rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      animation: fadeIn 0.4s ease-in-out;
    }

       .alert-danger {
      background-color: #fee2e2;
      color: #991b1b;
      padding: 1rem 1.5rem;
      border: 1px solid #ef4444;
      border-radius: 0.5rem;
      margin-bottom: 1rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      animation: fadeIn 0.4s ease-in-out;
    }

    .alert-danger .close-alert {
      background: none;
      border: none;
      font-size: 1.25rem;
      color: #991b1b;
      cursor: pointer;
      font-weight: bold;
      margin-left: 1rem;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 600px) {
      .book-page .container > a {
        display: block;
        margin-bottom: 1rem !important;
      }
    }
  </style>
</head>
<body>

<section class="book-page">
  <div class="container">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['admin_name']) ?> 👋</h1>
      
      <?php if (isset($_GET['added'])): ?>
        <div class="alert-success" id="addAlert">
          <span>Event added successfully.</span>
          <button class="close-alert" onclick="document.getElementById('addAlert').style.display='none'">&times;</button>
        </div>
      <?php endif; ?>

      <?php if (isset($_GET['edited'])): ?>
        <div class="alert-success" id="editAlert">
          <span>Event updated successfully.</span>
          <button class="close-alert" onclick="document.getElementById('editAlert').style.display='none'">&times;</button>
        </div>
      <?php endif; ?>

      <?php if (isset($_GET['deleted'])): ?>
        <div class="alert-danger" id="deleteAlert">
          <span>Event deleted successfully.</span>
          <button class="close-alert" onclick="document.getElementById('deleteAlert').style.display='none'">&times;</button>
        </div>
      <?php endif; ?>

    <a href="add-event.php" class="button-primary" style="margin-bottom: 1rem; display:inline-block;">➕ Add New Event</a>
    <a href="contact-submissions.php" class="button-secondary">📬 View Contact Submissions</a>
    <a href="logout.php" class="button-primary" style="background: #c0392b;">Logout</a>

    <?php if (empty($events)): ?>
      <p>No events available.</p>
    <?php else: ?>
      <div class="events-grid" style="margin-top: 2rem;">
        <?php foreach ($events as $event): ?>
          <div class="event-card" style="position: relative;">
            <img src="<?= htmlspecialchars($event['image']) ?>" alt="<?= htmlspecialchars($event['title']) ?>">
            <div class="event-card-content">
              <h3><?= htmlspecialchars($event['title']) ?></h3>
              <p><strong>Date:</strong> <?= date('d M Y', strtotime($event['date'])) ?></p>
              <p><strong>Venue:</strong> <?= htmlspecialchars($event['venue']) ?></p>
              <p><strong>Category:</strong> <?= htmlspecialchars($event['category']) ?></p>

              <a href="edit-event.php?id=<?= $event['id'] ?>" class="button-primary" style="margin-top: 0.5rem;">✏️ Edit</a>
              <a href="delete-event.php?id=<?= $event['id'] ?>" class="button-primary" style="background: #e74c3c; margin-left: 0.5rem;" onclick="return confirm('Delete this event?');">🗑 Delete</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>



<script>
  document.addEventListener("DOMContentLoaded", () => {
    ['addAlert', 'editAlert', 'deleteAlert'].forEach(id => {
      const el = document.getElementById(id);
      if (el) {
        setTimeout(() => {
          el.style.display = 'none';
        }, 4000);
      }
    });
  });
</script>

</body>
</html>
