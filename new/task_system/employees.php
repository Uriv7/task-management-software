<?php
require_once 'includes/header.php';

$db = Database::getInstance();
$conn = $db->getConnection();

// Get all employees with their task statistics
$employees = $conn->query("
    SELECT 
        e.*,
        COUNT(t.id) as total_tasks,
        SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) as completed_tasks,
        SUM(CASE WHEN t.status = 'pending' THEN 1 ELSE 0 END) as pending_tasks
    FROM employees e
    LEFT JOIN tasks t ON e.id = t.employee_id
    GROUP BY e.id
    ORDER BY e.name
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
    <div class="col-md-4">
        <div class="form-container">
            <h3><i class="fas fa-user-plus me-2"></i>Add New Employee</h3>
            <form action="actions/add_employee.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                
                <div class="mb-3">
                    <label for="position" class="form-label">Position</label>
                    <input type="text" class="form-control" id="position" name="position" required>
                </div>
                
                <div class="mb-3">
                    <label for="department" class="form-label">Department</label>
                    <input type="text" class="form-control" id="department" name="department" required>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Add Employee
                </button>
            </form>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="table-container">
            <h3 class="mb-4"><i class="fas fa-users me-2"></i>Employees List</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Employee</th>
                            <th>Contact</th>
                            <th>Tasks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees as $employee): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($employee['name']) ?></strong>
                                    <small class="d-block text-muted">
                                        <?= htmlspecialchars($employee['position']) ?> - 
                                        <?= htmlspecialchars($employee['department']) ?>
                                    </small>
                                </td>
                                <td>
                                    <i class="fas fa-envelope me-1"></i>
                                    <?= htmlspecialchars($employee['email']) ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-primary">
                                            <?= $employee['total_tasks'] ?> Total
                                        </span>
                                        <span class="badge bg-success">
                                            <?= $employee['completed_tasks'] ?> Done
                                        </span>
                                        <span class="badge bg-warning text-dark">
                                            <?= $employee['pending_tasks'] ?> Pending
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="edit_employee.php?id=<?= $employee['id'] ?>" 
                                           class="btn btn-sm btn-primary btn-action" title="Edit Employee">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="actions/delete_employee.php?id=<?= $employee['id'] ?>" 
                                           class="btn btn-sm btn-danger btn-action"
                                           onclick="return confirm('Are you sure you want to delete this employee? Their tasks will be unassigned.')"
                                           title="Delete Employee">
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
