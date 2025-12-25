<?php
require 'db.php';
include 'header.php';

// ---------------- CONFIG ----------------
$limit = 5; // posts per page
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// ---------------- SEARCH ----------------
$search = trim($_GET['q'] ?? '');
$where = '';
$params = [];

if ($search !== '') {
  $where = 'WHERE title LIKE ? OR content LIKE ?';
  $params[] = "%$search%";
  $params[] = "%$search%";
}

// ---------------- COUNT POSTS ----------------
$countSql = "SELECT COUNT(*) FROM posts $where";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$total = (int)$countStmt->fetchColumn();
$totalPages = max(1, (int)ceil($total / $limit));

// ---------------- FETCH POSTS ----------------
$listSql = "SELECT * FROM posts $where ORDER BY created_at DESC LIMIT ? OFFSET ?";
$listStmt = $pdo->prepare($listSql);

// bind search params first
$i = 1;
foreach ($params as $p) {
  $listStmt->bindValue($i++, $p, PDO::PARAM_STR);
}
$listStmt->bindValue($i++, $limit, PDO::PARAM_INT);
$listStmt->bindValue($i++, $offset, PDO::PARAM_INT);

$listStmt->execute();
$posts = $listStmt->fetchAll();
?>

<div class="container py-4">
  <h2 class="mb-4">All Posts</h2>

  <!-- Search bar -->
  <form method="get" action="index.php" class="mb-3 d-flex gap-2">
    <input type="text" name="q" class="form-control" placeholder="Search posts..."
           value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit" class="btn btn-primary">Search</button>
    <?php if ($search !== ''): ?>
      <a href="index.php" class="btn btn-secondary">Clear</a>
    <?php endif; ?>
  </form>

  <?php if (empty($posts)): ?>
    <div class="alert alert-info">
      No posts<?php echo $search ? ' found for “' . htmlspecialchars($search) . '”' : ''; ?>.
    </div>
  <?php endif; ?>

  <?php foreach ($posts as $p): ?>
  <div class="card mb-3 shadow-sm">
    <div class="card-body">
      <h5 class="card-title"><?php echo htmlspecialchars($p['title']); ?></h5>
      <p class="card-text"><?php echo nl2br(htmlspecialchars($p['content'])); ?></p>
      <small class="text-muted">Created at: <?php echo htmlspecialchars($p['created_at']); ?></small><br>
      <small class="text-muted">Posted by: <?php echo htmlspecialchars($p['created_by'] ?? 'Unknown'); ?></small>
      <div class="mt-2 d-flex gap-2">
        <!-- Always visible -->
        <a href="view.php?id=<?php echo (int)$p['id']; ?>" class="btn btn-sm btn-outline-primary">View</a>

        <!-- Only for admins -->
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
          <a href="edit.php?id=<?php echo (int)$p['id']; ?>" class="btn btn-sm btn-outline-warning">Edit</a>
          <a href="delete.php?id=<?php echo (int)$p['id']; ?>" class="btn btn-sm btn-outline-danger"
             onclick="return confirm('Delete this post?');">Delete</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
<?php endforeach; ?>

  <!-- Pagination -->
  <?php if ($totalPages > 1): ?>
    <nav aria-label="Page navigation">
      <ul class="pagination">
        <?php
          $qParam = $search !== '' ? '&q=' . urlencode($search) : '';

          // Previous link
          if ($page > 1) {
            echo "<li class='page-item'><a class='page-link' href='index.php?page=" . ($page-1) . "$qParam'>&laquo; Prev</a></li>";
          }

          // Page numbers
          for ($i = 1; $i <= $totalPages; $i++) {
            $active = $i == $page ? "active" : "";
            echo "<li class='page-item $active'><a class='page-link' href='index.php?page=$i$qParam'>$i</a></li>";
          }

          // Next link
          if ($page < $totalPages) {
            echo "<li class='page-item'><a class='page-link' href='index.php?page=" . ($page+1) . "$qParam'>Next &raquo;</a></li>";
          }
        ?>
      </ul>
    </nav>
  <?php endif; ?>
</div>

<?php include 'footer.php'; ?>