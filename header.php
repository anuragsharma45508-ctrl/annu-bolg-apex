<?php
require_once 'db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>My Blog</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php">MyBlog</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <?php if (isset($_SESSION['user'])): ?>
            <li class="nav-item"><a class="nav-link" href="add.php">Add Post</a></li>
          <?php endif; ?>
        </ul>
        <form class="d-flex" method="get" action="index.php">
          <input class="form-control me-2" type="search" name="q" placeholder="Search"
                 value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
          <button class="btn btn-outline-light" type="submit">Search</button>
        </form>
        <ul class="navbar-nav ms-3">
          <?php if (isset($_SESSION['user'])): ?>
            <li class="nav-item">
              <span class="navbar-text text-white me-2">
                <?php echo htmlspecialchars($_SESSION['user']['username']); ?>
                (<?php echo htmlspecialchars($_SESSION['user']['role']); ?>)
              </span>
            </li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container py-4">