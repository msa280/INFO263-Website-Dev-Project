<?php
	include("configuration.php");
	
	session_start();

	if(isset( $_SESSION['logged_In_User_id'] )){
		header('Location:dashboard.php');
		exit;	
	}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
</head>

<style type="text/css">
	.container{
		width: 1024px;
		margin: 0 auto;
	}
	.wrapper{
		width: 50%;
		margin: 0 auto;
		border:1px solid #666;
	}
	.header{
		width: 100%;
	    background-color: #f1f1f1;
	    float: left;
	}
	.logo{
		width: 100%;
		float: left;
		text-align: center;
		background:#35363A;
	}
	.login{
		width:100%;
		text-align:center;
		padding:5px 5px 15px 5px;
	}
	input[type="text"], input[type="date"], input[type="time"], input[type="password"] {
		width: 90%;
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
		text-align:center;
	}
	button{
		color: #FFF;
		background: #35363A;
	    border: 1px solid;
	    font-size: 18px;
	    width: 90%;
	    height: 40px;
	    border-radius: 8px;
	}
	::placeholder{
		font-weight: bold;
	}
</style>

<script src="js/jquery-latest.min.js"></script>

<body>
<div class="container">
	<div class="wrapper">
		<div class="header">
			<div class="logo"><img src="images/logo.png" /></div>
		</div>
        
        <div style="clear:both;">&nbsp;</div>
		
        <!----Input Fields----->
		<div class="login">
            <input type="text" placeholder="Your Username" name="username" id="username" value="" required />
            <input type="password" placeholder="Your Password" name="password" id="password" value="" required />
            
            <div id="error" style="color:#F00;"></div>
            <button type="button" id="btn_login">Login</button>
		</div>	
	</div>	
</div>

</body>

<script type="text/javascript">
	$(document).ready(function(){
		$("#btn_login").click(function(){
			if($("#username").val() == ""){
				$("#error").html("Username and Password are Requried!");
				return false;
			}
			
			if($("#password").val() == ""){
				$("#error").html("Username and Password are required!");
				return false;
			}
			
			$("#error").html("");
			
			$.ajax({
				type: "GET",
				url: 'user_authentication.php?username='+$("#username").val()+'&&password='+$("#password").val(),
				async: false,
				success: function(objResponse){
					if( objResponse == "FAILED" ){
						$("#error").html("The password or username is incorrect.");
						return false;
					}else{
						window.location.href = "dashboard.php";	
					}
				}
			});
		});
	});
</script>

</html>