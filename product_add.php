<?php
require_once __DIR__ . '/functions/products.php';

$title = "Add Product";
$isAuthPage = false;
$breadcrumbs = [
    ['title'=> 'Home', 'url' => 'index.php'],
    ['title'=> 'Inventory Management', 'url' => 'manage-inventory.php'],
    ['title'=> 'Add Product'],
];

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => trim($_POST['title']),
        'slug' => trim($_POST['slug']),
        'description' => trim($_POST['description']),
        'price' => floatval($_POST['price']),
        'quantity' => intval($_POST['quantity']),
        'category_id' => $_POST['category_id'] ?? null,
    ];

    if (empty($data['title']) || empty($data['slug']) || $data['price'] <= 0) {
        $errors[] = "Please fill all required fields and provide valid data.";
    } else {
        $success = addProduct($data, $_FILES['image'] ?? null);
        if ($success) {
            header("Location: manage-inventory.php");
            exit;
        } else {
            $errors[] = "Something went wrong while adding the product.";
        }
    }
}

ob_start();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add New Product</h1>
</div>

<?php if ($errors): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
            <div><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="row" >
    <div class="col-md-1 col-lg-2" ></div>
    <div class="col-md-10 col-lg-8" >
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Title *</label>
                <input type="text" name="title" id="title" class="form-control" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
            </div>
        
            <div class="mb-3">
                <label for="slug" class="form-label">Slug *</label>
                <input type="text" name="slug" id="slug" class="form-control" required value="<?= htmlspecialchars($_POST['slug'] ?? '') ?>">
            </div>
        
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>
        
            <div class="mb-3">
                <label for="price" class="form-label">Price *</label>
                <input type="number" name="price" id="price" step="0.01" class="form-control" required value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">
            </div>
        
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity *</label>
                <input type="number" name="quantity" id="quantity" class="form-control" required value="<?= htmlspecialchars($_POST['quantity'] ?? 0) ?>">
            </div>
        
            <div class="mb-3">
                <label for="category_id" class="form-label">Category ID</label>
                <input type="number" name="category_id" id="category_id" class="form-control" value="<?= htmlspecialchars($_POST['category_id'] ?? '') ?>">
            </div>
        
            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>
        
            <button type="submit" class="btn btn-success">Add Product</button>
            <a href="manage-inventory.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <div class="col-md-1 col-lg-2" > </div>
</div>

<?php
$content = ob_get_clean();
include 'Layout.php';
?>
