<?php
require_once 'config.php';

function requireLogin(): void {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}

function requireRole(string $role): void {
    requireLogin();
    if ($_SESSION['user']['role'] !== $role) {
        http_response_code(403);
        exit('Forbidden: insufficient privileges.');
    }
}