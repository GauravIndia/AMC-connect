<?php



// include db connect class
require_once __DIR__ . '/db_connect.php';
 
// connecting to db
$db = new DB_CONNECT();
 
// check for post data
if (isset($_GET['user'])&&isset($_GET['token'])) {
    $email = $_GET['user'];
    $token = $_GET['token'];
    

 //
 //SQL injection may kill you , Save Yourself man!!
 //
 
    // get a user from user table
    $query = "SELECT * FROM user WHERE email_id =\"".$email."\"";
    
    $result = mysql_query($query);
 
    if (!empty($result)) {
        // check for empty result
        if (mysql_num_rows($result) > 0) {
 
            $result = mysql_fetch_array($result);
 
            
            $stoken = $result['token'];
            $ulevel = $result['level'];
            
            if(strcmp($token,$stoken)==0){
            
 		echo "inthis";           
            
            	$confuri = mt_rand();
            	$ulevel = $ulevel + 1;
            	$result = mysql_query("UPDATE user SET level = '$ulevel' WHERE email_id = '$email'");
            	$result = mysql_query("UPDATE user SET token = '$confuri' WHERE email_id = '$email'");
            	echo "Thanks For Showing Appreciation !!";

            }else{
            	echo "Link Expired";
            }
            
    }else{
    	echo "The Link is Expired";
    }

}
}

?>