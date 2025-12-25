<?php
require 'db.php';
include 'header.php';

if (!isset($_SESSION['user'])) {
  echo '<p>Please <a href="login.php">login</a> to create posts.</p>';
  include 'footer.php'; exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $content = trim($_POST['content'] ?? '');

  if ($title === '' || $content === '') {
    $errors[] = 'Title and content are required.';
  } else {
    $stmt = $pdo->prepare('INSERT INTO posts (title, content) VALUES (?, ?)');
    $stmt->execute([$title, $content]);
    header('Location: index.php');
    exit;
  }
}
?>
<h2>New Post</h2>
<?php foreach ($errors as $e): ?>
  <p class="error"><?php echo htmlspecialchars($e); ?></p>
<?php endforeach; ?>

<form method="post">
  <label>Title</label>
  <input type="text" name="title" required>
  <label>Content</label>
  <textarea name="content" rows="6" required></textarea>
  <button type="submit">Create</button>
</form>
<?php include 'footer.php'; ?>