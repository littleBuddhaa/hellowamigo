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


if(isset($_POST['update_details'])) {

	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$email = $_POST['email'];

	$email_check = mysqli_query($connect, "SELECT * FROM users WHERE email='$email'");
	$row = mysqli_fetch_array($email_check);
	$matched_user = $row['username'];

	if($matched_user == "" || $matched_user == $user_logged) {
		$message = "Details updated!<br><br>";

		$query = mysqli_query($connect, "UPDATE users SET first_name='$first_name', last_name='$last_name', email='$email' WHERE username='$user_logged'");
	}
	else 
		$message = "That email is already in use!<br><br>";
}
else 
	$message = "";
//////////////////////////////////////////////////////

if(isset($_POST['update_password']))
{

	$old_password = strip_tags($_POST['old_password']);
	$new_password1 =strip_tags($_POST['new_password1']);
	$new_password2 = strip_tags($_POST['new_password2']);

	$query = mysqli_query($connect , "SELECT * FROM users WHERE username = '$user_logged'");
	$row = mysqli_fetch_array($query);

	$db_pass =  $row['password'];

	if($db_pass == md5($old_password))
	{

		if($new_password1 == $new_password2)
		{



			if(strlen($new_password1) <= 4) {
				$pass_message = "Sorry, your password must be greater than 4 characters<br><br>";
			}	

			else{
			$new_pass = md5($new_password1);
			$update_pass = mysqli_query($connect , "UPDATE users SET password = '$new_pass' WHERE username = '$user_logged'");
			$pass_message = "Password Changed!";
			}
		}
		else {
			$pass_message = "Your two passwords do not match!<br><br>";

		}

	}
	else {
		$pass_message = "Old password is incorrect<br><br>";
	}




}
else {
	$pass_message = "";
}


if(isset($_POST['close_account']))
{
	header("Location: deactivate.php");
}



?>


<html>
<head>
	<title> Amigo</title>
		<script src = "js/bootstrap.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
		<link rel = "stylesheet" type = "text/css " href = "css/bootstrap.css">
		<link rel = "stylesheet" type = "text/css" href = "css/style.css">
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
	 			<a href= "index.php" ><img id="navb"src = "images/nav/home.png"> </a>
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

		<div class ="shift">

		<div class ="green"> <span class ="head">Account Settings :</span></div><br>
	<?php
	echo "<a href='upload_profile_pic.php'><img src='" . $user['profile_pic'] ."' id='small_profile_pics'></a?";
	?>
	<br><br>
	<a href="upload_profile_pic.php" style = "float: right;margin-top: 150px;margin-right: -180px ; color:#25b2a3">Upload new profile picture</a> 

	

	<?php
	$user_data_query = mysqli_query($connect, "SELECT first_name, last_name, email FROM users WHERE username='$user_logged'");
	$row = mysqli_fetch_array($user_data_query);

	$first_name = $row['first_name'];
	$last_name = $row['last_name'];
	$email = $row['email'];
	?>

	<form action="settings.php" method="POST" id ="settings">
		First Name: <input type="text" name="first_name" value="<?php echo $first_name; ?>" id ="settings_input"><br>
		Last Name: <input type="text" name="last_name" value="<?php echo $last_name; ?>" id ="settings_input"><br>
		Email: <input type="text" name="email" value="<?php echo $email; ?>" id ="settings_input" ><br>

		<?php echo $message; ?>

		<input type="submit" name="update_details" id="save_details" value="Update Details" id = "settings_button" style = "margin-left:0; margin-top:0;"><br><br>
	</form>

	<div class ="green_small" style="width:40%"><span style ="margin-left:10px" class ="head_small">Change Password</span></div><br>
	<form action="settings.php" method="POST" id="settings">
		Old Password: <input type="password" name="old_password" id ="settings_input" ><br>
		New Password: <input type="password" name="new_password1" id ="settings_input"><br>
		New Password Again: <input type="password" name="new_password2" id ="settings_input"><br>

		
		<input type="submit" name="update_password" id="save_details" value="Update Password" id = "settings_button" style = "margin-left:0; margin-top:0;"><br><br>


		<?php echo $pass_message; ?>

	</form>

	<div class ="green_small" style="width:40%"><span style ="margin-left:10px" class ="head_small">Deactivate Account</span></div><br>
	<form action="settings.php" method="POST" id ="settings2">
		<input type="submit" name="close_account" id="close_account" value="Close Account" style="margin-left:0px;">
	</form>

</div>


</div>
</div>