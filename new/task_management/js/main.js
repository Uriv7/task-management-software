document.addEventListener('DOMContentLoaded', function() {
    // Handle navigation
    document.querySelectorAll('[data-page]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            loadPage(this.dataset.page);
        });
    });

    // Load tasks page by default
    loadPage('tasks');
});

function loadPage(page) {
    fetch(`pages/${page}.php`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('content').innerHTML = html;
            initializePageFunctions(page);
        })
        .catch(error => console.error('Error loading page:', error));
}

function initializePageFunctions(page) {
    if (page === 'tasks') {
        initializeTaskFunctions();
    } else if (page === 'employees') {
        initializeEmployeeFunctions();
    }
}

function initializeTaskFunctions() {
    const taskForm = document.getElementById('taskForm');
    if (taskForm) {
        taskForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('api/add_task.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadPage('tasks');
                    alert('Task added successfully!');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
}

function initializeEmployeeFunctions() {
    const employeeForm = document.getElementById('employeeForm');
    if (employeeForm) {
        employeeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('api/add_employee.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadPage('employees');
                    alert('Employee added successfully!');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
}
