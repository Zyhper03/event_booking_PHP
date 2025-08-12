<?php
session_start();
require '../includes/db.php';

// Only allow logged-in admins
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Get event ID from URL
$eventId = $_GET['id'] ?? null;

if (!$eventId) {
    die("No event ID provided.");
}

// Optional: Check if event exists (for better error handling)
$check = $pdo->prepare("SELECT id FROM events WHERE id = ?");
$check->execute([$eventId]);
if (!$check->fetch()) {
    die("Event not found or already deleted.");
}

// Delete event
$stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
$stmt->execute([$eventId]);

// Redirect back to dashboard
header('Location: dashboard.php?deleted=1');
exit();

