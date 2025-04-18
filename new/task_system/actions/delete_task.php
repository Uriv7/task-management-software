<?php
require_once '../includes/header.php';

if (isset($_GET['id'])) {
    try {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->execute([':id' => $_GET['id']]);

        $_SESSION['success'] = "Task deleted successfully!";
    } catch(PDOException $e) {
        $_SESSION['error'] = "Error deleting task: " . $e->getMessage();
    }
}

header('Location: ../tasks.php');
