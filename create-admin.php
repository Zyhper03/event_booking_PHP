<?php
require 'includes/db.php';

$name = 'Admin';
$email = 'admin@example.com';
$password = password_hash('admin123', PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
$stmt->execute([$name, $email, $password]);

echo "Admin created.";
?>
