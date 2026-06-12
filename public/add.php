<?php
define('APP_ROOT', dirname(__DIR__));
require_once APP_ROOT . '/src/TaskManager.php';

$errors   = [];
$pageTitle = 'Add Task';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check would go here in a real app (sessions + token)
    $title       = trim($_POST['title']       ?? '');
    $description = trim($_POST['description'] ?? '');
    $priority    = trim($_POST['priority']    ?? 'medium');

    if (empty($title)) {
        $errors[] = 'Title is required.';
    } elseif (strlen($title) > 100) {
        $errors[] = 'Title must be 100 characters or fewer.';
    }

    if (strlen($description) > 500) {
        $errors[] = 'Description must be 500 characters or fewer.';
    }

    if (empty($errors)) {
        $manager = new TaskManager(APP_ROOT . '/data');
        $manager->add($title, $description, $priority);
        header('Location: /?added=1');
        exit;
    }
}

require APP_ROOT . '/templates/header.php';
?>

<div class="page-header">
    <h1>New Task</h1>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-error">
    <ul><?php foreach ($errors as $e): ?><li><?= $e ?></li><?php endforeach; ?></ul>
</div>
<?php endif; ?>

<div class="form-card">
    <form method="POST" action="/add.php">
        <div class="form-group">
            <label for="title">Title <span class="required">*</span></label>
            <input type="text" id="title" name="title" maxlength="100" required
                   value="<?= htmlspecialchars($_POST['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" maxlength="500"><?=
                htmlspecialchars($_POST['description'] ?? '', ENT_QUOTES, 'UTF-8')
            ?></textarea>
        </div>

        <div class="form-group">
            <label for="priority">Priority</label>
            <select id="priority" name="priority">
                <option value="low"    <?= ($_POST['priority'] ?? '') === 'low'    ? 'selected' : '' ?>>Low</option>
                <option value="medium" <?= ($_POST['priority'] ?? 'medium') === 'medium' ? 'selected' : '' ?>>Medium</option>
                <option value="high"   <?= ($_POST['priority'] ?? '') === 'high'   ? 'selected' : '' ?>>High</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Add Task</button>
            <a href="/" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require APP_ROOT . '/templates/footer.php'; ?>