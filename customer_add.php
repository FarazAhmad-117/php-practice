<?php 

    require_once("functions/customers.php");
    $title = "Add Customer";
    $breadcrumbs = [
        ['title'=> 'Home', 'url' => 'index.php'],
        ['title'=> 'Customer List', 'url' => 'customer-list.php'],
        ['title'=> 'Add Customer'],
    ];

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $data = [
            'firstName' => trim($_POST["firstName"]),
            "lastName"=> trim($_POST["lastName"]),
            "email" => trim($_POST["email"]),
            "phone" => trim($_POST["phone"]),
            "address" => trim($_POST["address"]),
        ];

        $imageFile = $_FILES["profile_image"] ?? null;

        if(addCustomer($data, $imageFile)){
            header("Location: customer-list.php");
        }
    }

    ob_start();
?>

<div class="d-flex align-items-center justify-content-between border-bottom" >
    <h1>
        Add Customer
    </h1>
</div>

<div class="row" >
    <div class="col-md-1 col-lg-2" ></div>
    <div class="col-md-10 col-lg-8 p-2" >
        <form method="POST" enctype="multipart/form-data" >
            <div class="mb-3" >
                <label class="form-label" for="firstName" >First Name:</label>
                <input class="form-control" id="firstName" name="firstName" placeholder="John" required type="text" />
            </div>
            <div class="mb-3" >
                <label class="form-label" for="lastName" >Last Name:</label>
                <input class="form-control" id="lastName" name="lastName" placeholder="Doe"  type="text" />
            </div>
            <div class="mb-3" >
                <label class="form-label" for="email" >Email:</label>
                <input class="form-control" id="email" name="email" required placeholder="johndoe@example.com"  type="email" />
            </div>
            <div class="mb-3" >
                <label class="form-label" for="phone" >Phone:</label>
                <input class="form-control" id="phone" name="phone" required placeholder="+923210000000"  type="text" />
            </div>
            <div class="mb-3" >
                <label class="form-label" for="address" >Address:</label>
                <input class="form-control" id="address" name="address" required placeholder="Street, City, Country"  type="text" />
            </div>
            <div class="mb-3" >
                <label class="form-label" for="profile_image" >Profile Image:</label>
                <input class="form-control" id="profile_image" name="profile_image"  type="file" accept="image/*" />
            </div>
            <button type="submit" class="btn btn-success" >Add Customer</button>
            <a href="customer-list.php" class="btn btn-secondary" >Cancel</a>
        </form>
    </div>
    <div class="col-md-1 col-lg-2" ></div>
</div>

<?php 
    $content = ob_get_clean();
    include 'Layout.php';
?>