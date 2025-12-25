<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    exit('Invalid request');
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare('SELECT id, title, content, created_at FROM posts WHERE id = ? LIMIT 1');
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    exit('Post not found.');
}
?>
<!DOCTYPE html>
<html>
<head>
  <title><?= htmlspecialchars($post['title']) ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="card shadow-sm">
      <div class="card-body">
        <h3 class="card-title"><?= htmlspecialchars($post['title']) ?></h3>
        <p class="card-text"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
        <small class="text-muted">Created at: <?= htmlspecialchars($post['created_at']) ?></small>
      </div>
    </div>
    <a href="index.php" class="btn btn-secondary mt-3">Back to posts</a>
  </div>
</body>
</html>