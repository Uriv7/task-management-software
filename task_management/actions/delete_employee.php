<?php
require_once '../config/config.php';
require_once '../config/Database.php';

if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->connect();

    try {
        // First, update tasks to remove reference to this employee
        $stmt = $db->prepare("UPDATE tasks SET employee_id = NULL WHERE employee_id = :id");
        $stmt->execute([':id' => $_GET['id']]);

        // Then delete the employee
        $stmt = $db->prepare("DELETE FROM employees WHERE id = :id");
        $stmt->execute([':id' => $_GET['id']]);

        $_SESSION['success'] = "Employee deleted successfully!";
    } catch(PDOException $e) {
        $_SESSION['error'] = "Error deleting employee: " . $e->getMessage();
    }
}

header('Location: ' . BASE_URL . '/employees.php');
