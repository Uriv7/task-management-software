<?php
class Database {
    private $db_path;
    private static $instance = null;
    private $conn = null;

    private function __construct() {
        $this->db_path = __DIR__ . '/../data/task_system.sqlite';
        
        // Create data directory if it doesn't exist
        $data_dir = dirname($this->db_path);
        if (!file_exists($data_dir)) {
            mkdir($data_dir, 0777, true);
        }

        try {
            $this->conn = new PDO("sqlite:{$this->db_path}");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec('PRAGMA foreign_keys = ON');
            
            // Create tables if they don't exist
            $this->createTables();
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    private function createTables() {
        $tables = [
            'employees' => "CREATE TABLE IF NOT EXISTS employees (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT UNIQUE NOT NULL,
                position TEXT NOT NULL,
                department TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )",
            'tasks' => "CREATE TABLE IF NOT EXISTS tasks (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                description TEXT,
                employee_id INTEGER,
                status TEXT CHECK(status IN ('pending', 'in_progress', 'completed')) DEFAULT 'pending',
                priority TEXT CHECK(priority IN ('low', 'medium', 'high')) DEFAULT 'medium',
                deadline DATE NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE SET NULL
            )"
        ];

        foreach ($tables as $name => $sql) {
            try {
                $this->conn->exec($sql);
            } catch(PDOException $e) {
                die("Error creating {$name} table: " . $e->getMessage());
            }
        }
    }
}
