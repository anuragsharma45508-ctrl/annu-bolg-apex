<?php
require_once 'auth.php';
requireLogin(); // any logged-in user can add posts

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === '' || strlen($title) < 3) {
        $error = 'Title too short.';
    } elseif ($content === '' || strlen($content) < 5) {
        $error = 'Content too short.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO posts (title, content, created_at, created_by) VALUES (?, ?, NOW(), ?)');
$stmt->execute([$title, $content, $_SESSION['user']['username']]);
        header('Location: index.php?msg=created');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Add Post</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card p-4 shadow-sm">
          <h4 class="mb-3">Add New Post</h4>
          <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>
          <form method="post">
            <div class="mb-3">
              <label class="form-label">Title</label>
              <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Content</label>
              <textarea name="content" class="form-control" rows="5" required></textarea>
            </div>
            <button class="btn btn-primary w-100" type="submit">Save Post</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
