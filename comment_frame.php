<html>
<head>
	<title>

	</title>
	<link rel = "stylesheet" type = "text/css" href = "css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Muli|Raleway|Ubuntu" rel="stylesheet">

		<link href="https://fonts.googleapis.com/css?family=Heebo:500|Montserrat|PT+Sans|Sarala|Satisfy" rel="stylesheet"> 
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
?>

	<script>
		function toggle() {
			var element = document.getElementById("comment_section");

			if(element.style.display == "block") 
				element.style.display = "none";
			else 
				element.style.display = "block";
		}
	</script>

<?php
if(isset($_GET['post_id'])) //getting post id
{
	$post_id = $_GET['post_id'];

}
$comment_query = mysqli_query($connect, "SELECT post_by  FROM posts WHERE id = '$post_id'");

$row = mysqli_fetch_array($comment_query);
$posted_to = $row['post_by'];

if(isset($_POST['postComment' . $post_id])){
$post_body =  $_POST['post_body'];
$post_body = mysqli_escape_string($connect, $post_body);
$date_time = date("Y-m-d H:i:s");

$comment_query = mysqli_query($connect , "INSERT INTO posts_comments VALUES ('', '$posted_to' , '$user_logged' , '$post_body' , '0' , '$date_time' , 'no' , '$post_id')");
//echo "comment posted!";
}


?>

	<form action="comment_frame.php?post_id=<?php echo $post_id; ?>" id="comment_form" name="postComment<?php echo $post_id; ?>" method="POST">
		<textarea name="post_body"></textarea>
		<input type="submit" name="postComment<?php echo $post_id; ?>" value="Comment">
	</form>



	<?php  
	$get_comments = mysqli_query($connect, "SELECT * FROM posts_comments WHERE post_id='$post_id' ORDER BY id DESC");
	$count = mysqli_num_rows($get_comments);

	if($count != 0) {

		while($comment = mysqli_fetch_array($get_comments)) {

			$comment_body = $comment['comment_body'];
			$is_empty = preg_replace('/\s+/' , '' ,$comment_body);
					if($is_empty != ""){

					$posted_to = $comment['comment_on'];

					$posted_by = $comment['comment_by'];
					$date_added = $comment['comment_date'];
					$removed = $comment['comment_removed'];

					//Timeframe
					$date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_added); //Time of post
					$end_date = new DateTime($date_time_now); //Current time
					$interval = $start_date->diff($end_date); //Difference between dates 
					if($interval->y >= 1) {
						if($interval == 1)
							$time_message = $interval->y . " year ago"; //1 year ago
						else 
							$time_message = $interval->y . " years ago"; //1+ year ago
					}
					else if ($interval->m >= 1) {
						if($interval->d == 0) {
							$days = " ago";
						}
						else if($interval->d == 1) {
							$days = $interval->d . " day ago";
						}
						else {
							$days = $interval->d . " days ago";
						}


						if($interval->m == 1) {
							$time_message = $interval->m . " month ". $days;
						}
						else {
							$time_message = $interval->m . " months ". $days;
						}

					}
					else if($interval->d >= 1) {
						if($interval->d == 1) {
							$time_message = "Yesterday";
						}
						else {
							$time_message = $interval->d . " days ago";
						}
					}
					else if($interval->h >= 1) {
						if($interval->h == 1) {
							$time_message = $interval->h . " hour ago";
						}
						else {
							$time_message = $interval->h . " hours ago";
						}
					}
					else if($interval->i >= 1) {
						if($interval->i == 1) {
							$time_message = $interval->i . " minute ago";
						}
						else {
							$time_message = $interval->i . " minutes ago";
						}
					}
					else {
						if($interval->s < 30) {
							$time_message = "Just now";
						}
						else {
							$time_message = $interval->s . " seconds ago";
						}
					}

					$user_obj = new User($connect, $posted_by);
					$name = $user_obj->getFirstAndLastName();

					?>
					<div class="comment_section">
						<a href="<?php echo $posted_by?>" target="_parent"><img src="<?php echo $user_obj->getProfilePic();?>" title="<?php echo $posted_by; ?>" style="float:left ;padding :5px;" height="35" ;></a>&nbsp;&nbsp;&nbsp;
						<a class = "commenter" href="<?php echo $posted_by?>" target="_parent" >  <?php echo $name  ?> </a>
						<?php echo "<span id ='timec'>$time_message</span>" . "<br>" . "<span id = 'comment_body'>$comment_body</span>" ?> 
						<hr size="0.1px" color ="white" noshade>
					</div>
					<?php
			}
			

		}
	}


	?>


</body>
</html>