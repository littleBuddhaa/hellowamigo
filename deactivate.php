<?php
require 'connection.php';
include("user.php");
include("posts.php");

if(isset($_SESSION['username']))
{
	$user_logged = $_SESSION['username'];
	$user_details = mysqli_query($connect, "SELECT * FROM users WHERE username = '$user_logged'");
	$user = mysqli_fetch_array($user_details);

}

else{
	header("Location: register.php");
}

if(isset($_POST['deactivate']))
{
	$query = mysqli_query($connect , "UPDATE users SET user_closed ='yes' WHERE username = '$user_logged'") or die ('error!');
	session_destroy();
	header("Location: register.php");
}
if(isset($_POST['cancel']))
{
	header("Location: settings.php");

}
?>


<html>
<head>
	<title>Amigo</title>
		<script src = "js/bootstrap.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

		<link rel = "stylesheet" type = "text/css " href = "css/bootstrap.css">
		<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
		<link rel = "stylesheet" type = "text/css" href = "css/style.css">
		<link href="https://fonts.googleapis.com/css?family=Cabin+Sketch|Dancing+Script|Fredoka+One|Londrina+Outline|Monoton|Noto+Sans|Open+Sans:300|Pacifico|Poiret+One|Questrial|Quicksand|Slabo+27px|Source+Sans+Pro:200,400" rel="stylesheet">

		<link href="https://fonts.googleapis.com/css?family=Cabin+Sketch|Dancing+Script|Fredoka+One|Londrina+Outline|Monoton|Noto+Sans|Open+Sans:300|Pacifico|Poiret+One|Questrial|Quicksand|Slabo+27px|Source+Sans+Pro:200,400" rel="stylesheet">
</head>
<body style="background-color :#e6e9e7"  >


	<div class = "nav_bar">

			<div class = "logosmall">	
			<a style ="color: white;margin-left: 30px;" href = "index.php">Amigo</a>


		 </div>


		  <div class = "search" style=" margin-top: -2.5%;margin-left: 15%;">
		 	<form action ="search_results.php" method = "GET" name = "search_form" style="width: 30%">
		 		<input type = "text"  name ="q" placeholder ="Search for a User.." autocomplete = "off" id ="search_text">
		 		<div class = "button_holder">
		 			<img id = "magnify" src = "images/search2.png" >
		 		</div>
		 	</form>

		 </div>
	 		<div class ="nav">

	 			<a href= "<?php echo $user_logged; ?>" ><img src = "<?php echo $user['profile_pic']; ?>" style="border-radius:100px;    border: 1px solid #e6e9e7;width: 38px;
margin-top: 0px;"></a>
	 			<a href= "index.php" ><img id = "navb"src = "images/nav/home.png"> </a>
	 			<a href= "friend_request.php" ><img id ="navb" src = "images/nav/add_friend.png"> </a>
	 			<a href= "Messages.php" ><img id = "navb" src = "images/nav/messages.png"> </a>
	 			
	 			<a href= "settings.php" > <img id="navb" src = "images/nav/settings.png"></a>
	 			<a href= "forms/logout.php" ><img id="navb" src = "images/nav/log_out.png"></a>
	 		</div>

	</div> 

<div class ="wrapper">	
	<div class = "pattern">
		<img src ="dots.png" height="900vw" width="auto">
	</div>
	<div class = "feed column">
		<div class = "green"><span class ="head"> We'll miss you! </span> </div><br>
		<p class ="no" style="margin-left:0">
			Are you sure you want to deactivate your account?<br><br> Other users won't be able to see your posts and activities!<br><br>
			You can activate your account by logging in anytime. <br><br><br>
		</p>

			<form action ="deactivate.php" method = "POST" id = "settings">
			
			<input type = "submit" name = "cancel"  value ="Cancel"  >
			

		</form>
		<form action ="deactivate.php" method = "POST" id = "settings2">
			<input type = "submit" name = "deactivate"   value ="Deactivate Account" style="margin-top:-40px;">
			
			

		</form>
	
		


	</div>
</div>
