<?php
// Prevent direct access to template files
if (!defined('APP_ROOT')) {
    http_response_code(403);
    exit('Forbidden');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'PHP Task Manager', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<nav class="navbar">
    <div class="nav-container">
        <a href="/" class="nav-brand">⚡ PHP TaskApp</a>
        <div class="nav-links">
            <a href="/">All Tasks</a>
            <a href="/add.php">+ New Task</a>
        </div>
    </div>
</nav>
<main class="container">