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
	
	if($_POST){
		$query_events 	= "SELECT * FROM front_events WHERE event_name like '%". $_POST['event_name'] ."%'";
		$objinfoEvents 	= $DB->Select($query_events);
	}else{
		$query_events 	= "SELECT * FROM front_events";
		$objinfoEvents 	= $DB->Select($query_events);
	}
	//echo '<pre>';print_r($objinfoEvents);
?>

<!DOCTYPE html>
<html>
<head>
<title>View All Events</title>
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
	.headding{
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
    	border-bottom: 1px solid #666;
		text-align:center;
	}

	input[type="text"], input[type="date"], input[type="time"] {
		width: 50%;
		text-align:center;
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
	.detail{
		width: 100%;
	    float: left;
	}
	
	.dropbtn {
		background-color:#666;
		color: white;
		padding: 10px;
		font-size: 16px;
		border: none;
		width:20%;
		cursor:pointer;
	}

	.dropdown {
	  position: relative;
	  display: inline-block;
	  width:100%;
	  text-align:center;
	}
	
	.dropdown-content {
	  display: none;
	  position: absolute;
	  background-color: red;
	  width:20%;
	  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
	  z-index: 1;
	}
	
	.dropdown-content a {
	  color: black;
	  padding: 12px 16px;
	  text-decoration: none;
	  display: block;
	  margin-bottom: 10px;
	}
	.dropdown-content a:hover {background-color: #F96962;}
	
	.dropdown:hover .dropdown-content {display: inline-block;}
	
	.dropdown:hover .dropbtn {background-color:#333;}
	
	table{
		width: 100%;
		border-top: 1px solid #666;
		border-bottom: 1px solid #666;
	}
	td{
		text-align: center;
		border-top: 1px solid #666;
		padding: 10px 0;
	}
	th{
		padding: 10px 0;
		background-color:#666;
	}
	a{
		color:red;
	}
	.btn_update{
		width:120px;
		height:40px;
		font-size:14px;
		background-color:#060;
		color:#FFF;
	}
</style>

<body>
<div class="container">
	<div class="wrapper">
		
        <div class="header">
			<div class="profile_icon"><img src="images/profile.png" style="margin-top: 17px;" /></div>
            <div class="logo"><img src="images/logo.png" /></div>
			<div class="headding">View All Events<br /><a href="logout.php">Logout</a></div>
        </div>
        
        <div style="width:100%; text-align:center;">
        	<a href="dashboard.php"><img src="images/back_arrow.png" /></a>
        </div>
        

        <form action="view_event.php" method="post">
        <div class="event_cluster">
			<input type="text" placeholder="Enter Event Name" name="event_name" required />
			<div class="dropdown">
            	<button type="submit" class="dropbtn">Search Event</button>

            </div>
		</div>
        </form>
        	
		<div class="detail">
			<table>
			  <tr>
			    <th>Event Name</th>
			    <th>Start Date</th>
			    <th>Start Time</th>
			    <th>End Time</th>
			    <th>Status</th>
			    <th>Details</th>
                <th>Update</th>
			  </tr>
			  
              <?php
              	if($objinfoEvents){
					foreach($objinfoEvents as $event){
			  ?>
              			<tr>
                        	<td><?php echo $event->event_name; ?></td>
                        	<td><?php echo $event->date; ?></td>
                        	<td><?php echo $event->start_time; ?></td>
                        	<td><?php echo $event->end_time; ?></td>
                        	
                            <?php
								$eventStartTime = $event->date . ' ' . $event->start_time;
								$eventEndTime = $event->date . ' ' . $event->end_time;
								
								$date_now = new DateTime();
								
								$date_start	= new DateTime($eventStartTime);
								$date_end	= new DateTime($eventEndTime);
								$status = '';
								if ($date_now >= $date_start && $date_now <= $date_end) {
									$status = 'In Progress';
								}else if($date_now < $date_start){
									$status = 'UpComing';
								}else if($date_now > $date_end){
									$status = 'Completed';
								}
							?>
                            
                            <td><?php echo $status; ?></td>
                        	<td><a href="delete.php?delete_id=<?php echo $event->id; ?>">Delete Event</a></td>
                            <td><a href="update_event.php?event_id=<?php echo $event->id; ?>"><button class="btn_update">Update Event</button></a></td>
                      	</tr>				
              <?php			
					}	
				}
			  ?>	
			</table>
		</div> 
            <div>&nbsp;</div>
            
		</div>	
</div>

</body>
</html>
