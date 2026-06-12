<?php

/**
 * TaskManager — Simple file-based task storage.
 * Uses JSON file in /data directory.
 * In production you'd replace this with a MySQL/RDS backend.
 */
class TaskManager
{
    private string $dataFile;

    public function __construct(string $dataDir)
    {
        // Ensure data directory exists with correct permissions
        if (!is_dir($dataDir)) {
            mkdir($dataDir, 0750, true);
        }
        $this->dataFile = rtrim($dataDir, '/') . '/tasks.json';

        // Create the file if it doesn't exist
        if (!file_exists($this->dataFile)) {
            file_put_contents($this->dataFile, json_encode([]));
        }
    }

    public function getAll(): array
    {
        $content = file_get_contents($this->dataFile);
        $tasks   = json_decode($content, true);
        return is_array($tasks) ? $tasks : [];
    }

    public function getById(string $id): ?array
    {
        foreach ($this->getAll() as $task) {
            if ($task['id'] === $id) {
                return $task;
            }
        }
        return null;
    }

    public function add(string $title, string $description, string $priority): array
    {
        $tasks  = $this->getAll();
        $task   = [
            'id'          => uniqid('task_', true),
            'title'       => htmlspecialchars(trim($title),       ENT_QUOTES, 'UTF-8'),
            'description' => htmlspecialchars(trim($description), ENT_QUOTES, 'UTF-8'),
            'priority'    => in_array($priority, ['low', 'medium', 'high']) ? $priority : 'medium',
            'status'      => 'pending',
            'created_at'  => date('Y-m-d H:i:s'),
        ];
        $tasks[] = $task;
        $this->save($tasks);
        return $task;
    }

    public function delete(string $id): bool
    {
        $tasks    = $this->getAll();
        $filtered = array_filter($tasks, fn($t) => $t['id'] !== $id);
        if (count($filtered) === count($tasks)) {
            return false; // not found
        }
        $this->save(array_values($filtered));
        return true;
    }

    public function complete(string $id): bool
    {
        $tasks = $this->getAll();
        foreach ($tasks as &$task) {
            if ($task['id'] === $id) {
                $task['status']       = 'completed';
                $task['completed_at'] = date('Y-m-d H:i:s');
                $this->save($tasks);
                return true;
            }
        }
        return false;
    }

    private function save(array $tasks): void
    {
        // Atomic write — write to temp file then rename to avoid corruption
        $tmp = $this->dataFile . '.tmp';
        file_put_contents($tmp, json_encode($tasks, JSON_PRETTY_PRINT));
        rename($tmp, $this->dataFile);
    }
}