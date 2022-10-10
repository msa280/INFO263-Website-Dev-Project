<?php
	include("configuration.php");
	
	session_start();

	if(!isset( $_SESSION['logged_In_User_id'] )){
		header('Location:index.php');
		exit;	
	}
	
	//For Database
	include("classes/Database.class.php");
	include("classes/DB.class.php");
	
	$DB = new DB();
	
	if(isset($_REQUEST['event_id']) && !empty($_REQUEST['event_id'])){
		$query_events 	= "SELECT * FROM front_events WHERE id = '". $_REQUEST['event_id'] ."'";
		$objinfoEvents 	= $DB->Select($query_events);
	}else{
		header('Location:view_event.php');
		exit;
	}
	//echo '<pre>';print_r($objinfoEvents);exit;
	$query_events 		= "SELECT event_id, event_name FROM front_event ORDER BY event_id ASC";
	$objinfo_events 	= $DB->Select($query_events);
	
	$query_cluster 		= "SELECT cluster_id, cluster_name FROM front_cluster ORDER BY cluster_id ASC";
	$objinfo_cluster 	= $DB->Select($query_cluster);
	
	$query_room 		= "SELECT room_id, room_name FROM front_room ORDER BY room_id ASC";
	$objinfo_room 		= $DB->Select($query_room);
	//echo '<pre>';print_r($objinfo_cluster);
?>

<!DOCTYPE html>
<html>
<head>
<title>Update An Event</title>
</head>
<style>
	.container{
		width: 1024px;
		margin: 0 auto;
	}
	.wrapper{
		width: 100%;
		margin: 0 auto;
	}
	.header{
		width: 100%;
	    background-color: #35363A;
	    float: left;
	}
	.logo{
		width: 33%;
		float: left;
		text-align: center;
	}
	.heading{
		width: 33%;
	    float: left;
	    font-size: 27px;
	    text-align: center;
	    margin: 40px 0;
	}	
	.profile_icon{
		width: 33%;
		float: left;
		text-align: center;
	}
	.event_cluster{
	    width: 100%;
    	float: left;
		text-align:center;
	}
	.btn1{
		width: 40%;
	    margin:30px auto;
	    height: 35px;
	    color: red;
	    border: 1px solid;
	    background: none;
	    font-weight: bold;
	    font-size: 15px;
	}
	input[type="text"], input[type="date"], input[type="time"] {
		width: 50%;
		background: #f9fafa;
		background-color: rgb(249, 250, 250);
		border: #c2c4c5 1px solid;
		height: 20px;
		padding: 10px;
		line-height: 30px;
		border-radius: 5px;
		-moz-border-radius: 5px;
		-webkit-border-radius: 5px;
		outline: none;
		margin: 20px auto;
	}
	::placeholder{
		font-weight: bold;
		color: #b9b8b8;
	}
	.event{
		width: 100%;
    	float: left;
    	border-bottom: 1px solid red;
	}
	#date{
		width: 50%;
	}
	.event_date{
		width: 25%;
	    float: left;
		text-align:center;
		padding:15px 0px 10px 0px;
	}
	.room{
	    width: 100%;
    	float: left;
		text-align:center;
	}
	.btn2{
		width: 15%;
	    margin:30px auto;
	    height: 35px;
	    color: red;
	    border: 1px solid;
	    background: none;
	    font-weight: bold;
	    font-size: 15px;
	}
	.btn{
    	width: 100%;
		margin-top:10px;
	}
	#btn_update_event{
		width: 50%;
	    height: 50px;
	    color: white;
	    background: #666;
	    border: none;
	    font-weight: bold;
	    font-size: 15px;
	    border-radius: 5px;
		cursor:pointer;
	}
	a{
		color:red;
	}
	.custom-select {
		width: 52%;
		background: #f9fafa;
		border: #c2c4c5 1px solid;
		height: 30px;
		padding: 0px 5px 0px 5px;
		line-height: 30px;
		border-radius: 5px;
		-moz-border-radius: 5px;
		-webkit-border-radius: 5px;
		outline: none;
	}
</style>

<script src="js/jquery-latest.min.js"></script>

<body>
<div class="container">
	<div class="wrapper">
		<input type="hidden" id="e_id" name="e_id" value="<?php echo $objinfoEvents[0]->id; ?>" />
        <div class="header">
			<div class="profile_icon"><img src="images/profile.png" style="margin-top: 17px;" /></div>
            <div class="logo"><img src="images/logo.png" /></div>
			<div class="heading">Update An Event<br /><a href="logout.php">Logout</a></div>
        </div>
        
        <div style="width:100%; text-align:center;">
        <a href="dashboard.php"><img src="images/back_arrow.png" /></a>
        </div>
        
		<div class="event_cluster">
			
            <div class="form_row">
            	<div class="label">
            		<label for="eventName">Event Name:&nbsp;</label>
            	</div>
                <div class="form_field">
                	<select class="custom-select" name="event_name" id="event_name">
                    	<option value="">Please Select</option>
                        <?php
                        	if($objinfo_events){
								foreach($objinfo_events as $event_row){
						?>
                        			<option value="<?php echo $event_row->event_id .'_'. $event_row->event_name; ?>" <?php if( $event_row->event_name == $objinfoEvents[0]->event_name ){echo 'selected="selected"'; }else{ echo ''; } ?> ><?php echo $event_row->event_name; ?></option>
                        <?php
								}	
							}
						?>
                    </select>
				</div>
            </div>
            
            <div class="form_row">
            	<div class="label">
            		<label for="clusterName">Cluster Name:&nbsp;</label>
            	</div>
                <div class="form_field">
                	<select class="custom-select" name="cluster_name" id="cluster_name">
                    	<option value="">Please Select</option>
                        <?php
                        	if($objinfo_cluster){
								foreach($objinfo_cluster as $cluster_row){
						?>
                        			<option value="<?php echo $cluster_row->cluster_name; ?>" <?php if( $cluster_row->cluster_name == $objinfoEvents[0]->cluster_name ){echo 'selected="selected"'; }else{ echo ''; } ?>><?php echo $cluster_row->cluster_name; ?></option>			
                        <?php
								}	
							}
						?>
                    </select>
				</div>
            </div>
            
            <div class="form_row">
            	<div class="label">
            		<label for="eventDate">Event Date:&nbsp;</label>
            	</div>
                <div class="form_field">
                	<input type="date" placeholder="Select Event Date" name="eventdate" id="eventdate" value="<?php echo $objinfoEvents[0]->date; ?>" />
				</div>
            </div>
            
            <div class="form_row">
            	<div class="label">
            		<label for="eventStartTime">Start Time:&nbsp;</label>
            	</div>
                <div class="form_field">
                	<?php 
						$startTime_Actual = explode(":", $objinfoEvents[0]->start_time);
						$startTime_Actual = $startTime_Actual[0] . ":" . $startTime_Actual[1];
					?>
                    <input type="time" placeholder="Start Time" name="start_time" id="start_time" value="<?php echo $startTime_Actual; ?>" />
				</div>
            </div>
            
            <div class="form_row">
            	<div class="label">
                    <label for="eventEndDate">End Time:&nbsp;</label>
            	</div>
                <div class="form_field">
                	<?php 
						$endTime_Actual = explode(":", $objinfoEvents[0]->end_time);
						$endTime_Actual = $endTime_Actual[0] . ":" . $endTime_Actual[1];
					?>
                	<input type="time" placeholder="End Time" name="end_time" id="end_time" value="<?php echo $endTime_Actual; ?>" />
				</div>
            </div>
            
            <div class="form_row">
            	<div class="label">
            		<label for="eventOffset">Offset:&nbsp;</label>
            	</div>
                <div class="form_field">
                	<input type="text" placeholder="" name="offset" id="offset" value="<?php echo $objinfoEvents[0]->time_offset; ?>" readonly />
				</div>
            </div>
			
            <div class="form_row">
            	<div class="label">
            		<label for="selectRoom">Room:&nbsp;</label>
            	</div>
                <div class="form_field">
                	<select class="custom-select" name="select_room" id="select_room">
                        <option value="">Please Select</option>
                        <?php
                        	if($objinfo_room){
								foreach($objinfo_room as $room_row){
						?>
                        			<option value="<?php echo $room_row->room_name; ?>" <?php if( $room_row->room_name == $objinfoEvents[0]->class_name ){echo 'selected="selected"'; }else{ echo ''; } ?>><?php echo $room_row->room_name; ?></option>			
                        <?php
								}	
							}
						?>
                    </select>
				</div>
            </div>
            
            <div class="form_row">
            	<div class="label">
					&nbsp;
            	</div>
                <div class="form_field">
                	<button type="button" id="btn_update_event">Update Event</button>
				</div>
            </div>    
            
        </div>
        
        <div>&nbsp;</div>
        
	</div>
</div>

</body>

<script type="text/javascript">
	$(document).ready(function(){
		
		$("#eventdate, #start_time, #end_time").focusout(function(){
			if($("#eventdate").val()!="" && $("#start_time").val()!="" && $("#end_time").val()!=""){
				$.ajax({
					type: "GET",
					url: 'create_event_ajax.php?calculate_offset=yes&&'+'e_eventDate='+$("#eventdate").val()+'&&e_startTime='+$("#start_time").val()+'&&e_endTime='+$("#end_time").val(),
					async: false,
					success: function(objResponse){
						$("#offset").val(objResponse);
						return true;
					}
				});
			}
		});
		
		
		$("#btn_update_event").click(function(){
			
			if($("#event_name").val() == "" || $("#cluster_name").val() == "" || $("#eventdate").val() == "" || $("#start_date").val() == "" || $("#end_date").val() == "" || $("#select_room").val() == ""){
				alert("All Fields are Requried!");
				return false;
			}
			//alert($("#event_name").val());return true;
			$.ajax({
				type: "GET",
				url: 'create_event_ajax.php?update=yes&&e_eventName='+$("#event_name").val()+'&&e_clusterName='+$("#cluster_name").val()+'&&e_eventDate='+$("#eventdate").val()+'&&e_startTime='+$("#start_time").val()+'&&e_endTime='+$("#end_time").val()+'&&e_room='+$("#select_room").val()+'&&e_offset='+$("#offset").val()+'&&e_id='+$("#e_id").val(),
				async: false,
				success: function(objResponse){
					//alert(objResponse);return true;
					if( objResponse == "FAILURE" ){
						alert("There is some Problem with Data Save Logic!");
						return false;
					}else{
						alert("Event Updated Successfully!");
						window.location.href = "view_event.php";
					}
				}
			});
		});
	});
</script>

</html>
