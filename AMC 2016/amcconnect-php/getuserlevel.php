<?php


// include db connect class
require_once __DIR__ . '/db_connect.php';
 

// connecting to db
$db = new DB_CONNECT();


 
// check for post data
if (isset($_POST['mail'])) {
    $email = $_POST['mail'];
   
    
 

 //
 //SQL injection may kill you , Save Yourself man!!
 //
 
    // get a user from user table
    $query = "SELECT * FROM user WHERE email_id =\"".$email."\"";
    
  
    
    $result = mysql_query($query);
 
    if (!empty($result)) {
        // check for empty result
        if (mysql_num_rows($result) > 0) {
 
            $resulter = mysql_fetch_array($result);
            
            $ulevel = $resulter['level'];
            
            echo $ulevel;

            }
            
    }else{
    	echo "0";
    }

}


?>