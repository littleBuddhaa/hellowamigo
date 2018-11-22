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


if(isset($_GET['u']))
{
	$profile_of = $_GET['u'];
	$user_obj = new User($connect,$profile_of);
	$name = $user_obj->getFirstAndLastName();
	



}


?>



<html>
<head>
	<title> Amigo</title>
		<script src = "js/bootstrap.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

		<link rel = "stylesheet" type = "text/css " href = "css/bootstrap.css">
		<link rel = "stylesheet" type = "text/css" href = "css/style.css">

				<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Muli|Raleway|Ubuntu" rel="stylesheet">
				<link href="https://fonts.googleapis.com/css?family=Heebo:500|Montserrat|PT+Sans|Sarala|Satisfy" rel="stylesheet"> 

				<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Muli|Raleway|Ubuntu" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Heebo:500|Montserrat|PT+Sans|Sarala|Satisfy" rel="stylesheet"> 
		<link href="https://fonts.googleapis.com/css?family=Heebo:500|Montserrat|PT+Sans|Sarala|Satisfy" rel="stylesheet"> 

<link href="https://fonts.googleapis.com/css?family=Jua" rel="stylesheet"> 
<link href="https://fonts.googleapis.com/css?family=Cabin+Sketch|Dancing+Script|Fredoka+One|Londrina+Outline|Monoton|Noto+Sans|Open+Sans:300|Pacifico|Poiret+One|Questrial|Quicksand|Slabo+27px|Source+Sans+Pro:200,400" rel="stylesheet"><body style="background-color :#e6e9e7"  >
		<link href="https://fonts.googleapis.com/css?family=Cabin+Sketch|Dancing+Script|Fredoka+One|Londrina+Outline|Monoton|Noto+Sans|Open+Sans:300|Pacifico|Poiret+One|Questrial|Quicksand|Slabo+27px|Source+Sans+Pro:200,400" rel="stylesheet">


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
	 			<a href= "index.php" ><img id="navb" src = "images/nav/home.png"> </a>
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

		<div class = "green">
		<span class ="head"> <?php echo $name?>'s Friends :</span> </div> <br><br> 
			<?php


			$query = mysqli_query($connect, "SELECT * FROM friends WHERE user_to = '$profile_of' ");
			while($row= mysqli_fetch_array($query))
			{
			$friend = $row['user_from'];

			$user_from_obj = new User($connect , $friend);
			$profile_pic = $user_from_obj->getProfilePic();
			 $friend = $user_from_obj->getUsername();
			$_SESSION['friend'] = $friend;
			$put_button = "";


				$user_logged_in = new User($connect , $user_logged);
					if($user_logged != $friend)
					{
						if($user_logged_in->isFriend($friend))
						{
								//remove friend
							$put_button= "<input type='submit' name='send$friend'  value ='Send Message'<br>"; 

						}

						else if($user_logged_in->receive_request($friend) ) //respond
						{
							$put_button= "<input type='submit' name='respond_request$friend' value ='Respond to Request'<br>"; 
								// add friend
						}
						else if($user_logged_in->sent_request($friend) ) //
						{
							$put_button="<input type='submit' name='' class='disable' value ='Request Sent'<br>"; 
								
						}
 						
						else {
							 $put_button= "<input type='submit' name='add_friend$friend'  value='Add Friend'><br>";
							 
						}

					}
			
			echo "<div class = postprofilepic > <a href ='$friend'><img src = '$profile_pic' width ='60' style='margin-left:20px'> </a>"."  ". "<a href = '$friend' id ='poster'>" .$user_from_obj->getFirstAndLastName()."</a>" ;?><br> <?php

			echo  "<form action ='friends.php?u=$profile_of' method = 'POST' id ='send_message'>". $put_button ."</form>";

//////buttonsssssssssssssssssssssssssssss--------------------------------------------------
						if(isset($_POST['add_friend' . $friend]))
						{
							//$var = $_SESSION['friend'];
							$user = new User($connect , $user_logged);
							$user->send_request($friend);
							//$profile_of = $_SESSION['profile_of'];
							header("Location: friends.php?u=$profile_of");
						}
						if(isset($_POST['remove_friend' . $friend]))
						{
							//$var = $_SESSION['friend'];
							$user = new User($connect , $user_logged);
							$user->remove_friend($friend);
						}
						if(isset($_POST['respond_request']))
						{
						header("Location: friend_request.php");
						}
						if(isset($_POST['send' . $friend]))
						{
							//$var = $GLOBALS['friend'];
							header("Location: Messages.php?u=$friend");
						}


			
			?>    <br><br>


			<?php }?> 
			
			
			<?php
			$query = mysqli_query($connect, "SELECT * FROM friends WHERE user_from = '$profile_of' ");

			while($row= mysqli_fetch_array($query)){

			
			$friend = $row['user_to'];

			$user_from_obj = new User($connect , $friend);
			$profile_pic = $user_from_obj->getProfilePic();
			 $friend = $user_from_obj->getUsername();
		//	$_SESSION['friend'] = $friend;
			$put_button = "";
			//echo $_SESSION['friend'];

			$user_logged_in = new User($connect , $user_logged);
					if($user_logged != $friend)
					{
						if($user_logged_in->isFriend($friend))
						{
								//remove friend
							$put_button= "<input type='submit' name='send$friend'  value ='Send Message'<br>"; 

						}

						else if($user_logged_in->receive_request($friend) ) //respond
						{
							$put_button= "<input type='submit' name='respond_request$friend' value ='Respond to Request'<br>"; 
								// add friend
						}
						else if($user_logged_in->sent_request($friend) ) //
						{
							$put_button="<input type='submit' name='' class='disable' value ='Request Sent'<br>"; 
								
						}
 						
						else {
							 $put_button= "<input type='submit' name='add_friend$friend'  value='Add Friend'><br>";
							 
						}

					}


			echo "<div class = postprofilepic > <a href ='$friend'><img src = '$profile_pic' width ='60' style='margin-left:20px'> </a>"."  ". "<a href = '$friend' id ='poster'>" .$user_from_obj->getFirstAndLastName()."</a>"  ;?> <br><?php

			echo  "<form action ='friends.php?u=$profile_of' method = 'POST' id ='send_message'>". $put_button ."</form>";


						if(isset($_POST['add_friend' . $friend]))
						{
							//$var = $_SESSION['friend'];
							$user = new User($connect , $user_logged);
							$user->send_request($friend);
							//$profile_of = $_SESSION['profile_of'];
							header("Location: friends.php?u=$profile_of");
						}
						if(isset($_POST['remove_friend' . $friend]))
						{
							//$var = $_SESSION['friend'];
							$user = new User($connect , $user_logged);
							$user->remove_friend($friend);
						}
						if(isset($_POST['respond_request']))
						{
						header("Location: friend_request.php");
						}
						if(isset($_POST['send' . $friend]))
						{
							//$var = $GLOBALS['friend'];
							header("Location: Messages.php?u=$friend");
						}
	
			?>    <br><br>




			<?php }?> 


</div>

</div>