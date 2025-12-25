<?php
require 'db.php';
include 'header.php';

if (!isset($_SESSION['user'])) {
  echo '<p>Please <a href="login.php">login</a> to edit posts.</p>';
  include 'footer.php'; exit;
}

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ?');
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) { echo '<p>Post not found.</p>'; include 'footer.php'; exit; }

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $content = trim($_POST['content'] ?? '');

  if ($title === '' || $content === '') {
    $errors[] = 'Title and content are required.';
  } else {
    $upd = $pdo->prepare('UPDATE posts SET title = ?, content = ? WHERE id = ?');
    $upd->execute([$title, $content, $id]);
    header('Location: index.php');
    exit;
  }
}
?>
<h2>Edit Post</h2>
<?php foreach ($errors as $e): ?>
  <p class="error"><?php echo htmlspecialchars($e); ?></p>
<?php endforeach; ?>

<form method="post">
  <label>Title</label>
  <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
  <label>Content</label>
  <textarea name="content" rows="6" required><?php echo htmlspecialchars($post['content']); ?></textarea>
  <button type="submit">Update</button>
</form>
<?php include 'footer.php'; ?>