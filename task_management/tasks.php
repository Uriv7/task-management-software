<?php
require_once 'includes/header.php';
require_once 'config/Database.php';

$database = new Database();
$db = $database->connect();

// Get all tasks with employee names
$query = "SELECT t.*, e.name as employee_name 
          FROM tasks t 
          LEFT JOIN employees e ON t.employee_id = e.id 
          ORDER BY t.created_at DESC";
$stmt = $db->query($query);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get all employees for the form
$stmt = $db->query("SELECT id, name FROM employees ORDER BY name");
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
    <div class="col-md-4">
        <div class="form-container">
            <h3><i class="fas fa-plus-circle"></i> Add New Task</h3>
            <form id="taskForm" action="actions/add_task.php" method="POST">
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
                        <option value="">Select Employee</option>
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?= $employee['id'] ?>"><?= htmlspecialchars($employee['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="deadline" class="form-label">Deadline</label>
                    <input type="date" class="form-control" id="deadline" name="deadline" required>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Add Task
                </button>
            </form>
        </div>
    </div>
    
    <div class="col-md-8">
        <h3><i class="fas fa-list"></i> Tasks List</h3>
        <?php foreach ($tasks as $task): ?>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title"><?= htmlspecialchars($task['title']) ?></h5>
                        <span class="badge bg-<?= 
                            $task['status'] === 'completed' ? 'success' : 
                            ($task['status'] === 'in_progress' ? 'primary' : 'warning') 
                        ?>">
                            <?= ucfirst($task['status']) ?>
                        </span>
                    </div>
                    <p class="card-text"><?= htmlspecialchars($task['description']) ?></p>
                    <div class="row">
                        <div class="col-md-6">
                            <p><i class="fas fa-user"></i> <strong>Assigned to:</strong> 
                                <?= htmlspecialchars($task['employee_name']) ?></p>
                            <p><i class="fas fa-calendar"></i> <strong>Deadline:</strong> 
                                <?= date('F j, Y', strtotime($task['deadline'])) ?></p>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="actions/update_status.php?id=<?= $task['id'] ?>&status=in_progress" 
                               class="btn btn-sm btn-primary btn-action">
                                <i class="fas fa-play"></i>
                            </a>
                            <a href="actions/update_status.php?id=<?= $task['id'] ?>&status=completed" 
                               class="btn btn-sm btn-success btn-action">
                                <i class="fas fa-check"></i>
                            </a>
                            <a href="actions/delete_task.php?id=<?= $task['id'] ?>" 
                               class="btn btn-sm btn-danger btn-action"
                               onclick="return confirm('Are you sure you want to delete this task?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
