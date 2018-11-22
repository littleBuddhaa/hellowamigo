

<?php

require 'connection.php';

require 'forms/register_form.php';
require 'forms/login_form.php';


?>


<html>
<head>
	<title>Amigo</title>
	<link rel = "stylesheet" type ="text/css" href = "css/register.css">
	<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	
</head>
<body>

<div class = "wrapper">


	<div class = "heading">
		Amigo.
	</div>
	<div class= "login_window">
		<div class="login_header">
			<h1>Welcome to AMIGO</h1>
			Login or sign up below!
		</div>
        
        <div >
		<form action="register.php" method="POST">
		<input type="email" name="login_email" placeholder="Email Address" value="<?php 
		if(isset($_SESSION['log_email'])) {
			echo $_SESSION['log_email'];
		} 
		?>" required>
		<br>
		<input type="password" name="login_password" placeholder="Password">
		<br>
		<?php if(in_array("Email or password was incorrect<br>", $error_array)) echo  "Email or password was incorrect<br>"; ?>
		<input type="submit" name="login_button" value="Login">
		<br>
         
         </form>
     </div>
		

	
	<div >

	<form action ="register.php" method= "POST">
		<input type = "text" name = "user_fname" placeholder = "First name" value = "<?php
		if(isset($_SESSION['user_fname'])){
		echo $_SESSION['user_fname'];

		}?>" required >
		<br>
		<?php if(in_array("Your First Name should be less than 25 characters!<br>", $error_array)) echo "Your First Name should be less than 25 characters!<br>"; ?>


		<input type = "text" name = "user_lname" placeholder = "Last name"  value = "<?php
		if(isset($_SESSION['user_lname'])){
		echo $_SESSION['user_lname'];

		}?>"required >
		<br>
		<?php if(in_array("Your Last Name should be less than 25 characters!<br>", $error_array)) echo "Your Last Name should be less than 25 characters!<br>"; ?>

		<input type = "email" name = "user_email" placeholder = "Email"  value = "<?php
		if(isset($_SESSION['user_email'])){
		echo $_SESSION['user_email'];


		}?>"required >
		<br>
		<?php if(in_array("Email Already exist<br>", $error_array)) echo "Email Already exist<br>";
		else if(in_array("Invalid Email format<br>", $error_array)) echo "Invalid Email format<br>" ;?>


		<input type = "text" name = "user_name" placeholder = "Username"    value = "<?php 
		if(isset($_SESSION['user_name'])) {
			echo $_SESSION['user_name'];

		}?>" required>
		<br>
		<?php if(in_array("Username Already Exists<br>", $error_array)) echo "Username already Exists<br>"; ?>


		<input type = "password" name = "user_pass" placeholder = "Password"  required >
		<br>

		<input type = "password" name = "user_pass2" placeholder = "Confirm Password"  required >
		<br>

		<?php if(in_array("Your Passwords do not match<br>", $error_array)) echo "Your Passwords do not match<br>" ;
		 if(in_array("Your Password is too weak<br>", $error_array)) echo "Your Password is too weak<br>" ;
		  if(in_array("Your password length should be less than 30<br>", $error_array)) echo "Your password length should be less than 30<br>" ; ?>

		  <br>
		  <?php if(in_array("<span style='color: #14C800;'>You're all set! Go ahead and login!</span><br>", $error_array)) echo "<span style='color: #14C800;'>You're all set! Go ahead and login!</span><br>"; ?>
		<input type = "submit" name = "register_button" value = "Register">
             <br>
            
			</form>
		</div>

		</div>
</div>	
</body>
</html>

