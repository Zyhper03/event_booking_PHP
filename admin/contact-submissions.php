<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$stmt = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC");
$submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Submissions - Admin Panel</title>
  <link rel="stylesheet" href="../styles.css">
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 2rem;
    }
    th, td {
      padding: 12px;
      border: 1px solid #ddd;
    }
    th {
      background: #f4f4f4;
      text-align: left;
    }
    tr:nth-child(even) {
      background-color: #fafafa;
    }
    .admin-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
  </style>
</head>
<body>

<section class="book-page">
  <div class="container">
    <div class="admin-header">
      <h1>📬 Contact Submissions</h1>
      <a href="dashboard.php" class="button-primary">← Back to Dashboard</a>
    </div>

    <?php if (empty($submissions)): ?>
      <p style="color:red;">No contact form submissions yet.</p>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($submissions as $row): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
              <td><?= date('d M Y, h:i A', strtotime($row['created_at'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</section>

</body>
</html>
