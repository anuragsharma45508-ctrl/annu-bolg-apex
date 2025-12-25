<?php
require 'db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
  $del = $pdo->prepare('DELETE FROM posts WHERE id = ?');
  $del->execute([$id]);
}
header('Location: index.php');