<html>
<head>
	<title>

		<link rel = "stylesheet" type = "text/css" href = "css/style.css">
		<link href="https://fonts.googleapis.com/css?family=Noto+Sans|Open+Sans:300|Slabo+27px|Source+Sans+Pro:200,400" rel="stylesheet">
	</title>
</head>
<body>


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


if(isset($_GET['post_id'])) //getting post id
{
	$post_id = $_GET['post_id'];

}


$get_likes = mysqli_query($connect, "SELECT likes ,post_by FROM  posts WHERE  id = '$post_id'");
$row = mysqli_fetch_array($get_likes);

$total_likes = $row['likes'];
$post_by = $row['post_by'];


//

if(isset($_POST['like_button']))
{
	$total_likes++;

	$query = mysqli_query($connect , "UPDATE posts SET likes='$total_likes' WHERE id = '$post_id'");
	$likes_query = mysqli_query($connect , "INSERT INTO post_likes VALUES('', '$user_logged' , '$post_id')") ;
}


///


if(isset($_POST['unlike_button']))
{
	$total_likes--;

	$query = mysqli_query($connect , "UPDATE posts SET likes='$total_likes' WHERE id = '$post_id'");
	$likes_query = mysqli_query($connect , "DELETE FROM post_likes WHERE liked_by = '$user_logged' AND post_id = '$post_id'");

}

	$check_query = mysqli_query($connect, "SELECT id FROM post_likes WHERE liked_by='$user_logged' AND post_id='$post_id'") or die('error hai!');

	
	$num_rows = mysqli_num_rows($check_query);

	if($num_rows > 0) {

	echo '<form action ="likes.php?post_id=' . $post_id . '" method = "POST" id ="like_heart">

	<input  type= "submit"  name ="unlike_button"  value ="" style="background:url(images/icons/liked.png) no-repeat ;border: none; cursor:pointer;
height: 45px;
width: 50px;
margin: -10px;">

		
		<div style ="    color: #42c3ac;
    font-family: arial;
    margin-left: 45px;
    position: absolute;
    margin-top: -18px;">
		'. $total_likes .' Likes
		</div>
		</form>
		';
}
else {
	echo '<form  action ="likes.php?post_id=' . $post_id . '" method = "POST" id = "unlike_heart">
		<input  type= "submit"  name ="like_button"  value ="" style="background:url(images/icons/unliked.png) no-repeat; border: none;cursor:pointer;
height: 45px;
width: 50px;
margin: -10px;"> 


		
		<div style ="    color: #42c3ac;
    font-family: arial;
    margin-left: 45px;
    position: absolute;
    margin-top: -18px;">

		'. $total_likes .' Likes
		</div>
		</form>
		';
}

//<input type = "submit" class = "comment_unlike" name ="unlike_button" value ="Unlike">
//<input type = "submit" class = "comment_like" name ="like_button" value ="like">
?>
</body>