<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingId = $_POST['booking_id'];

    // Check if the booking belongs to the user
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ? AND user_id = ?");
    $stmt->execute([$bookingId, $_SESSION['user_id']]);
    $booking = $stmt->fetch();

    if ($booking) {
        // Refund tickets to the event
        $pdo->prepare("UPDATE events SET available_tickets = available_tickets + ? WHERE id = ?")
            ->execute([$booking['tickets_booked'], $booking['event_id']]);

        // Delete booking
        $pdo->prepare("DELETE FROM bookings WHERE id = ?")->execute([$bookingId]);
    }
}

header('Location: my-bookings.php');
exit();
