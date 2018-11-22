<?php

$fname = "";
$lname = "";
$em = "";
$pass = "" ;
$pass2 = "";
$date = "";
$username = "";
$error_array = array();

if(isset($_POST['register_button']))
{
	$fname = strip_tags($_POST['user_fname']);
	$fname = str_replace(' ', '', $fname);
	$fname = ucfirst(strtolower($fname));
	$_SESSION['user_fname'] = $fname ;

	$lname = strip_tags($_POST['user_lname']);
	$lname = str_replace(' ', '', $lname);
	$lname = ucfirst(strtolower($lname));
	$_SESSION['user_lname'] = $lname ;

	$em = strip_tags($_POST['user_email']);
	$em = str_replace(' ', '', $em);
	//$em = ucfirst(strtolower($em));
	$_SESSION['user_email'] = $em ;

	$username = strip_tags($_POST['user_name']);
	$_SESSION['user_name'] = $username ;

	$pass = strip_tags($_POST['user_pass']);
	$_SESSION['user_pass'] = $pass;

	$pass2 = strip_tags($_POST['user_pass2']);
	$_SESSION['user_pass2'] = $pass2 ;


	$date = date("Y-m-d");

	if(filter_var($em, FILTER_VALIDATE_EMAIL))
	{
		$em = filter_var($em, FILTER_VALIDATE_EMAIL);
		$email_check = mysqli_query($connect , "SELECT email FROM users WHERE email = '$em'");

		$num_rows = mysqli_num_rows($email_check);

		if($num_rows > 0) {
			array_push($error_array,"Email Already exist<br>");
		}



	}
	else {
		array_push($error_array,"Invalid Email format<br>");
	} // email check


	if (strlen($fname > 25))
	{
		array_push($error_array, "Your First Name should be less than 25 characters!<br>");
	}


	if (strlen($lname > 25))
	{
		array_push($error_array,"Your Last Name should be less than 25 characters!<br>");
	}


		$user_check = mysqli_query($connect , "SELECT username FROM users WHERE username = '$username'");

		$num_users = mysqli_num_rows($user_check);

		if($num_users > 0) {
			array_push($error_array,"Username Already Exists<br>");
		}


	if($pass != $pass2) //password test
	{
			array_push($error_array, "Your Passwords do not match<br>");
	}
	else 
	{
		if(preg_match('/[^A-Za-z0-9]/', $pass));
	}


	if(strlen($pass) <5)
	{
		array_push($error_array, "Your Password is too weak<br>");
	}


	if(strlen($pass) >30)
		{
			array_push($error_array, "Your password length should be less than 30<br>");
		}


	if(empty($error_array))	
	{
		$pass = md5($pass);

	
	$profile_pic = "images/default_profile.png";

	$query = mysqli_query($connect, "INSERT INTO users VALUES('','$fname', '$lname','$username','$em','$pass','$date','$profile_pic','0','0','no')") or die('sad') ;
	
	array_push($error_array, "<span style='color: #14C800;'>You're all set! Go ahead and login!</span><br>");



	$_SESSION['user_fname'] = "";
	$_SESSION['user_lname'] = "";
	$_SESSION['user_email'] = "";
	$_SESSION['user_name'] = "";
	//$_SESSION['user_fname'] = "";
	//$_SESSION['user_fname'] = "";
	}
}
?>