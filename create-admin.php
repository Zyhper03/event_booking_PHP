<?php
require 'includes/db.php';

// Define admin credentials
$name = 'Admin';
$email = 'admin@example.com';
$password = password_hash('admin123', PASSWORD_DEFAULT);
$role = 'admin';

// Insert admin into users table
$stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->execute([$name, $email, $password, $role]);

echo "Admin account created successfully!";
?>
