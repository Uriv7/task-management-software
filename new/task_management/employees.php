<?php
require_once 'includes/header.php';
require_once 'config/Database.php';

$database = new Database();
$db = $database->connect();

// Get all employees with their task counts
$query = "SELECT e.*, 
          COUNT(t.id) as task_count,
          SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) as completed_tasks
          FROM employees e
          LEFT JOIN tasks t ON e.id = t.employee_id
          GROUP BY e.id
          ORDER BY e.name";
$stmt = $db->query($query);
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
    <div class="col-md-4">
        <div class="form-container">
            <h3><i class="fas fa-user-plus"></i> Add New Employee</h3>
            <form id="employeeForm" action="actions/add_employee.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
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
                    <i class="fas fa-save"></i> Add Employee
                </button>
            </form>
        </div>
    </div>
    
    <div class="col-md-8">
        <h3><i class="fas fa-users"></i> Employees List</h3>
        <div class="row">
            <?php foreach ($employees as $employee): ?>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-user"></i> <?= htmlspecialchars($employee['name']) ?>
                            </h5>
                            <p class="card-text">
                                <i class="fas fa-envelope"></i> <?= htmlspecialchars($employee['email']) ?><br>
                                <i class="fas fa-briefcase"></i> <?= htmlspecialchars($employee['position']) ?><br>
                                <i class="fas fa-building"></i> <?= htmlspecialchars($employee['department']) ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-primary">
                                        <?= $employee['task_count'] ?> Tasks
                                    </span>
                                    <span class="badge bg-success">
                                        <?= $employee['completed_tasks'] ?> Completed
                                    </span>
                                </div>
                                <div>
                                    <a href="edit_employee.php?id=<?= $employee['id'] ?>" 
                                       class="btn btn-sm btn-primary btn-action">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="actions/delete_employee.php?id=<?= $employee['id'] ?>" 
                                       class="btn btn-sm btn-danger btn-action"
                                       onclick="return confirm('Are you sure you want to delete this employee?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
