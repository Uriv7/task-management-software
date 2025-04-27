<?php
require_once '../includes/header.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    try {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("UPDATE tasks SET status = :status WHERE id = :id");
        $stmt->execute([
            ':status' => $_GET['status'],
            ':id' => $_GET['id']
        ]);

        $_SESSION['success'] = "Task status updated successfully!";
    } catch(PDOException $e) {
        $_SESSION['error'] = "Error updating task status: " . $e->getMessage();
    }
}

header('Location: ../tasks.php');
