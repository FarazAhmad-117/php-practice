<?php

    require_once 'functions/customers.php';



    $id = isset($_GET['id']) ? intval($_GET['id']) :null;
    if (!$id) {
        die("An Id is required!");
    }

    $customer = getCustomerById($id);
    if (!$customer) {
        die("Customer not found!");
    }

    $title = "Edit Customer";
    $isAuthPage = false;
    $breadcrumbs = [
        ['title'=> 'Home', 'url' => 'index.php'],
        ['title'=> 'Customer List', 'url' => 'customer-list.php'],
        ['title'=> 'Edit Customer'],
    ];


    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $data = [
            "first_name" => trim($_POST["first_name"]),
            "last_name"=> trim($_POST["last_name"]),
            "email"=> trim($_POST["email"]),
            "phone"=> trim($_POST["phone"]),
            "address"=> trim($_POST["address"]),
        ];

        $imageFile = $_FILES["profile_image"] ?? null;
        if(updateCustomer($id, $data, $imageFile)){
            header("Location: customer-list.php");
        }else{
            die("Customer update failed!");
        }

    }

    ob_start();
?>


<div class="d-flex align-items-center justify-content-between border-bottom" >
    <h1>Edit Customer</h1>
</div>

<div class="row" >
    <div class="col-md-1 col-lg-2"></div>
    <div class="col-md-10 col-lg-8 p-2">
        <form method="POST" enctype="multipart/form-data" >
            <div class="mb-3" >
                <label for="first_name" class="form-label" >First Name</label>
                <input class="form-control" id="first_name" name="first_name" placeholder="John" required type="text" value="<?=  htmlspecialchars($_POST['first_name'] ?? $customer["first_name"] ) ?>" />
            </div>
            <div class="mb-3" >
                <label for="last_name" class="form-label" >Last Name</label>
                <input class="form-control" id="last_name" name="last_name" placeholder="Doe" required type="text" value="<?=  htmlspecialchars($_POST['last_name'] ?? $customer["last_name"] ) ?>" />
            </div>
            <div class="mb-3" >
                <label for="email" class="form-label" >Email</label>
                <input class="form-control" id="email" name="email" placeholder="6t9Fg@example.com" required type="email" value="<?=  htmlspecialchars($_POST['email'] ?? $customer["email"] ) ?>" />
            </div>
            <div class="mb-3" >
                <label for="phone" class="form-label" >Phone</label>
                <input class="form-control" id="phone" name="phone" placeholder="+923210000000" required type="text" value="<?=  htmlspecialchars($_POST['phone'] ?? $customer["phone"] ) ?>" />
            </div>
            <div class="mb-3" >
                <label for="address" class="form-label" >Address</label>
                <input class="form-control" id="address" name="address" placeholder="123 Main St" required type="text" value="<?=  htmlspecialchars($_POST['address'] ?? $customer["address"] ) ?>" />
            </div>
            <div class="mb-3" >
                <label for="profile_image" class="form-label" >Profile Image</label>
                <?php if ($customer['profile_image']): ?>
                    <img src="<?= $customer['profile_image']  ?>" width="80" height="80" style="max-height: 100px;" class="mb-2 d-block" alt="Image" />
                <?php endif; ?>
                <input class="form-control" id="profile_image" name="profile_image" type="file" />
                <small class="text-muted form-text" >Add a new image to replace old one</small>
            </div>
            <button type="submit" class="btn btn-info" >Save Customer</button>
            <a class="btn btn-secondary" href="customer-list.php" >Cancel</a>
        </form>
    </div>
    <div class="col-md-1 col-lg-2"></div>
</div>



<?php 

    $content = ob_get_clean();
    require_once 'Layout.php';

?>