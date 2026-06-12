<?php
define('APP_ROOT', dirname(__DIR__));
require_once APP_ROOT . '/src/TaskManager.php';

$manager  = new TaskManager(APP_ROOT . '/data');
$tasks    = $manager->getAll();
$pageTitle = 'All Tasks';

// Handle quick status toggle from list view
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_id'])) {
    $manager->complete($_POST['complete_id']);
    header('Location: /');
    exit;
}

require APP_ROOT . '/templates/header.php';
?>

<div class="page-header">
    <h1>Task List</h1>
    <span class="badge"><?= count($tasks) ?> tasks</span>
</div>

<?php if (empty($tasks)): ?>
    <div class="empty-state">
        <p>No tasks yet. <a href="/add.php">Add your first task →</a></p>
    </div>
<?php else: ?>
    <div class="task-grid">
        <?php foreach ($tasks as $task): ?>
        <div class="task-card priority-<?= $task['priority'] ?> status-<?= $task['status'] ?>">
            <div class="task-card-header">
                <a href="/detail.php?id=<?= urlencode($task['id']) ?>" class="task-title">
                    <?= $task['title'] ?>
                </a>
                <span class="priority-badge"><?= strtoupper($task['priority']) ?></span>
            </div>
            <p class="task-description"><?= $task['description'] ?></p>
            <div class="task-meta">
                <span class="task-date">📅 <?= $task['created_at'] ?></span>
                <span class="task-status <?= $task['status'] ?>"><?= ucfirst($task['status']) ?></span>
            </div>
            <div class="task-actions">
                <?php if ($task['status'] === 'pending'): ?>
                <form method="POST" style="display:inline">
                    <input type="hidden" name="complete_id" value="<?= $task['id'] ?>">
                    <button type="submit" class="btn btn-success">✓ Done</button>
                </form>
                <?php endif; ?>
                <a href="/delete.php?id=<?= urlencode($task['id']) ?>" class="btn btn-danger"
                   onclick="return confirm('Delete this task?')">Delete</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require APP_ROOT . '/templates/footer.php'; ?>