<?php
require_once '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("INSERT INTO tasks (title, description, employee_id, deadline, status, created_at) VALUES (?, ?, ?, ?, 'pending', NOW())");
        
        $stmt->execute([
            $_POST['title'],
            $_POST['description'],
            $_POST['employee_id'],
            $_POST['deadline']
        ]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
