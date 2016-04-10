<?php
 

// Path to move uploaded files
$target_path = "uploads/";
 
// array for final json respone
$response = array();
$confuri = "a"; 
// getting server ip address
$server_ip = gethostbyname(gethostname());

// final file url that is being uploaded
$file_upload_url = 'http://' . $_SERVER[HTTP_HOST] . '/' . 'amcconnect' . '/' . $target_path;
 
 
if (isset($_FILES['image']['name'])) {
    $target_path = $target_path . basename($_FILES['image']['name']);
 
    // reading other post parameters
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $website = isset($_POST['website']) ? $_POST['website'] : '';
 
    $response['file_name'] = basename($_FILES['image']['name']);
    $response['email'] = $email;
    $response['website'] = $website;
 
    try {
        // Throws exception incase file is not being moved
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            // make error flag true
            $response['error'] = true;
            $response['message'] = 'Could not move the file!';
        }
 
        // File successfully uploaded
        $response['message'] = 'File uploaded successfully!';
        $response['error'] = false;
        $response['file_path'] = $file_upload_url . basename($_FILES['image']['name']);
        
        $f_finaluri = $response['file_path'];
        
    } catch (Exception $e) {
        // Exception occurred. Make error flag true
        $response['error'] = true;
        $response['message'] = $e->getMessage();
    }
} else {
    // File parameter is missing
    $response['error'] = true;
    $response['message'] = 'Not received any file!F';
}

if(isset($_POST['email'])){
	try{
	
	$email = $_POST['email'];
	$address = $_POST['address'];
	$details = $_POST['details'];
	$category = $_POST['category'];
	  // include db connect class
	    require_once __DIR__ . '/db_connect.php';
	 
	    // connecting to db
	    $db = new DB_CONNECT();
	    
	 
	    // mysql inserting a new row
	    $result = mysql_query("INSERT INTO problem(id, imageuri, address,useremail,category,status,details) VALUES(NULL,'$f_finaluri', '$address', '$email','$category',0,'$details')");
	    
	
	    
	$response['error'] = false;
    	$response['message2'] = 'Database Updated';
    	
    	//token Procedure for badges
    	
    	$confuri = mt_rand();
	
	$result = mysql_query("UPDATE user SET token = '$confuri' WHERE email_id = '$email'");
    	
	}catch(Exception $e){
	
	$response['error'] = true;
    	$response['message2'] = 'Cannot edit database';
	}
}

if($response['error']!=true){

	
	
	$to = "amcdemo5@gmail.com";
	$subject = "New Complaint about garbage";
	$from = $_POST['email'];
	$message = "This is a mail from ".$from."Stating the problem of garbage in his area. Below are the details";
	$message = $message."\nAddress : ".$_POST['address'];
	$message = $message."\nCategory : ".$_POST['category'];
	$message = $message."\nAdditional Details : ".$_POST['details'];
	$message = $message."\nImage : ".$f_finaluri;
	if($confuri !="a"){
	
	$urltoupdate = "Gauravjat.in/amcconnect/to_confirm.php?user=".$email."&token=".$confuri;
	$message = $message."\nIs This Genuine ?? click here for yes : <a href='".$urltoupdate."' target='_BLANK'>Confirm</a>";
	
	}
	

	
	$headers = 'From: webmaster@Gauravjat.in' . "\r\n" .
    'Reply-To:'.$_POST['email'] . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
	
	mail($to,$subject,$message,$headers);
	
}


 
// Echo final json response to client
echo json_encode($response);
?>