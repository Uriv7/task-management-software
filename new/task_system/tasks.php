<?php
require_once 'includes/header.php';

$db = Database::getInstance();
$conn = $db->getConnection();

// Get all tasks with employee names
$tasks = $conn->query("
    SELECT t.*, e.name as employee_name 
    FROM tasks t 
    LEFT JOIN employees e ON t.employee_id = e.id 
    ORDER BY t.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Get all employees for the form
$employees = $conn->query("SELECT id, name FROM employees ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
    <div class="col-md-4">
        <div class="form-container">
            <h3><i class="fas fa-plus-circle me-2"></i>Add New Task</h3>
            <form action="actions/add_task.php" method="POST">
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
                    <select class="form-select" id="employee_id" name="employee_id" required>
                        <option value="">Select Employee</option>
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?= $employee['id'] ?>">
                                <?= htmlspecialchars($employee['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="priority" class="form-label">Priority</label>
                    <select class="form-select" id="priority" name="priority" required>
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="deadline" class="form-label">Deadline</label>
                    <input type="date" class="form-control" id="deadline" name="deadline" required>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Add Task
                </button>
            </form>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="table-container">
            <h3 class="mb-4"><i class="fas fa-list me-2"></i>Tasks List</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Assigned To</th>
                            <th>Deadline</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($task['title']) ?></strong>
                                    <small class="d-block text-muted"><?= htmlspecialchars(substr($task['description'], 0, 50)) ?>...</small>
                                </td>
                                <td><?= htmlspecialchars($task['employee_name'] ?? 'Unassigned') ?></td>
                                <td><?= date('M d, Y', strtotime($task['deadline'])) ?></td>
                                <td>
                                    <span class="priority-<?= $task['priority'] ?>">
                                        <i class="fas fa-flag me-1"></i><?= ucfirst($task['priority']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge status-<?= $task['status'] ?>">
                                        <?= ucfirst($task['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="actions/update_status.php?id=<?= $task['id'] ?>&status=in_progress" 
                                           class="btn btn-sm btn-info btn-action" title="Mark In Progress">
                                            <i class="fas fa-play"></i>
                                        </a>
                                        <a href="actions/update_status.php?id=<?= $task['id'] ?>&status=completed" 
                                           class="btn btn-sm btn-success btn-action" title="Mark Complete">
                                            <i class="fas fa-check"></i>
                                        </a>
                                        <a href="actions/delete_task.php?id=<?= $task['id'] ?>" 
                                           class="btn btn-sm btn-danger btn-action"
                                           onclick="return confirm('Are you sure you want to delete this task?')"
                                           title="Delete Task">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
