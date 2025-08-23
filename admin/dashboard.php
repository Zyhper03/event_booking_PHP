<?php
session_start();
require '../includes/db.php';

// ✅ Restrict access to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // If user is logged in but not admin → send them to homepage
    header("Location: ../index.php");
    exit;
}

// ✅ Handle role changes (make admin, make user, delete user)
if (isset($_POST['action']) && isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];
    $action = $_POST['action'];

    if ($action === 'make_admin') {
        $stmt = $pdo->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
        $stmt->execute([$userId]);
    } elseif ($action === 'make_user') {
        $stmt = $pdo->prepare("UPDATE users SET role = 'user' WHERE id = ?");
        $stmt->execute([$userId]);
    } elseif ($action === 'delete_user') {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);
    }
    header("Location: dashboard.php");
    exit;
}

// ✅ Fetch events
$stmt = $pdo->query("SELECT * FROM events ORDER BY date ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Fetch all users
$stmt = $pdo->query("SELECT id, name, email, role FROM users ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../styles.css">
  <style>
    .tabs { margin-top: 2rem; display: flex; gap: 1rem; }
    .tab-btn {
      padding: 0.5rem 1rem; border: none; cursor: pointer;
      background: #eee; border-radius: 6px;
    }
    .tab-btn.active { background: #2563eb; color: white; }
    .tab-content { display: none; margin-top: 2rem; }
    .tab-content.active { display: block; }
    table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
    table, th, td { border: 1px solid #ddd; padding: 0.75rem; text-align: left; }
    th { background: #f3f4f6; }
    .actions form { display: inline-block; }
    .btn-admin { background: #2563eb; color: white; padding: 5px 10px; border: none; }
    .btn-user { background: #10b981; color: white; padding: 5px 10px; border: none; }
    .btn-delete { background: #dc2626; color: white; padding: 5px 10px; border: none; }
  </style>
</head>
<body>
  <div class="container">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?> 👋</h1>
    <a href="../logout.php" class="button-primary" style="background:#c0392b;">Logout</a>

    <!-- Tabs -->
    <div class="tabs">
      <button class="tab-btn active" data-tab="events">Manage Events</button>
      <button class="tab-btn" data-tab="users">Manage Users</button>
    </div>

    <!-- Manage Events -->
    <div id="events" class="tab-content active">
      <a href="add-event.php" class="button-primary">➕ Add New Event</a>
      <a href="contact-submissions.php" class="button-secondary">📬 View Contact Submissions</a>

      <?php if (empty($events)): ?>
        <p>No events available.</p>
      <?php else: ?>
        <div class="events-grid" style="margin-top: 2rem;">
          <?php foreach ($events as $event): ?>
            <div class="event-card">
              <img src="<?= htmlspecialchars($event['image']); ?>" alt="<?= htmlspecialchars($event['title']); ?>">
              <div class="event-card-content">
                <h3><?= htmlspecialchars($event['title']); ?></h3>
                <p><strong>Date:</strong> <?= date('d M Y', strtotime($event['date'])); ?></p>
                <p><strong>Venue:</strong> <?= htmlspecialchars($event['venue']); ?></p>
                <p><strong>Category:</strong> <?= htmlspecialchars($event['category']); ?></p>
                <a href="edit-event.php?id=<?= $event['id']; ?>" class="button-primary">✏️ Edit</a>
                <a href="delete-event.php?id=<?= $event['id']; ?>" class="button-primary" style="background:#e74c3c;" onclick="return confirm('Delete this event?');">🗑 Delete</a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Manage Users -->
    <div id="users" class="tab-content">
      <h2>User Management</h2>
      <table>
        <thead>
          <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
            <tr>
              <td><?= $user['id']; ?></td>
              <td><?= htmlspecialchars($user['name']); ?></td>
              <td><?= htmlspecialchars($user['email']); ?></td>
              <td><?= $user['role']; ?></td>
              <td class="actions">
                <?php if ($user['role'] === 'user'): ?>
                  <form method="POST"><input type="hidden" name="user_id" value="<?= $user['id']; ?>"><button type="submit" name="action" value="make_admin" class="btn-admin">Make Admin</button></form>
                <?php else: ?>
                  <form method="POST"><input type="hidden" name="user_id" value="<?= $user['id']; ?>"><button type="submit" name="action" value="make_user" class="btn-user">Make User</button></form>
                <?php endif; ?>
                <form method="POST" onsubmit="return confirm('Delete this user?');">
                  <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                  <button type="submit" name="action" value="delete_user" class="btn-delete">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

<script>
  document.querySelectorAll(".tab-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      document.querySelectorAll(".tab-btn").forEach(b => b.classList.remove("active"));
      document.querySelectorAll(".tab-content").forEach(c => c.classList.remove("active"));
      btn.classList.add("active");
      document.getElementById(btn.dataset.tab).classList.add("active");
    });
  });
</script>
</body>
</html>
