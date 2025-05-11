<?php 

    require_once(__DIR__."/functions/products.php");


    $id = isset($_GET["id"]) ? intval($_GET["id"]) :0;
    if(empty($id)){
        die("Product ID is required!");
    }
    if(deleteProduct($id)){
        header("Location: manage-inventory.php");
        exit;
    } else {
        die("Product deletion failed!");
    }

