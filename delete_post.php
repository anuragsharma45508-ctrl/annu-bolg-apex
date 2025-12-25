<?php
require_once 'auth.php';
requireRole('admin'); // only admin can delete

if (!isset($_GET['id'])) {
    exit('Invalid request');
}
$id = (int)$_GET['id'];

$stmt = $pdo->prepare('DELETE FROM posts WHERE id = ?');
$stmt->execute([$id]);

header('Location: index.php?msg=deleted');