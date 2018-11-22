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

$date = date("Y-m-d H:i:s");

?>



<html>
<head>
	<title> The social network</title>
		<script src = "js/bootstrap.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

		<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">

		<link rel = "stylesheet" type = "text/css " href = "css/bootstrap.css">
		<link rel = "stylesheet" type = "text/css" href = "css/style.css">

<link href="https://fonts.googleapis.com/css?family=Dancing+Script|Fredoka+One|Monoton|Noto+Sans|Open+Sans:300|Pacifico|Poiret+One|Questrial|Quicksand|Slabo+27px|Source+Sans+Pro:200,400" rel="stylesheet"><body style="background-color :#e6e9e7"  >
		<link href="https://fonts.googleapis.com/css?family=Cabin+Sketch|Dancing+Script|Fredoka+One|Londrina+Outline|Monoton|Noto+Sans|Open+Sans:300|Pacifico|Poiret+One|Questrial|Quicksand|Slabo+27px|Source+Sans+Pro:200,400" rel="stylesheet">

		<link href="https://fonts.googleapis.com/css?family=Heebo:500|Montserrat|PT+Sans|Sarala|Satisfy" rel="stylesheet"> 

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
		<div class = "green">
		<span class ="head"> Your Friend Requests :</span></div> <br><br> 

	<?php 
	$query = mysqli_query($connect , "SELECT * FROM  friend_request WHERE user_to ='$user_logged'");

	if(mysqli_num_rows($query) == 0)
	{
	echo '<span class = "no">No friend requests to show </span>';
	}
	else{
		while( $row = mysqli_fetch_array($query))
		{
			$from = $row['user_from'];

			$user_from_obj = new User($connect , $from);
			$profile_pic = $user_from_obj->getProfilePic();
			$name = $user_from_obj->getUSername();
			echo "<div class = postprofilepic > <a href = '$name'><img src = '$profile_pic' width ='60'></a>". "<a href ='$name' id ='poster'>". $user_from_obj->getFirstAndLastName()."</a>" ;
			


		//	$user_from_friend_array = $user_from_obj->getFriendArray();
 
			if(isset($_POST['accept' . $from])){
				//$add_friend = mysqli_query($connect, "UPDATE users SET friend_array = CONCAT(friend_array,'$from,' ) WHERE username = $user_logged");
				//$add_friend = mysqli_query($connect, "UPDATE users SET friend_array = CONCAT(friend_array,'$user_logged,' ) WHERE username = $from");

				$add_friend = mysqli_query($connect , "INSERT INTO friends VALUES ('', '$from', '$user_logged')"); ////////// changes

				$delete_q  = mysqli_query($connect , "DELETE FROM friend_request WHERE user_from = '$from' AND user_to = '$user_logged'");

				//ōō$query = mysqli_query($connect, "INSERT INTO messages VALUES ('', '$user_logged' , '$from', 'You and $from are friends' , '$date', 'no' , 'no' , 'no')") or die ('error');

				echo "You are friends now!";

				header("Location: friend_request.php");
			}

			if(isset($_POST['ignore' . $from])){
				

				$delete_q  = mysqli_query($connect , "DELETE FROM friend_request WHERE user_from = '$from' AND user_to = '$user_logged'");
				echo "You are friends now";

				header("Location: friend_request.php");
				}



				?>

				<form action ="friend_request.php" method = "POST" id ="acc">
				<input type = "submit" name = "accept<?php echo $from; ?>" id= "accept_button" value ="Accept">
				</form>

				<form action ="friend_request.php" method = "POST" id ="ign">
				<input type = "submit" name = "ignore<?php echo $from; ?>" id= "ignore_button" value ="Ignore">
				</form>

				<?php
			}

			


			
	}
?>
	</div>

</div>

