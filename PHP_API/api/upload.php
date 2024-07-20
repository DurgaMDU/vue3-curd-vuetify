<?php
    // Error handling
    error_reporting(0);

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: multipart/form-data; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once '../config/database.php';
    include_once '../class/customer.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $item = new Customer($db);
    
    $data = json_decode(file_get_contents("php://input"), true);
 
   // echo json_encode($_FILES);  
   // {"file":{"name":"customer-csv.csv","full_path":"customer-csv.csv","type":"text\/csv","tmp_name":"C:\\xampp\\tmp\\phpE58A.tmp","error":0,"size":300}}

   if(isset($_FILES['file']['name'])){
        $fileName  =  $_FILES['file']['name'];
        $tempPath  =  $_FILES['file']['tmp_name'];
        $fileSize  =  $_FILES['file']['size'];
   }else{
        $fileName  =  '';
        $tempPath  =  '';
        $fileSize  =  '';
   }

   if(empty($fileName))
    {
        $Response = array("status_message" => "please select file", "status" => 0);	
    }
    else
    {
        $upload_path = '../upload/'; // set upload folder path 
        
        $fileExt = strtolower(pathinfo($fileName,PATHINFO_EXTENSION)); // get file extension

        $newFilename = $upload_path.time().rand(0,99999).'_'.$fileName;
            
        // valid file extensions
        $valid_extensions = array('csv'); 
                        
        // allow valid file formats
        if(in_array($fileExt, $valid_extensions))
        {				
            //check file not exist our upload folder path
            if(!file_exists($newFilename))
            {
                // check file size '2MB'
                if($fileSize > 2000000){
                    // move file from system temporary path to our upload folder path 
                    //move_uploaded_file($tempPath, $newFilename)

                    $Response = array("status_message" => "Sorry, your file is too large, please upload 2 MB size", "status" => 0);	
                }else if($fileSize > 0 && $fileSize < 2000000){

                        $file = fopen($tempPath, "r");
                        $importCount = 0;
                        $importupdateCount = 0;
                        $errCount = 0;
                        $duplicateCount = 0;
                        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                            if (! empty($column) && is_array($column)) {
                                if ($item->hasEmptyRow($column)) {
                                    continue;
                                }
                               // print_r($column); 
                               /*Array structure
                                (
                                    [0] => firstname
                                    [1] => surname
                                    [2] => email
                                    [3] => location
                                )*/
                                if (isset($column[2]) && !empty($column[2])) {
                                    
                                    $email = $column[2];//Email has unique field
                                    //getSingleCustomerbyEmail - Return if available userid to update record
                                    $checkUser = $item->getSingleCustomerbyEmail($email); 
                                    $item->id='';                                 
                                    $item->firstname = $column[0];
                                    $item->surname = $column[1];
                                    $item->email = $column[2];//Email has unique field
                                    $item->location = $column[3];
                                    $item->created= date('Y-m-d H:i:s');
                                    $item->updated= date('Y-m-d H:i:s');
                                    
                                    if($checkUser){ 
                                        //Update record            
                                        $item->id=$checkUser; 
                                        if($item->updateCustomer()){ 
                                            $importupdateCount++;
                                        }else{
                                            $duplicateCount++;
                                        }  
                                    }else{
                                        //Insert record
                                        if($item->createCustomer()){
                                            $importCount++;
                                        }else{
                                            $errCount++;
                                        }  
                                    }
                                }else {
                                    $errCount++;
                                }
                            } else {
                                $errCount++;
                            }
                        }
                        $type = "success"; $message ="";
                        if (($importCount == 0 && $importupdateCount == 0) || $errCount > 0) {
                            $type = "error";
                            $message .= $errCount." Error data found.<br />";
                        }else{
                            $message .= "CSV Upload completed.<br />";
                        }

                        if($importCount > 0) {
                            $message .= $importCount." customer data added.<br />";
                        }
                        if($importupdateCount > 0) {
                            $message .= $importupdateCount." customer data Updated.<br />";
                        }
                        if($duplicateCount > 0){
                            $message .= $duplicateCount. " Duplicated data found.<br />";
                        }

                        $output["type"] = $type;
                        $output["message"] = $message;
                        $output["NewRecord"] =$importCount;
                        $output["UpdateRecord"] =$importupdateCount;
                        $output["ErrorRecord"] =$errCount;
                        $output["DuplicateRecord"] =$duplicateCount;

                        $Response = array("result"=>$output, "status_message" => "File uploaded", "status" => 1);	
                   
                }
                else{
                    $Response = array("status_message" => "Sorry! your file is not uploaded.", "status" => 0);	
                }
            }
            else
            {		
                $Response = array("status_message" => "Sorry! file already exists check upload folder.", "status" => 0);	
            }
        }
        else
        {		
            $Response = array("status_message" => "Sorry! only CSV files are allowed.", "status" => 0);	
        }
    }

	echo json_encode($Response);	
?>