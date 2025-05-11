<?php
/**
 * Main Layout File
 * 
 * @param string $title Page title
 * @param array $breadcrumbs Array of breadcrumb items
 * @param bool $isAuthPage Whether this is an authentication page
 */

    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Redirect logic for non-auth pages
    if (!($isAuthPage ?? false)) {
        // Check if user is not logged in
        if (!isset($_SESSION['logged_in'])) {
            // Store the requested URL for redirect after login
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            header('Location: login.php');
            exit();
        }

        // Optional: Verify session validity (anti-hijacking)
        if (isset($_SESSION['user_agent'])) {
            if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
                // Session hijacking suspected
                session_unset();
                session_destroy();
                header('Location: login.php');
                exit();
            }
        } else {
            // Store user agent for future verification
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        }
    }


    // Define base path dynamically
    $basePath = rtrim(str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__), '/') . '/';
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin Panel') ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link href="assets/css/admin.css" rel="stylesheet">
    
    <?= $headContent ?? '' ?>
</head>
<body class="<?= $isAuthPage ?? false ? 'bg-light' : 'd-flex flex-column min-vh-100' ?>">
     <?php if ($isAuthPage ?? false): ?>
        <main class="container min-vh-100">
            <?= $content ?? '' ?>
        </main>
    <?php else: ?>
        <?php include __DIR__ . '/components/header.php'; ?>
        <div class="container-fluid flex-grow-1"  >
            <div class="row min-vh-100">
                <?php include __DIR__ . '/components/sidebar.php'; ?>
                
                <!-- Main content area -->
                <main class="col-md-9 col-lg-10 ms-sm-auto px-md-4 py-3 max-vh-100 overflow-auto">
                    <?= $content ?? '' ?>
                </main>
            </div>
        </div>
    <?php endif; ?>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/admin.js"></script>
    
    <?= $scriptContent ?? '' ?>
</body>
</html>