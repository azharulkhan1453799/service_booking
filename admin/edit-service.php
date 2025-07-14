<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: login.php');
  exit;
}
include '../includes/db.php';

$id = intval($_GET['id']);
$error = '';

// Fetch existing service
$stmt = $conn->prepare("SELECT * FROM services WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$service = $result->fetch_assoc();

if (!$service) {
  die("Service not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title']);
  $description = trim($_POST['description']);
  $newImage = $_FILES['image']['name'];
  $image = $service['image'];

  // If new image uploaded
  if (!empty($newImage)) {
    $upload_dir = '../assets/images/';
    $target_path = $upload_dir . basename($newImage);
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
      $image = $newImage;
    } else {
      $error = "Image upload failed.";
    }
  }

  if ($title && $description && $image) {
    $stmt = $conn->prepare("UPDATE services SET title=?, description=?, image=? WHERE id=?");
    $stmt->bind_param("sssi", $title, $description, $image, $id);
    if ($stmt->execute()) {
      header("Location: services.php");
      exit;
    } else {
      $error = "Failed to update service.";
    }
  } else {
    $error = "All fields are required.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Service - Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
  <div class="max-w-xl mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold text-yellow-600 mb-6">Edit Service</h2>

    <?php if ($error): ?>
      <div class="text-red-600 mb-4"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-5">
      <input type="text" name="title" value="<?= htmlspecialchars($service['title']); ?>" class="w-full border px-4 py-2 rounded" required>
      <textarea name="description" rows="4" class="w-full border px-4 py-2 rounded" required><?= htmlspecialchars($service['description']); ?></textarea>

      <div>
        <label class="block text-sm mb-2 font-semibold">Current Image:</label>
        <img src="../assets/images/<?= $service['image']; ?>" class="h-24 rounded mb-2" alt="Service Image">
        <input type="file" name="image" class="w-full border px-4 py-2 rounded bg-white">
        <small class="text-gray-500">Leave blank to keep the current image.</small>
      </div>

      <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded font-semibold">Update Service</button>
    </form>
  </div>
</body>
</html>
