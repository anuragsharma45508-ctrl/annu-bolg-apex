<?php
require_once 'config.php';

// Destroy session
$_SESSION = [];
session_unset();
session_destroy();

// Redirect to homepage
header('Location: index.php');
exit;