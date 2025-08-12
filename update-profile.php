<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

$name = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';

if (trim($name) === '') {
    die("Name is required.");
}

$stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
$stmt->execute([$name, $phone, $userId]);

header("Location: profile.php?success=1");
exit;
