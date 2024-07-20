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
    $data = json_decode(file_get_contents("php://input"));
    $item->firstname = $data->firstname;
    $item->surname = $data->surname;
    $item->email = $data->email;
    $item->location = $data->location;
    $item->created = date('Y-m-d H:i:s');
    if(isset($item->firstname) && isset($item->email)){
        try {
            if($item->createCustomer()){
                $messgae = "customer data created successfully.";
                $status = 1;
            } else{
                $messgae = "Data could not be created.";
                $status = 0;
            }
        }catch(Exception $e){
            $messgae = $e->getMessage();
            $status = 0;
        }
    }else{
        $messgae = "Firstname and Email is required";
        $status = 0;      
    }

    $Response = array(
		'status' => $status,
		'status_message' => $messgae
	);
	echo json_encode($Response);
?>