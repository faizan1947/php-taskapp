<?php
define('APP_ROOT', dirname(__DIR__));
require_once APP_ROOT . '/src/TaskManager.php';

$manager = new TaskManager(APP_ROOT . '/data');
$id      = $_GET['id'] ?? '';
$task    = $manager->getById($id);

if (!$task) {
    http_response_code(404);
    $pageTitle = '404 Not Found';
    require APP_ROOT . '/templates/header.php';
    echo '<div class="alert alert-error"><p>Task not found. <a href="/">Go back →</a></p></div>';
    require APP_ROOT . '/templates/footer.php';
    exit;
}

$pageTitle = $task['title'];
require APP_ROOT . '/templates/header.php';
?>

<div class="page-header">
    <h1><?= $task['title'] ?></h1>
    <span class="priority-badge priority-<?= $task['priority'] ?>"><?= strtoupper($task['priority']) ?></span>
</div>

<div class="detail-card">
    <div class="detail-row">
        <span class="detail-label">Status</span>
        <span class="task-status <?= $task['status'] ?>"><?= ucfirst($task['status']) ?></span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Description</span>
        <span><?= nl2br($task['description'] ?: '<em>No description</em>') ?></span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Created</span>
        <span><?= $task['created_at'] ?></span>
    </div>
    <?php if (isset($task['completed_at'])): ?>
    <div class="detail-row">
        <span class="detail-label">Completed</span>
        <span><?= $task['completed_at'] ?></span>
    </div>
    <?php endif; ?>
</div>

<div class="form-actions">
    <a href="/" class="btn btn-secondary">← Back</a>
    <a href="/delete.php?id=<?= urlencode($task['id']) ?>" class="btn btn-danger"
       onclick="return confirm('Delete this task?')">Delete</a>
</div>

<?php require APP_ROOT . '/templates/footer.php'; ?>