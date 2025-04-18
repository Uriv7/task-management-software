<?php
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("
            INSERT INTO tasks (title, description, employee_id, priority, deadline, status) 
            VALUES (:title, :description, :employee_id, :priority, :deadline, 'pending')
        ");
        
        $stmt->execute([
            ':title' => $_POST['title'],
            ':description' => $_POST['description'],
            ':employee_id' => $_POST['employee_id'],
            ':priority' => $_POST['priority'],
            ':deadline' => $_POST['deadline']
        ]);

        $_SESSION['success'] = "Task added successfully!";
    } catch(PDOException $e) {
        $_SESSION['error'] = "Error adding task: " . $e->getMessage();
    }
}

header('Location: ../tasks.php');
