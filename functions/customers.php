<?php

require_once(__DIR__."/../config/db.php");

function addCustomer($data, $imageFile){
    global $conn;

    $imagePath = null;
    if($imageFile && $imageFile["error"] == UPLOAD_ERR_OK){
        $uploadDir = __DIR__ . '/../uploads/customers/';
        if(!is_dir($uploadDir)){
            mkdir($uploadDir,755, true);
        }

        $ext = pathinfo($imageFile["name"], PATHINFO_EXTENSION);
        $filename = 'customer_' . time() . '_' . uniqid() . '.' . $ext;
        $uploadPath = $uploadDir . $filename;
        if(move_uploaded_file($imageFile["tmp_name"], $uploadPath)){
            $imagePath = "uploads/customers/" . $filename;
        }
    }

    $stmt = $conn->prepare("
        INSERT INTO customers (first_name, last_name, email, phone, address, profile_image) VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssssss",
        $data["firstName"],
        $data["lastName"],
        $data["email"],
        $data["phone"],
        $data["address"],
        $imagePath
    );

    return $stmt->execute();
}


function getCustomerById($id){
    global $conn;

    $stmt = $conn->prepare("
        SELECT * FROM customers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}


function getCustomers($page = 1, $limit = 10){
    global $conn;

    $offset = ($page - 1) * $limit;
    $stmt = $conn->prepare("
        SELECT * FROM customers LIMIT ?, ?
    ");
    $stmt->bind_param("ii", $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $customers = [];
    while($row = $result->fetch_assoc()){
        $customers[] = $row;
    }

    $total = $conn->query("SELECT COUNT(*) as count FROM customers")->fetch_assoc()['count'];
    $totalPages = ceil($total / $limit);

    return [
        'customers' => $customers,
        'totalPages' => $totalPages,
        'currentPage'=> $page,
    ];
}

function updateCustomer($id, $data, $imageFile){
    global $conn;

    $customer = getCustomerById($id);

    $imagePath = $customer['profile_image'];
    if($imageFile && $imageFile["error"] == UPLOAD_ERR_OK){
        $uploadDir = __DIR__ . '/../uploads/customers/';
        if(!is_dir($uploadDir)){
            mkdir($uploadDir,755, true);
        }

        $ext = pathinfo($imageFile["name"], PATHINFO_EXTENSION);
        $filename = 'customer_' . time() . '_' . uniqid() . '.' . $ext;
        $uploadPath = $uploadDir . $filename;
        if(move_uploaded_file($imageFile["tmp_name"], $uploadPath)){
            $imagePath = "uploads/customers/" . $filename;
        }
        if($customer['profile_image']){
            $oldImagePath = __DIR__ . '/../' . $customer['profile_image'];
            if(file_exists($oldImagePath)){
                unlink($oldImagePath);
            }
        }
    }

    $stmt = $conn->prepare('
        UPDATE customers SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ?, profile_image = ? WHERE id = ?');
    $stmt->bind_param('ssssssi', $data["first_name"], $data["last_name"], $data["email"], $data["phone"], $data["address"], $imagePath, $id);
    return $stmt->execute();
}

function deleteCustomer($id){   

    global $conn;
    $customer = getCustomerById($id);

    if($customer["profile_image"]){
        $imagePath = __DIR__ . '/../' . $customer["profile_image"];
        if(file_exists($imagePath)){
            unlink($imagePath);
        }
    }
    $stmt = $conn->prepare('DELETE FROM customers WHERE id = ?');
    $stmt->bind_param('i', $id);
    return $stmt->execute();

}


?>