<?php
    // Error handling
    error_reporting(0);

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    include_once '../config/database.php';
    include_once '../class/customer.php';
    $database = new Database();
    $db = $database->getConnection();
    $item = new Customer($db);
    $item->id = isset($_GET['id']) ? $_GET['id'] : die();
  
    $item->getSingleCustomerbyID();
    if($item->email != null){
        // create array
        $Arr = array(
            "id" =>  $item->id,
            "firstname" => $item->firstname,
            "surname" => $item->surname,
            "email" => $item->email,
            "location" => $item->location,
            "created" => $item->created,
            "updated" => $item->updated
        );
      
        http_response_code(200);
        echo json_encode( array("row" => $Arr,'status' => 1,"status_message" => "Record found."));
    }      
    else{
        echo json_encode(
            array("row" => array(),'status' => 0,"status_message" => "No record found.")
        );
    }
?>