<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: login.php");
  exit;
}
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $booking_id = $_POST['booking_id'];
  $status = $_POST['status'];

  $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
  $stmt->bind_param("si", $status, $booking_id);
  $stmt->execute();
}

header("Location: bookings.php");
exit;
?>
