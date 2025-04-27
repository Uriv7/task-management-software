function initChart(ctx, config) {
    if (!ctx) {
        console.error('Canvas context not found');
        return;
    }
    try {
        return new Chart(ctx, config);
    } catch (error) {
        console.error('Error creating chart:', error);
        return null;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize date range picker
    $('#dateRange').daterangepicker({
        opens: 'left',
        maxDate: new Date(),
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    // Fetch chart data
    fetch('api/get_chart_data.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                initializeCharts(data.data);
            }
        })
        .catch(error => console.error('Error fetching chart data:', error));
});

function initializeCharts(data) {
    console.log('Chart data:', data);
    
    // Task Completion Trend Chart
    const trendCtx = document.getElementById('taskTrendChart')?.getContext('2d');
    if (trendCtx && data.taskTrend && data.taskTrend.length > 0) {
        initChart(trendCtx, {
        type: 'line',
        data: {
            labels: data.taskTrend.map(item => item.date),
            datasets: [{
                label: 'Total Tasks',
                data: data.taskTrend.map(item => item.total_tasks),
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }, {
                label: 'Completed Tasks',
                data: data.taskTrend.map(item => item.completed_tasks),
                borderColor: 'rgb(54, 162, 235)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Task Completion Trend (Last 7 Days)'
                }
            }
        }
    });

    }

    // Priority Distribution Chart
    const priorityCtx = document.getElementById('priorityChart')?.getContext('2d');
    if (priorityCtx && data.priorityDistribution && data.priorityDistribution.length > 0) {
        initChart(priorityCtx, {
        type: 'doughnut',
        data: {
            labels: data.priorityDistribution.map(item => item.priority),
            datasets: [{
                data: data.priorityDistribution.map(item => item.count),
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 205, 86)',
                    'rgb(54, 162, 235)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    }

    // Department Workload Chart
    const deptCtx = document.getElementById('departmentChart')?.getContext('2d');
    if (deptCtx && data.departmentWorkload && data.departmentWorkload.length > 0) {
        initChart(deptCtx, {
        type: 'bar',
        data: {
            labels: data.departmentWorkload.map(item => item.department),
            datasets: [{
                label: 'Completed',
                data: data.departmentWorkload.map(item => item.completed_tasks),
                backgroundColor: 'rgb(75, 192, 192)'
            }, {
                label: 'In Progress',
                data: data.departmentWorkload.map(item => item.in_progress_tasks),
                backgroundColor: 'rgb(255, 205, 86)'
            }, {
                label: 'Pending',
                data: data.departmentWorkload.map(item => item.pending_tasks),
                backgroundColor: 'rgb(255, 99, 132)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true
                }
            }
        }
    });

    }

    // Employee Performance Chart
    const empCtx = document.getElementById('employeeChart')?.getContext('2d');
    if (empCtx && data.employeePerformance && data.employeePerformance.length > 0) {
        initChart(empCtx, {
        type: 'bar',
        data: {
            labels: data.employeePerformance.map(item => item.name),
            datasets: [{
                label: 'Total Tasks',
                data: data.employeePerformance.map(item => item.total_tasks),
                backgroundColor: 'rgb(54, 162, 235)'
            }, {
                label: 'Completed Tasks',
                data: data.employeePerformance.map(item => item.completed_tasks),
                backgroundColor: 'rgb(75, 192, 192)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    }
}
