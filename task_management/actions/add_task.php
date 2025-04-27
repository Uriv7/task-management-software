<?php
require_once '../config/config.php';
require_once '../config/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->connect();

    try {
        $stmt = $db->prepare("INSERT INTO tasks (title, description, employee_id, deadline, status) 
                             VALUES (:title, :description, :employee_id, :deadline, 'pending')");
        
        $stmt->execute([
            ':title' => $_POST['title'],
            ':description' => $_POST['description'],
            ':employee_id' => $_POST['employee_id'],
            ':deadline' => $_POST['deadline']
        ]);

        $_SESSION['success'] = "Task added successfully!";
    } catch(PDOException $e) {
        $_SESSION['error'] = "Error adding task: " . $e->getMessage();
    }
}

header('Location: ' . BASE_URL . '/tasks.php');
