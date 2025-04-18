<?php
require_once '../config/db.php';

$stmt = $pdo->query("SELECT * FROM employees ORDER BY name");
$employees = $stmt->fetchAll();
?>

<div class="row">
    <div class="col-md-4">
        <div class="form-container">
            <h3>Add New Employee</h3>
            <form id="employeeForm">
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
                <button type="submit" class="btn btn-primary">Add Employee</button>
            </form>
        </div>
    </div>
    
    <div class="col-md-8">
        <h3>Employees List</h3>
        <div class="row">
            <?php foreach ($employees as $employee): ?>
                <div class="col-md-6">
                    <div class="card employee-card mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($employee['name']) ?></h5>
                            <p class="card-text">
                                <strong>Email:</strong> <?= htmlspecialchars($employee['email']) ?><br>
                                <strong>Position:</strong> <?= htmlspecialchars($employee['position']) ?><br>
                                <strong>Department:</strong> <?= htmlspecialchars($employee['department']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
