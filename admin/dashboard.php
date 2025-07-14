<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

include '../includes/db.php'; // ✅ adjust if your db path is different

// Fetch Total Bookings
$bookingQuery = $conn->query("SELECT COUNT(*) AS total_bookings FROM bookings");
$totalBookings = $bookingQuery->fetch_assoc()['total_bookings'];

// Fetch Total Services
$serviceQuery = $conn->query("SELECT COUNT(*) AS total_services FROM services");
$totalServices = $serviceQuery->fetch_assoc()['total_services'];

// Fetch Today’s Bookings
$today = date('Y-m-d');
$todayQuery = $conn->query("SELECT COUNT(*) AS todays_bookings FROM bookings WHERE DATE(created_at) = '$today'");
$todaysBookings = $todayQuery->fetch_assoc()['todays_bookings'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - ServiceBook</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen bg-gray-100">

  <!-- ✅ Sidebar -->
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


  <!-- ✅ Main Content -->
  <main class="flex-1 p-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-4">Welcome, <?= $_SESSION['admin_username']; ?> </h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
      <!-- Total Bookings -->
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-700">Total Bookings</h2>
        <p class="text-3xl font-bold text-indigo-600 mt-2"><?= $totalBookings; ?></p>
      </div>

      <!-- Total Services -->
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-700">Total Services</h2>
        <p class="text-3xl font-bold text-indigo-600 mt-2"><?= $totalServices; ?></p>
      </div>

      <!-- Today’s Bookings -->
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-700">Today’s Bookings</h2>
        <p class="text-3xl font-bold text-indigo-600 mt-2"><?= $todaysBookings; ?></p>
      </div>
    </div>
  </main>

</body>
</html>
