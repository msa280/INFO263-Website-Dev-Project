<?php
	include("configuration.php");

	//For Database
	include("classes/Database.class.php");
	include("classes/DB.class.php");
	
	$DB = new DB();
	
	if(isset($_REQUEST['delete_id'])){
		$query_DEL = "DELETE FROM front_events WHERE id = '".$_REQUEST['delete_id']."'";

		$objinfo_DEL = $DB->Delete($query_DEL);

		if( $objinfo_DEL ){
			header('Location:view_event.php');
			exit;
		}else{
			echo "Failed to Delete the Event";exit;
		}
	}
?>