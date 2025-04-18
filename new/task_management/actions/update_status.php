<?php
require_once '../config/config.php';
require_once '../config/Database.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $database = new Database();
    $db = $database->connect();

    try {
        $stmt = $db->prepare("UPDATE tasks SET status = :status WHERE id = :id");
        $stmt->execute([
            ':status' => $_GET['status'],
            ':id' => $_GET['id']
        ]);

        $_SESSION['success'] = "Task status updated successfully!";
    } catch(PDOException $e) {
        $_SESSION['error'] = "Error updating task status: " . $e->getMessage();
    }
}

header('Location: ' . BASE_URL . '/tasks.php');
