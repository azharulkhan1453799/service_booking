<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
  header('Location: login.php');
  exit;
}
include '../includes/db.php';

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$stmt = $conn->prepare("SELECT b.*, s.title AS service_title 
                        FROM bookings b 
                        JOIN services s ON b.service_id = s.id 
                        WHERE b.user_id = ? 
                        ORDER BY b.created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

  <!-- Header -->
  <div class="bg-white shadow p-6 mb-8">
    <div class="max-w-6xl mx-auto flex justify-between items-center">
      <h1 class="text-2xl font-bold text-indigo-600">Welcome, <?= htmlspecialchars($user_name); ?></h1>
      <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Logout</a>
    </div>
  </div>

  <!-- Bookings Table -->
  <div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4 text-gray-800">Your Bookings</h2>
    <?php if ($result->num_rows > 0): ?>
    <div class="overflow-x-auto">
      <table class="min-w-full table-auto border border-gray-200 text-sm">
        <thead class="bg-indigo-600 text-white uppercase">
          <tr>
            <th class="text-left px-4 py-2">#</th>
            <th class="text-left px-4 py-2">Service</th>
            <th class="text-left px-4 py-2">Booking Date</th>
            <th class="text-left px-4 py-2">Status</th>
            <th class="text-left px-4 py-2">Booked At</th>
          </tr>
        </thead>
        <tbody class="text-gray-700">
          <?php $i = 1; while($row = $result->fetch_assoc()): ?>
          <tr class="border-b hover:bg-gray-50">
            <td class="px-4 py-2"><?= $i++; ?></td>
            <td class="px-4 py-2"><?= htmlspecialchars($row['service_title']); ?></td>
            <td class="px-4 py-2"><?= htmlspecialchars($row['booking_date']); ?></td>
            <td class="px-4 py-2"><?= htmlspecialchars($row['status'] ?? 'Pending'); ?></td>
            <td class="px-4 py-2"><?= date('d M Y, h:i A', strtotime($row['created_at'])); ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <?php else: ?>
      <p class="text-gray-600">You have not booked any services yet.</p>
    <?php endif; ?>
  </div>

</body>
</html>
