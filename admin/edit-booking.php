<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: login.php');
  exit;
}
include '../includes/db.php';

$id = intval($_GET['id']);
$error = '';

// Get booking data
$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
if (!$booking) {
  die("Booking not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $phone = trim($_POST['phone']);
  $service_id = intval($_POST['service_id']);
  $booking_date = $_POST['booking_date'];

  if ($name && $email && $phone && $service_id && $booking_date) {
    $stmt = $conn->prepare("UPDATE bookings SET name=?, email=?, phone=?, service_id=?, booking_date=? WHERE id=?");
    $stmt->bind_param("sssisi", $name, $email, $phone, $service_id, $booking_date, $id);
    if ($stmt->execute()) {
      header("Location: bookings.php"); // 🔁 Redirect
      exit;
    } else {
      $error = "Error updating booking.";
    }
  } else {
    $error = "All fields are required.";
  }
}

$services = $conn->query("SELECT * FROM services");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Edit Booking - Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
  <div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-yellow-600">Edit Booking</h2>

    <?php if ($error): ?>
      <div class="text-red-600 mb-4"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-5">
      <input type="text" name="name" value="<?= htmlspecialchars($booking['name']); ?>" class="w-full border px-4 py-2 rounded" required>
      <input type="email" name="email" value="<?= htmlspecialchars($booking['email']); ?>" class="w-full border px-4 py-2 rounded" required>
      <input type="text" name="phone" value="<?= htmlspecialchars($booking['phone']); ?>" class="w-full border px-4 py-2 rounded" required>
      <select name="service_id" class="w-full border px-4 py-2 rounded" required>
        <option value="">-- Select Service --</option>
        <?php while ($s = $services->fetch_assoc()): ?>
          <option value="<?= $s['id']; ?>" <?= $s['id'] == $booking['service_id'] ? 'selected' : ''; ?>>
            <?= $s['title']; ?>
          </option>
        <?php endwhile; ?>
      </select>
      <input type="date" name="booking_date" value="<?= $booking['booking_date']; ?>" class="w-full border px-4 py-2 rounded" required>
      <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded font-semibold">Update Booking</button>
    </form>
  </div>
</body>
</html>
