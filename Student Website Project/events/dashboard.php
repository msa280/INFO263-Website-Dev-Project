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
	
	$query_events 	= "SELECT * FROM front_events";
	$objinfoEvents 	= $DB->Select($query_events);
	$upcoming 	= 0;
	$inprogress = 0;
	$completed	= 0;
	if($objinfoEvents){
		foreach($objinfoEvents as $event){
			$eventStartTime = $event->date . ' ' . $event->start_time;
			$eventEndTime = $event->date . ' ' . $event->end_time;
			
			$date_now = new DateTime();
			
			$date_start	= new DateTime($eventStartTime);
			$date_end	= new DateTime($eventEndTime);

			if ($date_now >= $date_start && $date_now <= $date_end) {
				$inprogress = $inprogress+1;
			}else if($date_now < $date_start){
				$upcoming = $upcoming+1;
			}else if($date_now > $date_end){
				$completed = $completed+1;
			}
		}
	}

?>
<!DOCTYPE html>
<html>
<head>
<title>My Dashboard</title>
</head>

<style type="text/css">
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
	.new_event{
		width: 100%;
	    float: left;
	    margin: 0 auto;
		text-align: center;
	}
	.new_event p{
		font-size: 24PX;
	    font-weight:  bold;
	    text-align: center;
	}
	.all_event{
		width: 100%;
	    float: left;
	   
	    margin: 0 auto;
		text-align: center;
	}
	.all_event p{
		font-size: 24PX;
	    font-weight:  bold;
	    text-align: center;
	}
	
	#content{
		width:100%;	
	}
	#progress_section{
		width:30%;
		float:left;
		text-align:center;
	}
	#events_section{
		width:69%;
		float:left;
		text-align:center;
	}
	
	.heading{
		width:100%;
		font-size:20px;
		font-weight:bold;
		color:#FFF;
		background-color:#35363A;
	}
	.event_progress_image{
		width:100%;
		background-color:#35363A;
	}
	.progress_status{
		width:100%;
		font-size:20px;
		font-weight:bold;
		color:#FFF;
		background-color:#35363A;
		padding: 0px 0px 20px 0px;
	}
	a{
		color:#FFFFFF;
	}
</style>
<body style="background-color: #212F3C;">
<div class="container">
	<div class="wrapper">
		<div class="header">
			<div class="profile_icon"><img src="images/profile.png" style="margin-top: 17px;" /></div>
            <div class="logo"><img src="images/logo.png" /></div>
			<div class="heading">My Dashboard<br /><a href="logout.php">Logout</a></div>
			
		</div>
		<!----progress div----->
        
        <div id="content">
        	<div id="progress_section">
            	<div class="heading">Up Coming</div>
                <div class="event_progress_image"><img src="images/upcoming.png" /></div>
                <div class="progress_status"><?php echo $upcoming; ?></div>
                
                <div class="heading">In Progress</div>
                <div class="event_progress_image"><img src="images/inprogress.png" /></div>
                <div class="progress_status"><?php echo $inprogress; ?></div>
                
                <div class="heading">Completed</div>
                <div class="event_progress_image"><img src="images/completed.png" /></div>
                <div class="progress_status"><?php echo $completed; ?></div>
            </div>
            
            <div id="events_section">
                <div style="float: left;width:1%;">&nbsp;</div>
                <div class="new_event"><img src="images/newevent.png" /><p><a href="create_event.php">Create New Event</a></p></div>
                <div style="float: left;width: 7%;">&nbsp;</div>
                <div class="all_event"><img src="images/allevent.png" /><p><a href="view_event.php">View All Events</p></a></div>
                <div style="float: left;width:1%;">&nbsp;</div>
            </div>
            
        </div>
		
		<div>&nbsp;</div>
		
	</div>	
</div>

</body>
</html>
