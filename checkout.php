<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['last_booking'])) {
    header('Location: index.php');
    exit();
}

$booking = $_SESSION['last_booking'];
$eventId = $booking['event_id'];
$tickets = $booking['tickets'];
$amount = $tickets * 250 * 100; // in paisa

$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$eventId]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    die("Event not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout - <?= htmlspecialchars($event['title']) ?></title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .checkout-container {
      display: flex;
      flex-direction: column;
      gap: 2rem;
      margin-top: 2rem;
    }
    @media (min-width: 768px) {
      .checkout-container {
        flex-direction: row;
      }
    }
    .summary-box, .payment-box {
      flex: 1;
      padding: 1.5rem;
      background: #f9fafb;
      border-radius: 0.75rem;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .pay-btn {
      margin-top: 1rem;
      width: 100%;
      padding: 0.75rem;
      background: #000;
      color: #fff;
      font-size: 1rem;
      border: none;
      border-radius: 0.5rem;
      cursor: pointer;
    }
  </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<section class="book-page">
  <div class="container">
    <h1>Secure Checkout</h1>

    <div class="checkout-container">
      <div class="summary-box">
        <h3>Booking Summary</h3>
        <p><strong>Event:</strong> <?= htmlspecialchars($event['title']) ?></p>
        <p><strong>Date:</strong> <?= date('d M Y', strtotime($event['date'])) ?></p>
        <p><strong>Venue:</strong> <?= htmlspecialchars($event['venue']) ?></p>
        <p><strong>Tickets:</strong> <?= $tickets ?></p>
        <p><strong>Total:</strong> ₹<?= number_format($amount / 100) ?></p>
      </div>

      <div class="payment-box">
        <h3>Pay with Demo Gateway</h3>
        <button id="rzp-button" class="pay-btn">Pay ₹<?= number_format($amount / 100) ?> Now</button>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>


<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
  const options = {
    key: "rzp_test_1DP5mmOlF5G5ag", // Razorpay demo key
    amount: <?= $amount ?>,
    currency: "INR",
    name: "The Nextup Network",
    description: "Event Ticket Purchase",
    handler: function (response) {
      window.location.href = "confirmation.php?event_id=<?= $eventId ?>&tickets=<?= $tickets ?>&paid=1";
    },
    prefill: {
      name: "<?= $_SESSION['user_name'] ?? 'Guest' ?>",
      email: "<?= $_SESSION['user_email'] ?? 'demo@example.com' ?>",
    },
    theme: {
      color: "#000000"
    }
  };

  const rzp = new Razorpay(options);
  document.getElementById("rzp-button").onclick = function (e) {
    rzp.open();
    e.preventDefault();
  };
</script>

</body>
</html>
