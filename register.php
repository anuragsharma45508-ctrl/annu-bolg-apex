<?php
require 'db.php';
include 'header.php';

$errors = [];
$done = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm  = $_POST['confirm'] ?? '';

  if ($username === '' || $password === '') {
    $errors[] = 'Username and password are required.';
  }
  if ($password !== $confirm) {
    $errors[] = 'Passwords do not match.';
  }

  if (!$errors) {
    // Check username uniqueness
    $check = $pdo->prepare('SELECT id FROM users WHERE username = ?');
    $check->execute([$username]);
    if ($check->fetch()) {
      $errors[] = 'Username is already taken.';
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $ins = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
      $ins->execute([$username, $hash]);
      $done = true;
    }
  }
}
?>
<h2>Register</h2>
<?php foreach ($errors as $e): ?>
  <p class="error"><?php echo htmlspecialchars($e); ?></p>
<?php endforeach; ?>
<?php if ($done): ?>
  <p class="success">Registration successful. <a href="login.php">Login now</a>.</p>
<?php endif; ?>

<form method="post" autocomplete="off">
  <label>Username</label>
  <input type="text" name="username" required>

  <label>Password</label>
  <input type="password" name="password" required>

  <label>Confirm password</label>
  <input type="password" name="confirm" required>

  <button type="submit">Register</button>
</form>
<?php include 'footer.php'; ?>