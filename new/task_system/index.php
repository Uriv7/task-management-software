<?php
require_once 'includes/header.php';

$db = Database::getInstance();
$conn = $db->getConnection();

// Get statistics
$stats = [
    'total_tasks' => $conn->query("SELECT COUNT(*) FROM tasks")->fetchColumn(),
    'pending_tasks' => $conn->query("SELECT COUNT(*) FROM tasks WHERE status = 'pending'")->fetchColumn(),
    'completed_tasks' => $conn->query("SELECT COUNT(*) FROM tasks WHERE status = 'completed'")->fetchColumn(),
    'total_employees' => $conn->query("SELECT COUNT(*) FROM employees")->fetchColumn()
];

// Get recent tasks
$recent_tasks = $conn->query("
    SELECT t.*, e.name as employee_name 
    FROM tasks t 
    LEFT JOIN employees e ON t.employee_id = e.id 
    ORDER BY t.created_at DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <i class="fas fa-tasks"></i>
            <h3><?= $stats['total_tasks'] ?></h3>
            <p>Total Tasks</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <i class="fas fa-clock"></i>
            <h3><?= $stats['pending_tasks'] ?></h3>
            <p>Pending Tasks</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <i class="fas fa-check-circle"></i>
            <h3><?= $stats['completed_tasks'] ?></h3>
            <p>Completed Tasks</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <i class="fas fa-users"></i>
            <h3><?= $stats['total_employees'] ?></h3>
            <p>Total Employees</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>Recent Tasks
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>Assigned To</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Priority</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_tasks as $task): ?>
                                <tr>
                                    <td><?= htmlspecialchars($task['title']) ?></td>
                                    <td><?= htmlspecialchars($task['employee_name'] ?? 'Unassigned') ?></td>
                                    <td><?= date('M d, Y', strtotime($task['deadline'])) ?></td>
                                    <td>
                                        <span class="badge status-<?= $task['status'] ?>">
                                            <?= ucfirst($task['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="priority-<?= $task['priority'] ?>">
                                            <i class="fas fa-flag"></i>
                                            <?= ucfirst($task['priority']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
