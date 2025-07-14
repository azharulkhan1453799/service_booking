<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: login.php');
  exit;
}
include '../includes/db.php';

// Search & Pagination setup
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Count total records
$count_sql = "SELECT COUNT(*) AS total FROM services";
if ($search !== '') {
  $count_sql .= " WHERE title LIKE ?";
  $count_stmt = $conn->prepare($count_sql);
  $search_param = '%' . $search . '%';
  $count_stmt->bind_param("s", $search_param);
} else {
  $count_stmt = $conn->prepare($count_sql);
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total / $limit);

// Fetch services with limit/offset
$sql = "SELECT * FROM services";
$params = [];
$types = "";

if ($search !== '') {
  $sql .= " WHERE title LIKE ?";
  $params[] = '%' . $search . '%';
  $types .= "s";
}

$sql .= " ORDER BY id DESC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$services = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Services - Admin</title>
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
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">All Services</h1>
    <a href="add-service.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded shadow text-sm font-semibold">+ Add New Service</a>
  </div>

  <!-- Search Form -->
  <form method="GET" class="mb-4 flex">
    <input type="text" name="search" value="<?= htmlspecialchars($search); ?>" placeholder="Search services..." class="border px-4 py-2 rounded-l w-1/3">
    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-r">Search</button>
  </form>

  <!-- Service Table -->
  <div class="overflow-x-auto">
  <table class="min-w-full table-auto border border-gray-200 bg-white shadow rounded">
    <thead class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm uppercase">
      <tr>
        <th class="px-4 py-3 text-left">#</th>
        <th class="px-4 py-3 text-left">Image</th>
        <th class="px-4 py-3 text-left">Title</th>
        <th class="px-4 py-3 text-left">Price</th>
        <th class="px-4 py-3 text-left">Description</th>
        <th class="px-4 py-3 text-left">Actions</th>
      </tr>
    </thead>
    <tbody class="text-gray-700 text-sm">
      <?php
      $i = $offset + 1;
      while ($row = $services->fetch_assoc()):
      ?>
      <tr class="border-b hover:bg-gray-50">
        <td class="px-4 py-3"><?= $i++; ?></td>
        <td class="px-4 py-3">
          <img src="../assets/images/<?= htmlspecialchars($row['image']); ?>" alt="img" class="h-12 w-12 object-cover rounded">
        </td>
        <td class="px-4 py-3 font-medium"><?= htmlspecialchars($row['title']); ?></td>
        <td class="px-4 py-3">₹<?= number_format($row['price'], 2); ?></td>
        <td class="px-4 py-3"><?= substr(htmlspecialchars($row['description']), 0, 50); ?>...</td>
        <td class="px-4 py-3 space-x-2">
          <a href="edit-service.php?id=<?= $row['id']; ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">Edit</a>
          <form action="delete-service.php" method="POST" class="inline" onsubmit="return confirm('Delete this service?');">
            <input type="hidden" name="id" value="<?= $row['id']; ?>">
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Delete</button>
          </form>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

  <!-- Pagination -->
  <div class="mt-6 flex justify-center items-center space-x-2 text-sm">
    <?php if ($page > 1): ?>
      <a href="?search=<?= urlencode($search); ?>&page=<?= $page - 1; ?>" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">Previous</a>
    <?php endif; ?>
    <?php for ($p = 1; $p <= $total_pages; $p++): ?>
      <a href="?search=<?= urlencode($search); ?>&page=<?= $p; ?>" class="px-3 py-1 rounded <?= $p == $page ? 'bg-indigo-600 text-white' : 'bg-gray-200 hover:bg-gray-300'; ?>"><?= $p; ?></a>
    <?php endfor; ?>
    <?php if ($page < $total_pages): ?>
      <a href="?search=<?= urlencode($search); ?>&page=<?= $page + 1; ?>" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">Next</a>
    <?php endif; ?>
  </div>
</main>
</body>
</html>
