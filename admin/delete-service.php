<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: login.php');
  exit;
}
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = intval($_POST['id']);

  // First, get the image name to delete the file (optional cleanup)
  $stmt = $conn->prepare("SELECT image FROM services WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $service = $result->fetch_assoc();

  if ($service) {
    $imagePath = '../assets/images/' . $service['image'];
    if (file_exists($imagePath)) {
      unlink($imagePath); // Delete image from server
    }

    // Now delete service from DB
    $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
  }
}

header("Location: services.php"); // 🔁 Redirect back to list
exit;
