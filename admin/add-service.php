<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: login.php');
  exit;
}
include '../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title']);
  $description = trim($_POST['description']);

  // Image Upload
  $image = $_FILES['image']['name'];
  $tmp_name = $_FILES['image']['tmp_name'];
  $upload_dir = '../assets/images/';
  $target_path = $upload_dir . basename($image);

  if ($title && $description && $image) {
    if (move_uploaded_file($tmp_name, $target_path)) {
      $stmt = $conn->prepare("INSERT INTO services (title, description, image) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $title, $description, $image);
      if ($stmt->execute()) {
        header("Location: services.php");
        exit;
      } else {
        $error = "Error adding service to the database.";
      }
    } else {
      $error = "Failed to upload image.";
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
  <title>Add Service - Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
  <div class="max-w-xl mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold text-indigo-700 mb-6">Add New Service</h2>

    <?php if ($error): ?>
      <div class="text-red-600 mb-4"><?= $error; ?></div>
    <?php endif; ?>
    

    <form method="POST" enctype="multipart/form-data" class="space-y-5">
      <input type="text" name="title" placeholder="Service Title" class="w-full border px-4 py-2 rounded" required>
      <textarea name="description" placeholder="Service Description" class="w-full border px-4 py-2 rounded" rows="4" required></textarea>
      <input type="file" name="image" class="w-full border px-4 py-2 rounded bg-white" required>
      <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded font-semibold">Add Service</button>
    </form>
  </div>
</body>
</html>
