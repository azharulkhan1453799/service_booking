<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = intval($_POST['service_id']);
    $name        = trim($_POST['name']);
    $email       = trim($_POST['email']);
    $phone       = trim($_POST['phone']);
    $booking_date = $_POST['booking_date'];

    // Simple validation
    if ($service_id && $name && $email && $phone && $booking_date) {
        $stmt = $conn->prepare("INSERT INTO bookings (service_id, name, email, phone, booking_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $service_id, $name, $email, $phone, $booking_date);

        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = "Something went wrong. Please try again.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
} else {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking Status</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen text-center">
<div class="bg-white p-10 rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300">
  <?php if (!empty($success)): ?>
    <div class="flex flex-col items-center text-center">
      <div class="w-16 h-16 mb-4 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-3xl">
        ✓
      </div>
      <h2 class="text-2xl font-extrabold text-green-700 mb-2">Booking Confirmed!</h2>
      <p class="text-gray-700 mb-6">Thank you <span class="font-semibold"><?= htmlspecialchars($name); ?></span>. We'll contact you shortly.</p>
      <a href="index.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-full font-medium transition">Go Back to Home</a>
    </div>
  <?php else: ?>
    <div class="flex flex-col items-center text-center">
      <div class="w-16 h-16 mb-4 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-3xl">
        ⚠️
      </div>
      <h2 class="text-2xl font-extrabold text-red-700 mb-2">Booking Failed</h2>
      <p class="text-gray-700 mb-6"><?= $error; ?></p>
      <a href="javascript:history.back()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-full font-medium transition">Try Again</a>
    </div>
  <?php endif; ?>
</div>


</body>
</html>
