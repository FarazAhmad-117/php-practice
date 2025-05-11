<?php

    require_once 'functions/customers.php';

    $title = "Customer List";
    $isAuthPage = false;
    $breadcrumbs = [
        ['title'=> 'Home', 'url' => 'index.php'],
        ['title'=> 'Customer List'],
    ];

    $page =  isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 10;

    $result = getCustomers($page, $limit);
    $customers = $result['customers'];
    $totalPages = $result['totalPages'];
    $currentPage = $result['currentPage']; 

    ob_start();
?>


<div class="d-flex align-items-center justify-content-between border-bottom" >
    <h1>Customer List</h1>
    <a href="customer_add.php" class="btn btn-primary" >Add Customer</a>
</div>

<div class="table-responsive" >
    <table class="table table-bordered table-hover" >
        <thead class="table-light" >
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($customers)): ?>
                <tr><td colspan="7" class="text-center">No customers found.</td></tr>
            <?php else: ?>
                <?php foreach ($customers as $index => $customer): ?>
                    <tr>
                        <td><?= ($limit * ($currentPage - 1)) + $index + 1 ?></td>
                        <td><img src="<?= $customer['profile_image'] ?? 'assets/images/user.png' ?>" width="60" height="60" style="object-fit: cover;" alt="Image"  /></td>
                        <td><?= $customer['first_name'] ?></td><td><?= $customer['last_name'] ?></td><td><?= $customer['email'] ?></td><td><?= $customer['phone'] ?></td>
                        <td>
                            <a href="customer_edit.php?id=<?= $customer['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="customer_delete.php?id=<?= $customer['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this customer?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<!-- Adding pagination -->

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