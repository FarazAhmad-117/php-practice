<?php
/**
 * Admin Panel Header Component
 * 
 * @param string $title Page title
 * @param array $breadcrumbs Array of breadcrumb items
 * @param bool $showNotifications Whether to show notifications dropdown
 */
?>
<header class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <!-- Toggle sidebar button (only shown when sidebar is present) -->
        <?php if ($showSidebar ?? true): ?>
            <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar">
                <span class="navbar-toggler-icon"></span>
            </button>
        <?php endif; ?>
        
        <!-- Brand/logo -->
        <a class="navbar-brand" href="/practice/">
            <i class="bi bi-speedometer2 me-2"></i>
            Admin Panel
        </a>
        
        <!-- Mobile menu toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Main navigation -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if ($showSidebar ?? true): ?>
                    <!-- Header items that appear when sidebar is present -->
                    <li class="nav-item">
                        <span class="nav-link disabled"><?= $title ?? 'Dashboard' ?></span>
                    </li>
                <?php else: ?>
                    <!-- Header items for auth pages -->
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
            
            <!-- Right side of header -->
            <ul class="navbar-nav">
                <?php if ($showSidebar ?? true): ?>
                    <!-- User dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/admin/profile"><i class="bi bi-person me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="/admin/settings"><i class="bi bi-gear me-2"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="functions/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</header>

<!-- Breadcrumbs (only shown in admin pages) -->
<?php if (($showSidebar ?? true) && !empty($breadcrumbs)): ?>
<nav aria-label="breadcrumb" class="bg-light py-2 px-3 border-bottom">
    <ol class="breadcrumb mb-0">
        <?php foreach ($breadcrumbs as $crumb): ?>
            <?php if (!empty($crumb['url'])): ?>
                <li class="breadcrumb-item"><a href="<?= htmlspecialchars($crumb['url']) ?>"><?= htmlspecialchars($crumb['title']) ?></a></li>
            <?php else: ?>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($crumb['title']) ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ol>
</nav>
<?php endif; ?>