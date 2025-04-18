<?php
require_once '../includes/header.php';

if (isset($_GET['id'])) {
    try {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        // Begin transaction
        $conn->beginTransaction();

        // Update tasks to remove reference to this employee
        $stmt = $conn->prepare("UPDATE tasks SET employee_id = NULL WHERE employee_id = :id");
        $stmt->execute([':id' => $_GET['id']]);

        // Delete the employee
        $stmt = $conn->prepare("DELETE FROM employees WHERE id = :id");
        $stmt->execute([':id' => $_GET['id']]);

        // Commit transaction
        $conn->commit();

        $_SESSION['success'] = "Employee deleted successfully and their tasks have been unassigned.";
    } catch(PDOException $e) {
        // Rollback transaction on error
        $conn->rollBack();
        $_SESSION['error'] = "Error deleting employee: " . $e->getMessage();
    }
}

header('Location: ../employees.php');
