<?php
require_once '../config/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$db = Database::getInstance();
$conn = $db->getConnection();

$response = [];

try {
    // Task completion trend (last 7 days)
    $stmt = $conn->query("
        SELECT 
            date(created_at) as date,
            COUNT(*) as total_tasks,
            SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_tasks
        FROM tasks 
        WHERE created_at >= date('now', '-7 days')
        GROUP BY date(created_at)
        ORDER BY date
    ");
    $response['taskTrend'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Department workload
    $stmt = $conn->query("
        SELECT 
            e.department,
            COUNT(t.id) as total_tasks,
            SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) as completed_tasks,
            SUM(CASE WHEN t.status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_tasks,
            SUM(CASE WHEN t.status = 'pending' THEN 1 ELSE 0 END) as pending_tasks
        FROM employees e
        LEFT JOIN tasks t ON e.id = t.employee_id
        GROUP BY e.department
    ");
    $response['departmentWorkload'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Task priority distribution
    $stmt = $conn->query("
        SELECT 
            priority,
            COUNT(*) as count
        FROM tasks
        GROUP BY priority
    ");
    $response['priorityDistribution'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Employee performance
    $stmt = $conn->query("
        SELECT 
            e.name,
            COUNT(t.id) as total_tasks,
            SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) as completed_tasks
        FROM employees e
        LEFT JOIN tasks t ON e.id = t.employee_id
        GROUP BY e.id, e.name
        HAVING total_tasks > 0
        ORDER BY completed_tasks DESC
        LIMIT 5
    ");
    $response['employeePerformance'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $response]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
