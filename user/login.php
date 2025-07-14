<?php
session_start();
include '../includes/db.php';

$login_error = '';
$register_error = '';
$register_success = '';

// Handle Login
if (isset($_POST['login'])) {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      $_SESSION['user_logged_in'] = true;
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_name'] = $user['name'];
      $_SESSION['user_email'] = $user['email'];
      header("Location: dashboard.php");
      exit;
    } else {
      $login_error = "Invalid email or password.";
    }
  } else {
    $login_error = "User not found.";
  }
}

// Handle Registration
if (isset($_POST['register'])) {
  $name = trim($_POST['reg_name']);
  $email = trim($_POST['reg_email']);
  $password = password_hash($_POST['reg_password'], PASSWORD_DEFAULT);

  $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $register_error = "Email already registered.";
  } else {
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);
    if ($stmt->execute()) {
      $register_success = "Registration successful! You can now login.";
    } else {
      $register_error = "Registration failed. Try again.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Login / Register</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    function toggleTab(tab) {
      document.getElementById('loginTab').classList.add('hidden');
      document.getElementById('registerTab').classList.add('hidden');
      document.getElementById(tab).classList.remove('hidden');

      document.getElementById('btnLogin').classList.remove('bg-indigo-600', 'text-white');
      document.getElementById('btnRegister').classList.remove('bg-indigo-600', 'text-white');
      if (tab === 'loginTab') {
        document.getElementById('btnLogin').classList.add('bg-indigo-600', 'text-white');
      } else {
        document.getElementById('btnRegister').classList.add('bg-indigo-600', 'text-white');
      }
    }
  </script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">

  <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
    <div class="flex mb-6 space-x-2">
      <button id="btnLogin" onclick="toggleTab('loginTab')" class="w-1/2 bg-indigo-600 text-white py-2 rounded font-semibold">Login</button>
      <button id="btnRegister" onclick="toggleTab('registerTab')" class="w-1/2 bg-gray-200 text-gray-700 py-2 rounded font-semibold">Register</button>
    </div>

    <!-- Login Form -->
    <div id="loginTab">
      <?php if ($login_error): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $login_error; ?></div>
      <?php endif; ?>
      <form method="POST" class="space-y-4">
        <input type="email" name="email" class="w-full border px-4 py-2 rounded" placeholder="Email" required>
        <input type="password" name="password" class="w-full border px-4 py-2 rounded" placeholder="Password" required>
        <button type="submit" name="login" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded font-semibold">Login</button>
      </form>
    </div>

    <!-- Register Form -->
    <div id="registerTab" class="hidden">
      <?php if ($register_error): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $register_error; ?></div>
      <?php endif; ?>
      <?php if ($register_success): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $register_success; ?></div>
      <?php endif; ?>
      <form method="POST" class="space-y-4">
        <input type="text" name="reg_name" class="w-full border px-4 py-2 rounded" placeholder="Full Name" required>
        <input type="email" name="reg_email" class="w-full border px-4 py-2 rounded" placeholder="Email" required>
        <input type="password" name="reg_password" class="w-full border px-4 py-2 rounded" placeholder="Password" required>
        <button type="submit" name="register" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded font-semibold">Register</button>
      </form>
    </div>
  </div>

  <script>
    // Default tab = login
    toggleTab('loginTab');
  </script>
</body>
</html>
