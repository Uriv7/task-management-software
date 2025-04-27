<?php
require_once '../config/config.php';
require_once '../config/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->connect();

    try {
        $stmt = $db->prepare("INSERT INTO employees (name, email, position, department) 
                             VALUES (:name, :email, :position, :department)");
        
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

header('Location: ' . BASE_URL . '/employees.php');
