<?php
include 'includes/db.php';

// Handle Search
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Pagination
$limit = 6;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Total Rows
$where = $search ? "WHERE title LIKE '%$search%' OR description LIKE '%$search%'" : "";
$totalQuery = $conn->query("SELECT COUNT(*) as total FROM services $where");
$totalRows = $totalQuery->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Fetch Services
$query = "SELECT * FROM services $where LIMIT $start, $limit";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Online Service Booking</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body { font-family: 'Segoe UI', sans-serif; }
  </style>
</head>
<body class="bg-gradient-to-br from-gray-100 to-white text-gray-900">

<!-- ✅ Navbar -->
 <nav class="bg-white shadow sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">

      <!-- Left: Logo -->
      <div class="flex-shrink-0">
        <a href="index.php" class="text-3xl font-extrabold text-indigo-700">ServiceBook</a>
      </div>

      <!-- Center: Search -->
      <div class="flex-grow flex justify-center">
        <form method="GET" action="index.php" class="hidden md:flex items-center gap-2 w-full max-w-md">
          <input type="text" name="search" value="<?= htmlspecialchars($search); ?>" placeholder="Search services..."
            class="w-full px-4 py-2 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
          <button type="submit"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium text-sm">Search</button>
        </form>
      </div>

      <!-- Right: Nav Links -->
      <div class="hidden md:flex space-x-6 text-lg items-center">
        <a href="index.php" class="text-indigo-700 font-semibold border-b-2 border-indigo-700">Home</a>
        <a href="services.php" class="hover:text-indigo-600 font-medium">Services</a>
        <a href="user/login.php" class="hover:text-indigo-600 font-medium">Login</a>
        <a href="user/register.php" class="hover:text-indigo-600 font-medium">Register</a>
        <a href="contact.php" class="hover:text-indigo-600 font-medium">Contact</a>
      </div>

    </div>
  </div>
</nav>


<!-- ✅ Hero Section -->
<section class="relative h-[85vh] bg-cover bg-center text-white flex items-center justify-center"
  style="background-image: url('assets/images/hero.jpg');">
  
  <!-- Overlay -->
  <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-transparent"></div>

  <!-- Content Box -->
  <div class="relative z-10 max-w-3xl text-center px-6">
    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-6 tracking-wide">
      Experience Seamless <span class="text-indigo-500">Service Booking</span> at Your Fingertips
    </h1>
    <p class="text-lg sm:text-xl mb-8 text-gray-200">
      Book top-rated professionals for cleaning, plumbing, electrical, and more — trusted by thousands across India.
    </p>
    <a href="services.php"
      class="inline-block bg-indigo-600 hover:bg-indigo-700 transition px-8 py-3 text-lg font-semibold rounded-full shadow-lg">
      Explore Services
    </a>
  </div>
</section>





<!-- ✅ Featured Services -->
<div class="max-w-7xl mx-auto px-4 py-16">
  <h2 class="text-4xl font-bold text-center text-gray-800 mb-12">🌟 Featured Services</h2>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
    <?php while($row = $result->fetch_assoc()): ?>
    <div class="bg-white border rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 overflow-hidden">
      <img src="assets/images/<?= $row['image']; ?>" alt="<?= $row['title']; ?>" class="w-full h-56 object-cover">
      <div class="p-6 space-y-3">
        <h3 class="text-xl font-semibold text-gray-900"><?= $row['title']; ?></h3>
        <p class="text-sm text-gray-600"><?= substr($row['description'], 0, 100); ?>...</p>
        <p class="text-indigo-700 font-bold text-lg">₹<?= $row['price']; ?></p>
        <a href="service-detail.php?id=<?= $row['id']; ?>" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-full font-semibold transition duration-300">Book Now</a>
      </div>
    </div>
    <?php endwhile; ?>
  </div>

  <!-- ✅ Pagination -->
  <div class="flex justify-center items-center mt-12 space-x-2">
    <?php if ($page > 1): ?>
      <a href="?search=<?= $search ?>&page=<?= $page - 1 ?>" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg">&larr; Prev</a>
    <?php endif; ?>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a href="?search=<?= $search ?>&page=<?= $i ?>" class="px-4 py-2 <?= ($i == $page) ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-800' ?> rounded-lg hover:bg-indigo-500 hover:text-white"><?= $i ?></a>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?>
      <a href="?search=<?= $search ?>&page=<?= $page + 1 ?>" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg">Next &rarr;</a>
    <?php endif; ?>
  </div>
</div>

<!-- ✅ Footer -->
<footer class="bg-gray-900 text-gray-300 py-12 mt-10 shadow-inner">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-10 text-sm text-gray-400">

      <!-- Brand -->
      <div>
        <h3 class="text-xl font-bold text-white mb-4">ServiceBook</h3>
        <p class="leading-relaxed mb-4">Your trusted platform for booking quality home services — anytime, anywhere.</p>
        <p class="text-indigo-400 text-xs">&copy; <?= date('Y'); ?> ServiceBook. All rights reserved.</p>
      </div>

      <!-- Quick Links -->
      <div>
        <h4 class="text-lg font-semibold text-white mb-3">Quick Links</h4>
        <ul class="space-y-2">
          <li><a href="index.php" class="hover:text-white">Home</a></li>
          <li><a href="services.php" class="hover:text-white">Services</a></li>
          <li><a href="contact.php" class="hover:text-white">Contact</a></li>
          <li><a href="user/login.php" class="hover:text-white">Login</a></li>
          <li><a href="user/register.php" class="hover:text-white">Register</a></li>
        </ul>
      </div>

      <!-- Legal -->
      <div>
        <h4 class="text-lg font-semibold text-white mb-3">Legal</h4>
        <ul class="space-y-2">
          <li><a href="privacy.php" class="hover:text-white">Privacy Policy</a></li>
          <li><a href="terms.php" class="hover:text-white">Terms of Service</a></li>
          <li><a href="#" class="hover:text-white">Refund Policy</a></li>
          <li><a href="#" class="hover:text-white">Cookies</a></li>
        </ul>
      </div>

      <!-- Contact Info -->
      <div>
        <h4 class="text-lg font-semibold text-white mb-3">Contact Us</h4>
        <p class="mb-2"><i class="fas fa-envelope mr-2 text-indigo-400"></i>support@servicebook.com</p>
        <p class="mb-2"><i class="fas fa-phone mr-2 text-indigo-400"></i>+91 98765 43210</p>
        <p class="mb-4"><i class="fas fa-map-marker-alt mr-2 text-indigo-400"></i>Mumbai, India</p>
        <div class="flex space-x-4 text-xl">
          <a href="#" class="hover:text-white"><i class="fab fa-facebook"></i></a>
          <a href="#" class="hover:text-white"><i class="fab fa-instagram"></i></a>
          <a href="#" class="hover:text-white"><i class="fab fa-twitter"></i></a>
          <a href="#" class="hover:text-white"><i class="fab fa-linkedin"></i></a>
        </div>
      </div>

    </div>
  </div>
</footer>

<!-- Font Awesome for icons -->
<script src="https://kit.fontawesome.com/a2d04a36d4.js" crossorigin="anonymous"></script>


</body>
</html>
