<?php include 'includes/db.php'; ?>
<?php
if (!isset($_GET['id'])) {
    header('Location: services.php');
    exit;
}
$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM services WHERE id = $id LIMIT 1");
if ($result->num_rows == 0) {
    echo "Service not found.";
    exit;
}
$service = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $service['title']; ?> - Book Service</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

  <!-- Navbar -->
  <nav class="bg-white shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16 items-center">
        <a href="index.php" class="text-2xl font-bold text-indigo-600">ServiceBook</a>
        <div class="space-x-6 hidden md:flex">
          <a href="index.php" class="hover:text-indigo-600 font-medium">Home</a>
          <a href="services.php" class="text-indigo-600 font-semibold">Services</a>
          <a href="user/login.php" class="hover:text-indigo-600 font-medium">Login</a>
          <a href="user/register.php" class="hover:text-indigo-600 font-medium">Register</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Service Details -->
  <div class="max-w-6xl mx-auto px-4 py-10 grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
    <div>
      <img src="assets/images/<?= $service['image']; ?>" alt="<?= $service['title']; ?>" class="w-full h-96 object-cover rounded-lg shadow">
    </div>
    <div>
      <h1 class="text-3xl font-bold mb-2"><?= $service['title']; ?></h1>
      <p class="text-gray-600 mb-4"><?= $service['description']; ?></p>
      <p class="text-xl text-indigo-600 font-bold mb-6">Price: ₹<?= $service['price']; ?></p>

      <!-- Booking Form -->
      <h2 class="text-xl font-semibold mb-2">Book This Service</h2>
      <form action="book-service.php" method="POST" class="space-y-4 bg-white p-6 rounded-lg shadow">
        <input type="hidden" name="service_id" value="<?= $service['id']; ?>">
        <div>
          <label class="block font-medium">Full Name</label>
          <input type="text" name="name" required class="w-full mt-1 border border-gray-300 px-4 py-2 rounded focus:ring focus:ring-indigo-200">
        </div>
        <div>
          <label class="block font-medium">Email</label>
          <input type="email" name="email" required class="w-full mt-1 border border-gray-300 px-4 py-2 rounded focus:ring focus:ring-indigo-200">
        </div>
        <div>
          <label class="block font-medium">Phone</label>
          <input type="text" name="phone" required class="w-full mt-1 border border-gray-300 px-4 py-2 rounded focus:ring focus:ring-indigo-200">
        </div>
        <div>
          <label class="block font-medium">Booking Date</label>
          <input type="date" name="booking_date" required class="w-full mt-1 border border-gray-300 px-4 py-2 rounded focus:ring focus:ring-indigo-200">
        </div>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded font-semibold">Confirm Booking</button>
      </form>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-gray-900 text-gray-300 py-8 mt-10">
    <div class="max-w-7xl mx-auto text-center space-y-2">
      <p>&copy; <?= date('Y'); ?> ServiceBook. All rights reserved.</p>
    </div>
  </footer>

</body>
</html>
