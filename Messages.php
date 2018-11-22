

<?php
require 'connection.php';
include("user.php");
include("Message.php");

$body="blHH";

if(isset($_SESSION['username']))
{
	$user_logged = $_SESSION['username'];
	$user_details = mysqli_query($connect, "SELECT * FROM users WHERE username = '$user_logged'");
	$user = mysqli_fetch_array($user_details);

}
else{
	header("Location: register.php");
}

	$message_obj = new Message($connect , $user_logged);
	if(isset($_GET['u'])){
		$user_to = $_GET['u'];
		$user_to_obj = new User($connect , $user_to);
		$user_to_details = mysqli_query($connect, "SELECT * FROM users WHERE username = '$user_to'");
		$user_to_array = mysqli_fetch_array($user_to_details);
	}
	else{
		$user_to = $message_obj->getMostRecent();
		$user_to_obj = new User($connect , $user_to);
		if($user_to == false)
			$user_to = 'new';
	

	$user_to_details = mysqli_query($connect, "SELECT * FROM users WHERE username = '$user_to'");
	$user_to_array = mysqli_fetch_array($user_to_details);
	}
	
	

if(isset($_POST['friends']))
{
	header("Location: friends.php?u=$user_logged");

}
		//echo "haaan<br>";

	//if(isset($_POST['ridam']))
	/*if(isset($_POST['message_post']))
	{
		echo "First loop hmm";
		if(isset($_POST['message_body']))
		{
			$body= mysqli_real_escape_string($connect, $_POST['message_body']);
			$date=date("Y-m-d H:i:s");
			echo "Message is being sent!";
			$message_obj->sendMessage($user_to, $body, $date);

		}
	}
	echo "ökaa";*/
  //done till here
?>


<html>
<head>
	<title> Amigo</title>
		<script src = "js/bootstrap.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

		<link rel = "stylesheet" type = "text/css" href = "css/style.css">
		<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">

		<link rel = "stylesheet" type = "text/css " href = "css/bootstrap.css">
		<link rel = "stylesheet" type = "text/css" href = "css/style.css">
				<link href="https://fonts.googleapis.com/css?family=Heebo:500|Montserrat|PT+Sans|Sarala|Satisfy" rel="stylesheet"> 

<link href="https://fonts.googleapis.com/css?family=Cabin+Sketch|Dancing+Script|Fredoka+One|Londrina+Outline|Monoton|Noto+Sans|Open+Sans:300|Pacifico|Poiret+One|Questrial|Quicksand|Slabo+27px|Source+Sans+Pro:200,400" rel="stylesheet"><body style="background-color :#e6e9e7"  >

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


<div class="feed_m column">

	<?php
	$notFriend = "";
		if($user_to != "new")
		{
			
			echo "<div class ='green_small'> <span class='head_small'>You and <a href='$user_to'>". $user_to_obj->getFirstAndLastName(). "</a></span></div><hr>";
			//I MADE A CHAGE HER
			
				if($user_to_obj->isFriend($user_logged) ){

				echo "<div class='messages' id='scroll'>";
				echo $message_obj->getMessages($user_to);
				echo "</div>";
				}
				else{
					echo "<span style='color:#57a59d'><h4> It seems you are no longer Friends!</h4></span>";
				}
			
			
		}
		else
		{
			echo "<h4> New Message</h4>";
		}
	?>
	
	<div class="message_post" >
	<form action="" method="POST" id = "mess_sub">
	<?php 
		/*if($user_to=="new")
		{
			echo "Select the friend you like to message<br>";
			?>
			To: <input type='text' onkeyup='getUsers(this.value, "<?php echo $user_logged; ?>")' name='q' placeholder='Name' autocomplete='off' id='search_text_input'>;
			<?php
			echo "<div class='results'></div> ";
		}*/
		//else

		//{
		$user_to_obj = new User($connect , $user_to);
		if($user_to_obj->isFriend($user_logged) && ($user_to_array['user_closed'])=="no")  {
			echo "<br> <input type='text' name='message_body' id='message_textarea'  placeholder='Write your message...'></input> ";
			echo "<input type='submit' name='message_post' class='info' id='message_submit' value='Send'> ";
		}
		if(($user_to_array['user_closed'])=="yes")
		{
			echo "<br><span style='color:#57a59d'><h4> This user Deactivated!</h4></span> ";
		}
			if(isset($_POST['message_post']))
			{
				
				if(isset($_POST['message_body']))
				{
					$body= mysqli_real_escape_string($connect, $_POST['message_body']);
					$date=date("Y-m-d H:i:s");
					//echo "Message is being sent!";
					$message_obj->sendMessage($user_to, $body, $date);

				}
			}
		//}
	?>

	</form>
	</div>
</div>
	<div class="user_details_mess column" id="conversations" >
		<div class="green_small"><span class = "head_small" style="margin-left:3%;"> Recent Conversations</span></div>
		<div class="loaded_messages">
			<?php
				echo $message_obj->getConvos();
			?>			
		</div>

		<div class = "choose">
					<form action ="Messages.php" method = "POST" id = "choose_friends">

						<input type = "submit" name ="friends"  value ="Choose from friend list">

 					</form>
		</div>

		<br>
		
	</div>


		<script>


     var objDiv = document.getElementById("scroll");
     objDiv.scrollTop = objDiv.scrollHeight;


	</script>
	


	<style type="text/css">
		.message
		{
			border: 1px solid #000;
			border-radius: 5px;
			padding: 5px;
			display: inline-block;
			color: #25120e;


		}
		.portion{
			min-wid:100%;
			
			
		}
		#blue
		{
			background-color: #3bc0afcc;
		    border-color: #73d6ce;
		    float: right;
		    margin-bottom: 5px;
		    color: white;
		    margin-right:8px;
		    max-width:97%;
		    height:auto;
		}
		#green
		{
		    background-color: #dae3e2;
    		border-color: #d4dcda;
		    float: left;
		    margin-bottom: 5px;
		    max-width:95%;
		    height:auto;

		}	

		#message_col{
		text-decoration:none;


}
		#message_col a:hover{

		background-color: grey;
		}
		

	}	
	</style>

