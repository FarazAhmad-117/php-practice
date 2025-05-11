<?php
    require_once(__DIR__."/functions/products.php");
    $title = "Edit Product";
    $isAuthPage = false;
    $breadcrumbs = [
        ['title'=> 'Home', 'url' => 'index.php'],
        ['title'=> 'Inventory Management', 'url' => 'manage-inventory.php'],
        ['title'=> 'Edit Product'],
    ];

    $id = isset($_GET["id"]) ? intval($_GET["id"]) :0;
    $product = getProductById($id);

    if(!$product){
        die("Product not found!");
    }


    $errors = [];
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $data = [
            'title' => trim($_POST['title']),
            'slug' => trim($_POST['slug']),
            'description' => trim($_POST['description']),
            'price' => trim($_POST['price']),
            'quantity' => trim($_POST['quantity']),
            'category_id' => $_POST['category_id'] ?? null,
        ];

        if(empty($data['title']) || empty($data['slug'])|| $data['price'] <= 0){
            $errors[] = "Please fill all required fields and provide valid data.";
        }else{
            $updated = updateProduct($id, $data, $_FILES['image'] ?? null);
            if ($updated) {
                header("Location: manage-inventory.php");
                exit;
            } else {
                $errors[] = "Something went wrong while updating the product.";
            }
        }
    }

    ob_start();

?>


<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Product</h1>
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
                <input type="text" name="title" id="title" class="form-control" required
                    value="<?= htmlspecialchars($_POST['title'] ?? $product['title']) ?>">
            </div>

            <div class="mb-3">
                <label for="slug" class="form-label">Slug *</label>
                <input type="text" name="slug" id="slug" class="form-control" required
                    value="<?= htmlspecialchars($_POST['slug'] ?? $product['slug']) ?>">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4"><?= htmlspecialchars($_POST['description'] ?? $product['description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price *</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" required
                    value="<?= htmlspecialchars($_POST['price'] ?? $product['price']) ?>">
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity *</label>
                <input type="number" name="quantity" id="quantity" class="form-control" required
                    value="<?= htmlspecialchars($_POST['quantity'] ?? $product['quantity']) ?>">
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Category ID</label>
                <input type="number" name="category_id" id="category_id" class="form-control"
                    value="<?= htmlspecialchars($_POST['category_id'] ?? $product['category_id']) ?>">
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label><br>
                <?php if ($product['image']): ?>
                    <img src="<?= $product['image'] ?>" alt="Product Image" style="max-height: 100px;" class="mb-2 d-block">
                <?php endif; ?>
                <input type="file" name="image" id="image" class="form-control">
                <small class="form-text text-muted">Upload a new image to replace the existing one.</small>
            </div>

            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="inventory.php" class="btn btn-secondary">Cancel</a>
        </form>

    </div>
    <div class="col-md-1 col-lg-2" ></div>
</div>

<?php
$content = ob_get_clean();
include 'Layout.php';
?>