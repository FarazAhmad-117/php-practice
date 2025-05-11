<?php 

    require_once  "functions/customers.php";

    $id = isset($_GET["id"]) ? intval($_GET["id"]) :0;
    if ($id > 0) {
        if(deleteCustomer($id)){
            header("Location: customer-list.php");
            exit;
        }else{
            header("Location: customer-list.php");
        }
    }else{
        die("Invalid ID");
    }

?>