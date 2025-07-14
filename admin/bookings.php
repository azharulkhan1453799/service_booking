<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: login.php');
  exit;
}
include '../includes/db.php';

// Handle search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Handle pagination
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Count total records
$countQuery = "SELECT COUNT(*) AS total FROM bookings b JOIN services s ON b.service_id = s.id";
if ($search !== '') {
    $search = $conn->real_escape_string($search);
    $countQuery .= " WHERE b.name LIKE '%$search%' OR b.email LIKE '%$search%'";
}
$totalResult = $conn->query($countQuery);
$totalRow = $totalResult->fetch_assoc();
$totalRecords = $totalRow['total'];
$totalPages = ceil($totalRecords / $limit);

// Fetch bookings with limit
$query = "SELECT b.*, s.title AS service_title FROM bookings b 
          JOIN services s ON b.service_id = s.id";
if ($search !== '') {
    $query .= " WHERE b.name LIKE '%$search%' OR b.email LIKE '%$search%'";
}
$query .= " ORDER BY b.created_at DESC LIMIT $limit OFFSET $offset";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Bookings - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gray-100 min-h-screen">

  <!-- Sidebar -->
  <aside class="w-64 bg-white shadow-2xl p-6 border-r border-gray-200">
  <h2 class="text-3xl font-extrabold text-indigo-700 mb-8 tracking-wide"> ServiceBook</h2>
  
  <nav class="space-y-4 text-[17px] font-medium">
    <a href="dashboard.php" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-indigo-50 transition text-gray-700 hover:text-indigo-700 group">
      <span class="w-2 h-2 bg-indigo-500 rounded-full group-hover:scale-125 transition"></span>
      Dashboard
    </a>
    
    <a href="bookings.php" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-indigo-50 transition text-gray-700 hover:text-indigo-700 group">
      <span class="w-2 h-2 bg-indigo-500 rounded-full group-hover:scale-125 transition"></span>
      Bookings
    </a>
    
    <a href="services.php" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-indigo-50 transition text-gray-700 hover:text-indigo-700 group">
      <span class="w-2 h-2 bg-indigo-500 rounded-full group-hover:scale-125 transition"></span>
      Manage Services
    </a>
    
    <a href="logout.php" class="flex items-center gap-3 px-4 py-2 rounded-lg bg-red-50 text-red-600 font-semibold hover:bg-red-100 hover:text-red-700 transition">
      <span class="w-2 h-2 bg-red-500 rounded-full"></span>
      Logout
    </a>
  </nav>

  <!-- Optional footer in sidebar -->
  <div class="mt-10 pt-6 border-t border-gray-200 text-sm text-gray-500">
    <p>Logged in as <br><span class="font-semibold text-gray-700"><?= $_SESSION['admin_username']; ?></span></p>
  </div>
</aside>


  <!-- Main -->
  <main class="flex-1 p-8 overflow-auto">
    <h1 class="text-2xl font-bold mb-6">All Bookings</h1>

    <div class="flex justify-between items-center mb-4">
      <a href="more.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded font-semibold text-sm shadow">
        + Add Booking
      </a>
      <form method="GET" class="flex space-x-2">
        <input type="text" name="search" value="<?= htmlspecialchars($search); ?>" placeholder="Search by name or email"
               class="px-3 py-2 rounded border border-gray-300 text-sm w-64">
        <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded text-sm hover:bg-black">Search</button>
      </form>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full table-auto border border-gray-200 shadow-lg rounded-lg bg-white">
        <thead class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm uppercase">
          <tr>
            <th class="px-4 py-3 text-left">#</th>
            <th class="px-4 py-3 text-left">Service</th>
            <th class="px-4 py-3 text-left">Name</th>
            <th class="px-4 py-3 text-left">Email</th>
            <th class="px-4 py-3 text-left">Phone</th>
            <th class="px-4 py-3 text-left">Booking Date</th>
            <th class="px-4 py-3 text-left">Booked At</th>
            <th class="px-4 py-3 text-left">Action</th>
          </tr>
        </thead>
        <tbody class="text-gray-700 text-sm">
          <?php
          $i = $offset + 1;
          while ($row = $result->fetch_assoc()):
          ?>
          <tr class="hover:bg-gray-50 border-b border-gray-200">
            <td class="px-4 py-3"><?= $i++; ?></td>
            <td class="px-4 py-3 font-medium"><?= htmlspecialchars($row['service_title']); ?></td>
            <td class="px-4 py-3"><?= htmlspecialchars($row['name']); ?></td>
            <td class="px-4 py-3"><?= htmlspecialchars($row['email']); ?></td>
            <td class="px-4 py-3"><?= htmlspecialchars($row['phone']); ?></td>
            <td class="px-4 py-3"><?= htmlspecialchars($row['booking_date']); ?></td>
            <td class="px-4 py-3 text-sm"><?= date('d M Y, h:i A', strtotime($row['created_at'])); ?></td>
            <td class="px-4 py-3 flex space-x-2">
              <a href="edit-booking.php?id=<?= $row['id']; ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">Edit</a>
              <form action="delete-booking.php" method="POST" onsubmit="return confirm('Are you sure to delete this booking?');">
                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Delete</button>
              </form>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="mt-6 flex justify-center items-center space-x-2">
        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
          <a href="?page=<?= $p; ?>&search=<?= urlencode($search); ?>"
             class="px-3 py-1 rounded border <?= $p == $page ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100'; ?>">
            <?= $p; ?>
          </a>
        <?php endfor; ?>
      </div>
    </div>
  </main>
</body>
</html>
