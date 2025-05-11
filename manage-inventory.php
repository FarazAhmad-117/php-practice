<?php 
    
    require_once __DIR__ . '/functions/products.php';

    $title = "Inventory Management";
    $isAuthPage = false;
    $breadcrumbs = [
        ['title'=> 'Home', 'url' => 'index.php'],
        ['title'=> 'Inventory Management'],
    ];

    // Pagination setup
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = 10;

    
    $results = getProducts($page, $perPage);
    $products = $results['products'];
    $totalPages = $results['totalPages'];
    $currentPage = $results['currentPage'];

    ob_start();
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Inventory Management</h1>
    <a href="product_add.php" class="btn btn-primary">Add New Product</a>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Title</th>
                <th>Slug</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr><td colspan="8" class="text-center">No products found.</td></tr>
            <?php else: ?>
                <?php foreach ($products as $index => $product): ?>
                    <tr>
                        <td><?= ($perPage * ($currentPage - 1)) + $index + 1 ?></td>
                        <td>
                            <?php if ($product['image']): ?>
                                <img src="<?= htmlspecialchars($product['image']) ?>" width="60" height="60" style="object-fit: cover;" alt="Image">
                            <?php else: ?>
                                <span class="text-muted">No Image</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($product['title']) ?></td>
                        <td><?= htmlspecialchars($product['slug']) ?></td>
                        <td>$<?= number_format($product['price'], 2) ?></td>
                        <td><?= (int)$product['quantity'] ?></td>
                        <td><?= date('F j, Y - g:i a' , strtotime($product['created_at'])) ?></td>
                        <td>
                            <a href="product_edit.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="product_delete.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this product?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<!-- Pagination -->
<nav>
    <ul class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>

<?php

    $content = ob_get_clean();

    include 'Layout.php';

?>

