# Task Management System

A web-based task management system built with PHP, MySQL, Bootstrap, and JavaScript. This system allows you to manage tasks and employees efficiently.

## Features

- Dashboard with task and employee statistics
- Task management (Create, Read, Update, Delete)
- Employee management (Create, Read, Update, Delete)
- Task status tracking (Pending, In Progress, Completed)
- Task priority levels (Low, Medium, High)
- Responsive design

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)

## Installation

1. Clone the repository to your web server directory
2. Create a new MySQL database named `task_system`
3. Import the `database.sql` file to create the required tables
4. Update the database configuration in `config/database.php` if needed
5. Access the application through your web browser

## Directory Structure

```
task_system/
├── actions/           # Form processing scripts
├── assets/           # CSS, JavaScript, and images
├── config/           # Configuration files
├── includes/         # Header and footer files
├── database.sql      # Database schema
├── index.php         # Dashboard
├── tasks.php         # Task management
├── employees.php     # Employee management
└── README.md         # Documentation
```

## Usage

1. Add employees through the Employees page
2. Create tasks and assign them to employees
3. Track task progress by updating their status
4. View statistics on the dashboard
5. Manage tasks and employees as needed

## Security

- Input validation and sanitization
- PDO prepared statements for database queries
- Session-based messages for user feedback
- Safe deletion with database transactions
