<?php
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("
            INSERT INTO employees (name, email, position, department) 
            VALUES (:name, :email, :position, :department)
        ");
        
        $stmt->execute([
            ':name' => $_POST['name'],
            ':email' => $_POST['email'],
            ':position' => $_POST['position'],
            ':department' => $_POST['department']
        ]);

        $_SESSION['success'] = "Employee added successfully!";
    } catch(PDOException $e) {
        $_SESSION['error'] = "Error adding employee: " . $e->getMessage();
    }
}

header('Location: ../employees.php');
