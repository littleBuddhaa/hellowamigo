<?php
require 'connection.php';
include("user.php");
include("posts.php");




if(isset($_SESSION['username']))
{
	$user_logged = $_SESSION['username'];
	$user_details = mysqli_query($connect, "SELECT * FROM users WHERE username = '$user_logged'");
	$users = mysqli_fetch_array($user_details);

}
else{
	header("Location: register.php");
}



if(isset($_GET['profile_username']))
{
	$username = $_GET['profile_username'];
	$user_details_query = mysqli_query($connect , "SELECT * FROM users WHERE username = '$username'");
	$user_details = mysqli_fetch_array($user_details_query);


	$user_friend_query = mysqli_query($connect , "SELECT * FROM friends WHERE user_from='$username' OR user_to = '$username'");
	$user_friend = mysqli_num_rows($user_friend_query);

	//$num_friends = (substr_count($user_details['friend_array'], ",")) -1;

	$user = new User($connect , $user_logged);
	$mutual = 0;// for now, has to be eddited 
	if($user_details['user_closed'] =="yes")
	{
		header("Location: user_deactivated.php");
	}

	//if($user->isClosed())
	//{
//		header("Location: user_deactivated.php");
//	}


}


if(isset($_POST['remove_friend']))
{
	$user = new User($connect , $user_logged);
	$user->remove_friend($username);
}

if(isset($_POST['add_friend']))
{
	$user = new User($connect , $user_logged);
	$user->send_request($username);
}

if(isset($_POST['respond_request']))
{
header("Location: friend_request.php");
}

if(isset($_POST['friends']))
{
	header("Location: friends.php?u=$username");

}

?>

<html>
<head>
	<title> Amigo</title>
		<!--<script src = "js/bootstrap.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>-->

		<link rel = "stylesheet" type = "text/css " href = "css/bootstrap.css">
		<link rel = "stylesheet" type = "text/css" href = "css/style.css">
		<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">

			<link rel = "stylesheet" type = "text/css " href = "css/bootstrap.css">
		<link rel = "stylesheet" type = "text/css" href = "css/style.css">
		<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Muli|Raleway|Ubuntu" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Muli|Raleway|Ubuntu" rel="stylesheet">

		<link href="https://fonts.googleapis.com/css?family=Heebo:500|Montserrat|PT+Sans|Sarala|Satisfy" rel="stylesheet"> 

		<link href="https://fonts.googleapis.com/css?family=Dancing+Script|Noto+Sans|Open+Sans:300|Pacifico|Questrial|Quicksand|Slabo+27px|Source+Sans+Pro:200,400" rel="stylesheet">
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
	 			<a href= "<?php echo $user_logged; ?>" ><img src = "<?php echo $users['profile_pic']; ?>" style="border-radius:100px;    border: 1px solid #e6e9e7;width: 38px;
margin-top: 0px;"></a>
	 			<a href= "index.php" ><img id="navb"src = "images/nav/home.png"> </a>
	 			<a href= "friend_request.php" ><img id ="navb" src = "images/nav/add_friend.png"> </a>
	 			<a href= "Messages.php" ><img id = "navb" src = "images/nav/messages.png"> </a>
	 			
	 			<a href= "settings.php" > <img id="navb" src = "images/nav/settings.png"></a>
	 			<a href= "forms/logout.php" ><img id="navb" src = "images/nav/log_out.png"></a>
	 		</div>

	</div> 

	
	<div class = "wrapper">
			<div class = "userColumn profile_column">

				<img src = "<?php echo $user_details['profile_pic']; ?>" style="border-radius:100px;    border: 3px solid #e6e9e7; margin-left: 8%">

			
				<div class = "user_info">
					<span id = "profile_details"> <?php echo "Posts: " . " ". $user_details['num_posts'] ;?></span><br>
					
					<apan id = "profile_details"> <?php echo "Friends:  " . $user_friend;?> </span>
					
					<!--<p><?php echo "Mutual Friends " . $mutual;?> </p> -->



				</div>



					<form action ="<?php echo $username; ?>" method = "POST" id = "friend_b">

						<input type = "submit" name ="friends"  value ="Friends">

 					</form>

				<form action = "<?php echo $username; ?>" id = "friend_c" method = "POST">
						<?php
							$user_obj = new User($connect , $username);
							if($user_obj->isClosed())
							{
								header("Location: user_deactivated.php");
							}
						?>


					
					<?php
					$user_logged_in = new User($connect , $user_logged);
					if($user_logged != $username)
					{
						if($user_logged_in->isFriend($username))
						{
								//remove friend
							echo '<input type="submit" name="remove_friend" style="background-color:#E15E3A" value ="Remove Friend"<br>'; 

						}

						else if($user_logged_in->receive_request($username) ) //respond
						{
							echo '<input type="submit" name="respond_request" value ="Respond to Request"<br>'; 
								// add friend
						}
						else if($user_logged_in->sent_request($username) ) //
						{
							echo '<input type="submit" name="" class="default" value ="Request Sent"<br>'; 
								// add friend
						}
 						
						else {
							 echo '<input type="submit" name="add_friend" class="success" value="Add Friend"><br>';
							 
						}

					}
					
					
					

					?>
					<br><br>
					
					</form>



			</div>


<div class = "feed column">
<div class = "mypage"> <?php echo $user_details['first_name'] . " " . $user_details['last_name']?></div> 
</div>


			<div class = "feed column">
				

				<?php 
				if(isset($_GET['profile_username'])){
					$username = $_GET['profile_username'];


				}

				$ppost = new Posts($connect , $username);
				$ppost->displayProfilePosts();

				 ?>
				 <br><br>
				<span class ="no"> No more posts to show :) </span>

			</div>


				









	</div> 
