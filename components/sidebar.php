<?php
/**
 * Admin Panel Sidebar Component
 */

$permissions = $_SESSION["permissions"] ?? [];
?>

<aside id="sidebar" class="collapse d-md-block col-md-3 col-lg-2 bg-dark text-white p-0 sidebar-collapse">
    <div class="position-sticky h-100 d-flex flex-column">
        <div class="p-3 bg-dark text-white border-bottom">
            <h5 class="mb-0">Navigation</h5>
        </div>

        <nav class="flex-grow-1 overflow-auto">
            <ul class="nav flex-column">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link <?= $_SERVER["REQUEST_URI"] == "/practice/index.php" ? 'active' : '' ?>" href="index.php">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>

                <!-- Inventory Management -->
                <?php
                    $current = $_SERVER["REQUEST_URI"];
                    $isInventoryActive = $current == "/practice/manage-inventory.php" || $current == "/practice/admin/stock";
                ?>
                <?php if (in_array("inventory_management", $permissions)): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $isInventoryActive ? 'active' : '' ?>" data-bs-toggle="collapse" href="#inventoryMenu" role="button" aria-expanded="<?= $isInventoryActive ? 'true' : 'false' ?>" aria-controls="inventoryMenu">
                        <i class="bi bi-box-seam me-2"></i> Inventory
                    </a>
                    <div class="collapse <?= $isInventoryActive ? 'show' : '' ?>" id="inventoryMenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link <?= $current == "/practice/manage-inventory.php" ? 'active' : '' ?>" href="/practice/manage-inventory.php">
                                    Manage Inventory
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $current == "/practice/admin/stock" ? 'active' : '' ?>" href="/practice/admin/stock">
                                    Stock Levels
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>


                <!-- Customer Management -->
                <?php
                    $current = $_SERVER["REQUEST_URI"];
                    $isCustomerActive = $current == "/practice/customer-list.php" || $current == "/practice/admin/feedback";
                ?>
                <?php if (in_array("customer_management", $permissions)): ?>
                <li class="nav-item  ">
                    <a class="nav-link <?= $isCustomerActive ? 'active' : '' ?> " data-bs-toggle="collapse" aria-expanded="<?= $isCustomerActive ? 'true' : 'false' ?>" href="#customerMenu" role="button">
                        <i class="bi bi-person-lines-fill me-2"></i> Customers
                    </a>
                    <div class="collapse <?= $isCustomerActive ? 'show' : '' ?> " id="customerMenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item "><a class="nav-link <?= $current == "/practice/customer-list.php" ? 'active' : '' ?> " href="customer-list.php">Customer List</a></li>
                            <li class="nav-item"><a class="nav-link" href="/admin/feedback">Feedback</a></li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>

                <!-- Blog Management -->
                <?php if (in_array("blog_management", $permissions)): ?>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#blogMenu" role="button">
                        <i class="bi bi-journal-text me-2"></i> Blog
                    </a>
                    <div class="collapse" id="blogMenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item"><a class="nav-link" href="/admin/blog">Manage Posts</a></li>
                            <li class="nav-item"><a class="nav-link" href="/admin/blog/categories">Categories</a></li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>

                <!-- Invoice Management -->
                <?php if (in_array("invoice_management", $permissions)): ?>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#invoiceMenu" role="button">
                        <i class="bi bi-receipt me-2"></i> Invoices
                    </a>
                    <div class="collapse" id="invoiceMenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item"><a class="nav-link" href="/admin/invoices">All Invoices</a></li>
                            <li class="nav-item"><a class="nav-link" href="/admin/invoices/create">Create Invoice</a></li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>

                <!-- Reporting -->
                <?php if (in_array("reporting", $permissions)): ?>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#reportMenu" role="button">
                        <i class="bi bi-graph-up me-2"></i> Reports
                    </a>
                    <div class="collapse" id="reportMenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item"><a class="nav-link" href="/admin/reports/sales">Sales</a></li>
                            <li class="nav-item"><a class="nav-link" href="/admin/reports/customers">Customer Reports</a></li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>

                <!-- User Management -->
                <?php if (in_array("user_management", $permissions)): ?>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#userMenu" role="button">
                        <i class="bi bi-people me-2"></i> Users
                    </a>
                    <div class="collapse" id="userMenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item"><a class="nav-link" href="/admin/users">Manage Users</a></li>
                            <li class="nav-item"><a class="nav-link" href="/admin/roles">Roles & Permissions</a></li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>

                <!-- Settings -->
                <?php if (in_array("settings", $permissions)): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/settings">
                        <i class="bi bi-gear me-2"></i> Settings
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>

        <div class="p-3 bg-dark text-white border-top">
            <small>Version 1.0.0</small>
        </div>
    </div>
</aside>
