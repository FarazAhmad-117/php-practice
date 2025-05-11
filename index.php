<?php
$title = 'Dashboard';
$breadcrumbs = [
    ['title' => 'Home', 'url' => 'index.php'],
    ['title' => 'Dashboard']
];

ob_start();
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <?php
    $modules = [
        'Inventory' => 150,
        'Customers' => 87,
        'Blogs' => 23,
        'Invoices' => 45,
        'Reports' => 12,
        'Users' => 5
    ];
    foreach ($modules as $name => $count) {
        echo <<<HTML
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">$name Management</h5>
                    <p class="card-text">Total: <strong>$count</strong></p>
                </div>
            </div>
        </div>
        HTML;
    }
    ?>
</div>

<!-- Charts -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Monthly Sales</h5>
                <canvas id="salesChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Users by Role</h5>
                <canvas id="userChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Welcome Message -->
<div class="row">
    <div class="col-md-12">
        <div class="card bg-light border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Welcome to Admin Panel</h5>
                <p class="card-text">Manage all aspects of the application including inventory, customers, content, invoices, and more.</p>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN and chart setup -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            datasets: [{
                label: 'Sales',
                data: [30, 45, 28, 60, 80],
                borderColor: 'rgba(75, 192, 192, 1)',
                tension: 0.4
            }]
        }
    });

    // User chart
    const userCtx = document.getElementById('userChart').getContext('2d');
    new Chart(userCtx, {
        type: 'doughnut',
        data: {
            labels: ['Admin', 'Manager', 'Staff'],
            datasets: [{
                label: 'Users',
                data: [2, 1, 2],
                backgroundColor: ['#007bff', '#28a745', '#ffc107']
            }]
        }
    });
</script>
<?php
$content = ob_get_clean();
include 'Layout.php';
?>
