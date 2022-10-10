<?php
	include("configuration.php");

	//For Database
	include("classes/Database.class.php");
	include("classes/DB.class.php");
	
	$DB = new DB();
	
	$query = "SELECT user_id FROM user WHERE username = '".$_REQUEST['username']."' AND password = '".$_REQUEST['password']."'";

	$objinfo = $DB->Select($query);

	if( !empty($objinfo[0]->user_id) ){
		session_start();
		$_SESSION['logged_In_User_id'] = $objinfo[0]->user_id;
		echo "SUCCESS";exit;
	}else{
		echo "FAILED";exit;
	}
?>