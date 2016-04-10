<?php
 
/*
 * Following code will get single user details
 * A user is identified by email id (email_id)
 */
 
// array for JSON response
$response = array();
 
// include db connect class
require_once __DIR__ . '/db_connect.php';
 
// connecting to db
$db = new DB_CONNECT();
 
// check for post data
if (isset($_POST['mail'])&&isset($_POST['password'])) {
    $mail = $_POST['mail'];
    $password = $_POST['password'];
    $password = md5($password);
    
 //
 //SQL injection may kill you , Save Yourself man!!
 //
 
    // get a user from user table
    $query = "SELECT * FROM user WHERE email_id =\"".$mail."\"";
    
    $result = mysql_query($query);
 
    if (!empty($result)) {
        // check for empty result
        if (mysql_num_rows($result) > 0) {
 
            $result = mysql_fetch_array($result);
 
            $user = array();
            $user['name'] = $result['name'];
            $upassword = $result['password'];
            $user['mail'] = $result['email_id'];
            $userlevel = $result['level'];
            
            if(strcmp($password,$upassword)==0)
            {
            // success
            $response["success"] = 1;
            $response["userlevel"] = $userlevel;
 
            // user node
            $response["user"] = array();
            
 
            array_push($response["user"], $user);
 
            // echoing JSON response
            echo json_encode($response);
            }
            else {
            // Wrong Password
            $response["success"] = 0;
            $response["message"] = "Wrong Password";
           
 
            // echo wrong password JSON
            echo json_encode($response);
        	}
        } else {
            // no User found
            $response["success"] = 10;
            $response["message"] = "No User found";
            
 
            // echo no users JSON
            echo json_encode($response);
        }
    } else {
        // no USer found
        $response["success"] = 20;
        $response["message"] = "No User found";
        
 
        // echo no users JSON
        echo json_encode($response);
    }
} else {
    // required field is missing
    $response["success"] = 0;
    $response["message"] = "Required field(s) is missing";
 
    // echoing JSON response
    echo json_encode($response);
}
?>