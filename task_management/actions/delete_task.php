<?php
require_once '../config/config.php';
require_once '../config/Database.php';

if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->connect();

    try {
        $stmt = $db->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->execute([':id' => $_GET['id']]);

        $_SESSION['success'] = "Task deleted successfully!";
    } catch(PDOException $e) {
        $_SESSION['error'] = "Error deleting task: " . $e->getMessage();
    }
}

header('Location: ' . BASE_URL . '/tasks.php');
