<?php
    // Error handling
    error_reporting(0);

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    
    include_once '../config/database.php';
    include_once '../class/customer.php';
    $database = new Database();
    $db = $database->getConnection();
    $items = new Customer($db);
    $stmt = $items->getCustomer();
    $itemCount = $stmt->rowCount();

    //echo json_encode($itemCount);
    if($itemCount > 0){
        
        $Arr = array();
        $Arr["row"] = array();
        $Arr["count"] = $itemCount;
        $Arr["status"] = 1;
        $Arr["status_message"] = 'Data Available';
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $e = array(
                "id" => $id,
                "firstname" => $firstname,
                "surname" => $surname,
                "email" => $email,
                "location" => $location,
                "created" => $created,
                "updated" => $updated
            );
            array_push($Arr["row"], $e);
        }
        echo json_encode($Arr);
    }
    else{
        echo json_encode(
            array("row" => array(), "count" => 0,'status' => 0,"status_message" => "No record found.")
        );
    }
?>