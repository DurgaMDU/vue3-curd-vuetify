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
    
    $item->id = $data->id;
    if($item->id){
        if($item->deleteCustomer()){
            $messgae = "Customer data deleted successfully.";
                $status = 1;
            } else{
                $messgae = "Data could not be deleted.";
                $status = 0;
            }
    } else {
		$messgae = "Invalid request.";
		$status = 0;
	}
    $Response = array(
		'status' => $status,
		'status_message' => $messgae
	);
	echo json_encode($Response);
?>