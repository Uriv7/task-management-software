<?php
require_once '../config/db.php';

$stmt = $pdo->query("SELECT t.*, e.name as employee_name FROM tasks t LEFT JOIN employees e ON t.employee_id = e.id ORDER BY t.created_at DESC");
$tasks = $stmt->fetchAll();
?>

<div class="row">
    <div class="col-md-4">
        <div class="form-container">
            <h3>Add New Task</h3>
            <form id="taskForm">
                <div class="mb-3">
                    <label for="title" class="form-label">Task Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="employee_id" class="form-label">Assign To</label>
                    <select class="form-control" id="employee_id" name="employee_id" required>
                        <?php
                        $employees = $pdo->query("SELECT * FROM employees")->fetchAll();
                        foreach ($employees as $employee) {
                            echo "<option value='{$employee['id']}'>{$employee['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="deadline" class="form-label">Deadline</label>
                    <input type="date" class="form-control" id="deadline" name="deadline" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Task</button>
            </form>
        </div>
    </div>
    
    <div class="col-md-8">
        <h3>Tasks List</h3>
        <?php foreach ($tasks as $task): ?>
            <div class="card task-card">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($task['title']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($task['description']) ?></p>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Assigned to:</strong> <?= htmlspecialchars($task['employee_name']) ?></p>
                            <p><strong>Deadline:</strong> <?= htmlspecialchars($task['deadline']) ?></p>
                        </div>
                        <div class="col-md-6 text-end">
                            <span class="badge bg-<?= $task['status'] === 'completed' ? 'success' : ($task['status'] === 'in_progress' ? 'info' : 'warning') ?>">
                                <?= ucfirst(str_replace('_', ' ', $task['status'])) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
