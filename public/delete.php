<?php
define('APP_ROOT', dirname(__DIR__));
require_once APP_ROOT . '/src/TaskManager.php';

// Only allow GET with valid ID (in a real app, use POST + CSRF token)
$id      = $_GET['id'] ?? '';
$manager = new TaskManager(APP_ROOT . '/data');
$manager->delete($id);

header('Location: /?deleted=1');
exit;