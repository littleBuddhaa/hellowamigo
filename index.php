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


if(isset($_POST['post'])){
	$uploadOk = 1;
	$imageName = $_FILES['upload_file']['name'];
	$errorMessage = "";
	
	if($imageName != "" )
	{
		$targetDir= "images/posted_images/";
		$imageName = $targetDir . uniqid() . basename($imageName); //images/posted_images
		$imageFileType = pathinfo($imageName, PATHINFO_EXTENSION);
		if($_FILES['upload_file']['size'] >10000000000000)
		{
			$errorMessage = "File is too large!";
			$uploadOk = 0;
		}
		if(strtolower($imageFileType) !="jpeg" && strtolower($imageFileType) !="jpg" && strtolower($imageFileType) !="png"  ){
			$errorMessage = "Only .jpg, .jpeg , .png supported";
			$uploadOk = 0;
		}

		if($uploadOk)
		{
			if(move_uploaded_file($_FILES['upload_file']['tmp_name'],$imageName))
			{

			}
			else  $uploadOk = 0;

		}
	
	}
	//with image 
			if($uploadOk){
			$post = new Posts($connect, $user_logged);
			$post->submit_post($_POST['post_text'], $imageName);
			}
			else{
				echo "<div class = 'alert alert-danger alert-dismissible fade in' style='text-align:center'> <a href='index.php' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Error Uploading file! We only support .jpeg .jpg and .png files or the file is too big </div>";

			}
	//no image 
//	$post = new Posts($connect, $user_logged);
//	$post->submit_post($_POST['post_text'],"none");
	
	}

////////////////////////////////////////////////////////////////////////////////////////

?>

<html>
<head>
	<title>Amigo</title>
		<script src = "js/bootstrap.js"></script>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src = "js/demo.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<link rel = "stylesheet" type = "text/css " href = "css/bootstrap.css">
		<link rel = "stylesheet" type = "text/css" href = "css/style.css">
		<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Muli|Raleway|Ubuntu" rel="stylesheet">

		<link href="https://fonts.googleapis.com/css?family=Heebo:500|Montserrat|PT+Sans|Sarala|Satisfy" rel="stylesheet"> 
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

	 			<a href= "<?php echo $user_logged;?>"><img src = "<?php echo $user['profile_pic']; ?>" style="border-radius:100px;    border: 1px solid #e6e9e7;width: 38px;
				margin-top: 0px;"></a>
	 			<a href= "index.php" ><img id = "navb"src = "images/nav/home.png"> </a>
	 			<a href= "friend_request.php" ><img id ="navb" src = "images/nav/add_friend.png"> </a>
	 			<a href= "Messages.php" ><img id = "navb" src = "images/nav/messages.png"> </a>
	 			
	 			<a href= "settings.php" > <img id="navb" src = "images/nav/settings.png"></a>
	 			<a href= "forms/logout.php" ><img id="navb" src = "images/nav/log_out.png"></a>
	 		</div>

	</div> 

<div class ="wrapper">		

	<div class ="column user_details">
		
		<a href ="<?php echo $user_logged;?>"> <img src= "<?php echo $user['profile_pic']; ?>"  style="margin-left: 36;"></a>
	

<br><br>
		<?php echo "<a style='text-decoration:none' href ='$user_logged'><div class ='green_small'> <span id='head_small2' style='margin-left:23%'>" .$user['first_name'] . " " . $user['last_name'] ."</span></div></a>" ;?>
	</div>
	<div class ="pattern">
		<img src = "dots.png" height="880vw" width="auto">
	</div>
	<div class ="feed column">
		<form class ="post_form" action ="index.php" method = "POST" enctype="multipart/form-data"> 
			<input type ="file" id = "upload_file" name ="upload_file" > 
			<textarea name = "post_text"  id = "post_text" placeholder ="What is on your mind? "  ></textarea>
				<input type = "submit"  name = "post" id="post_button" class="post_button" value ="Post" > </form>


				<?php

	$post = new Posts($connect, $user_logged);
	$post->displayPosts();
	?>

					 <br><br>
				<span class ="no"> No more posts to show :) </span>

	</div>
	
	




</div>	
</body>
</html>