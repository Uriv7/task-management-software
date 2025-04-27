<?php
require_once 'includes/header.php';

$db = Database::getInstance();
$conn = $db->getConnection();

// Get statistics
$stats = [
    'total_tasks' => $conn->query("SELECT COUNT(*) FROM tasks")->fetchColumn(),
    'pending_tasks' => $conn->query("SELECT COUNT(*) FROM tasks WHERE status = 'pending'")->fetchColumn(),
    'completed_tasks' => $conn->query("SELECT COUNT(*) FROM tasks WHERE status = 'completed'")->fetchColumn(),
    'total_employees' => $conn->query("SELECT COUNT(*) FROM employees")->fetchColumn(),
    'high_priority' => $conn->query("SELECT COUNT(*) FROM tasks WHERE priority = 'high' AND status != 'completed'")->fetchColumn(),
    'overdue_tasks' => $conn->query("SELECT COUNT(*) FROM tasks WHERE deadline < date('now') AND status != 'completed'")->fetchColumn()
];

// Get recent tasks
$recent_tasks = $conn->query("
    SELECT t.*, e.name as employee_name 
    FROM tasks t 
    LEFT JOIN employees e ON t.employee_id = e.id 
    ORDER BY t.created_at DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Get top performing employees
$top_employees = $conn->query("
    SELECT 
        e.name,
        COUNT(t.id) as total_tasks,
        SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) as completed_tasks
    FROM employees e
    LEFT JOIN tasks t ON e.id = t.employee_id
    GROUP BY e.id, e.name
    HAVING total_tasks > 0
    ORDER BY completed_tasks DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row mb-4">
    <div class="col-md-2">
        <div class="stats-card bg-primary">
            <i class="fas fa-tasks"></i>
            <h3><?= $stats['total_tasks'] ?></h3>
            <p>Total Tasks</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stats-card bg-warning text-dark">
            <i class="fas fa-clock"></i>
            <h3><?= $stats['pending_tasks'] ?></h3>
            <p>Pending Tasks</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stats-card bg-success">
            <i class="fas fa-check-circle"></i>
            <h3><?= $stats['completed_tasks'] ?></h3>
            <p>Completed Tasks</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stats-card bg-info">
            <i class="fas fa-users"></i>
            <h3><?= $stats['total_employees'] ?></h3>
            <p>Total Employees</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stats-card bg-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <h3><?= $stats['high_priority'] ?></h3>
            <p>High Priority</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stats-card bg-secondary">
            <i class="fas fa-hourglass-end"></i>
            <h3><?= $stats['overdue_tasks'] ?></h3>
            <p>Overdue Tasks</p>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Task Completion Trend</h5>
            </div>
            <div class="card-body">
                <canvas id="taskTrendChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Task Priority Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="priorityChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Department Workload</h5>
            </div>
            <div class="card-body">
                <canvas id="departmentChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Top Performing Employees</h5>
            </div>
            <div class="card-body">
                <canvas id="employeeChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>Recent Tasks
                </h5>
                <div class="date-filter">
                    <input type="text" id="dateRange" class="form-control" placeholder="Filter by date range">
                </div>
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_tasks as $task): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($task['title']) ?></strong>
                                        <small class="d-block text-muted"><?= htmlspecialchars(substr($task['description'], 0, 50)) ?>...</small>
                                    </td>
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
                                    <td>
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-sm btn-info btn-action" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="actions/update_status.php?id=<?= $task['id'] ?>&status=completed" 
                                               class="btn btn-sm btn-success btn-action" title="Mark Complete">
                                                <i class="fas fa-check"></i>
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
</div>

<?php require_once 'includes/footer.php'; ?>
