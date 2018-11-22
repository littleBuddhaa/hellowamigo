<?php 

class Posts {

	private $connection;
	private $user_object ;
	//private $str ="";
	public function __construct($connection, $user)
	{
		$this->connection = $connection;
		//$user_details = mysqli_query($connection,"SELECT * FROM users WHERE username = '$user'");
		$this->user_object = new User($connection , $user);


	}
	public function submit_post($body, $imageName){
		//$body = strip_tags($body);
		$body = mysqli_real_escape_string($this->connection, $body );
		$is_empty = preg_replace('/\s+/' , '' ,$body) ; // deletes all blank space

		if($is_empty != "")
		{

			
		
			
			$body_array = preg_split('/\s+/',$body) ;
			foreach ($body_array as $key => $value) {
				if(strpos($value, "www.youtube.com/watch?v=") !== false)
				{
					$value = preg_replace("!watch\?v=!", "embed/", $value);
					$value = "<br><iframe width=\'420\' height=\'315\' src=\'".$value."\'></iframe><br>";
					$body_array[$key] = $value;
				}
				# code...
			}

			$body= implode(" ", $body_array);
			$date = date("Y-m-d H:i:s");

		

		$added_by = $this->user_object->getUsername();

		 $query = mysqli_query($this->connection , "INSERT INTO posts VALUES('','$added_by', '$body','$date', '0','$imageName', 'no')") or die('couldnt post');

		 $returned_id = mysqli_insert_id($this->connection);

		 $num_posts = $this->user_object->getNumPosts();

		 $num_posts++;
		 $query = mysqli_query($this->connection , "UPDATE users SET num_posts = '$num_posts' WHERE username ='$added_by'");

		}
			
	}
	public function displayPosts(){


	//	$page = $data['page'];
		$user_logged = $this->user_object->getUsername();

	//	if($page ==1)
	//		$start   = 0;
	//	else 
	//		$start = ($page -1) * $limit;

		$str ="";

		$details = mysqli_query($this->connection,"SELECT * FROM posts WHERE deleted ='no' ORDER BY id DESC");
	//	$row = mysqli_fetch_array($details);
		//$user_log = new User($this->connection, )


	//	if(mysqli_num_rows($details)>0)
	//	{

		//	$num_iterations = 0;

		//	$count = 1;
		//$id = $row['id'];
	
							

			while($row= mysqli_fetch_array($details))
			{
				$id = $row['id'];
				$added_by = $row['post_by'];
				$body = $row['post_body'];
				$date_time = $row['date'];
				$imagePath = $row['image'];


				$added_by_obj =  new User($this->connection , $added_by);
				if($added_by_obj->isClosed() )
				{
					continue ;
				}
				///

		/*		if($num_iterations++ < $start)
					continue;

				if($count > $limit){
					break;
				}
				else{
					$count++;
				}*/

				

				$user_log_obj = new User($this->connection,$_SESSION['username']);
				//if($user_log_obj->isFriend($added_by))
				//{

				////

					$user_post_details = mysqli_query($this->connection , "SELECT first_name, last_name, profile_pic FROM users WHERE username = '$added_by'");
					$user_details = mysqli_fetch_array($user_post_details);
					//
					$firstName = $user_details['first_name'];
					$lastName = $user_details['last_name'];
					$photo = $user_details['profile_pic'];

					?>

					<?php


						$comments_check = mysqli_query($this->connection, "SELECT * FROM posts_comments WHERE post_id='$id'");
						$comments_check_num = mysqli_num_rows($comments_check);

					
					//Timeframe
							$date_time_now = date("Y-m-d H:i:s");
							$start_date = new DateTime($date_time); //Time of post
							$end_date = new DateTime($date_time_now); //Current time
							$interval = $start_date->diff($end_date); //Difference between dates 
							if($interval->y >= 1) {
								if($interval == 1)
									$time_message = $interval->y . " year ago"; //1 year ago
								else 
									$time_message = $interval->y . " years ago"; //1+ year ago
							}
							else if ($interval-> m >= 1) {
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


							if($imagePath != "" )
							{
								$imageDiv= "<div class ='postedimage' >
										<img src ='$imagePath'>

									       </div>";

							}
							else {
								$imageDiv = "";
							}


								$comment_query = mysqli_query($this->connection , "SELECT * FROM posts_comments WHERE post_id = '$id'") or die ('error');
							$comments = mysqli_num_rows($comment_query);

							if($comments>0)
							{
								$comment_div = "</div>
									<div class='post_comment' id='toggleComment$id' style='display:block;'>
									<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>

									</div>";
							}
							else {

								$comment_div = "</div>
									<div class='post_comment' id='toggleComment$id' style='display:block;'>
									<iframe src='comment_frame.php?post_id=$id'  id='comment_iframe' style = 'max-height: 70px;width: 100%;margin-top: 5px;' frameborder='0'></iframe>

									</div>";
							}

							if($added_by == $user_logged)
							{
								$del = "<div id = 'del'><form action ='index.php' method = 'POST' id='del_form'> <input  type= 'submit'  name ='delete_button$id'  value ='' style='background:url(images/delete2.png) no-repeat ;border: none;cursor:pointer;float: right;width: 50px;height: 40px;'> </form></div>";
							}
							else{
								$del ="";
							}

							if(isset($_POST['delete_button'. $id])){
								$del_query = mysqli_query($this->connection, "UPDATE posts SET deleted = 'yes' WHERE id = '$id' ") or die('error');

								 $num_posts = $this->user_object->getNumPosts();

								 $num_posts--;
								 $query = mysqli_query($this->connection , "UPDATE users SET num_posts = '$num_posts' WHERE username ='$added_by'");
								header("Location: index.php");
								}

							$str .= "<div class = 'statuspost' >


											<div class ='postprofilepic'>
												<a href ='$added_by' ><img src ='$photo' width ='60' ></a>


											</div>
											

											<div class ='postedby'>
												<a id ='poster' href ='$added_by'> $firstName $lastName</a> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id ='time'>$time_message</span><br>
												
											</div>	
											$del
											<br>
											

											<div id='post_body'>
												&nbsp; &nbsp;&nbsp;
												$body
												<br>
												<br>
											</div>
												$imageDiv



											<hr>
											
											<div class = 'like_frame'>
											<iframe src ='likes.php?post_id=$id' scrolling= 'no'></iframe>
											</div>



										$comment_div";
				} // while loop

			/*	if($count > $limit){
				$str .= "<input type ='hidden' class ='nextPage' value ='" . ($page +1) . "'>
				<input type ='hidden' class ='noMorePosts' value ='false'>";
				}

				else{
					$str.= " <input type ='hidden' class ='noMorePosts' value ='false'><span id = 'no'> No more Posts to show! </span>";


				}*/


	//	}
			echo $str;
		

	} //function
	

	public function displayProfilePosts(){

		//$user_logged = $this->user_object->getUsername();
		$str ="";
		$userProfile = $this->user_object->getUsername();
		$details = mysqli_query($this->connection,"SELECT * FROM posts WHERE post_by = '$userProfile'  AND deleted = 'no' ORDER BY id DESC" );
		if(isset($_SESSION['username']))
			{
			$user_logged = $_SESSION['username'];
			//$user_details = mysqli_query($connect, "SELECT * FROM users WHERE username = '$user_logged'");
			//$user = mysqli_fetch_array($user_details);

		}
		//$user_log = new User($this->connection, )
		while($row= mysqli_fetch_array($details))
		{
			$id = $row['id'];
			$added_by = $row['post_by'];
			$body = $row['post_body'];
			$date_time = $row['date'];
			$imagePath = $row['image'];


			$added_by_obj =  new User($this->connection , $added_by);
			if($added_by_obj->isClosed())
			{
				continue ;
			}
			///

			$user_log_obj = new User($this->connection,$_SESSION['username']);
			//if($user_log_obj->isFriend($added_by))
			//{

			////

				$user_post_details = mysqli_query($this->connection , "SELECT first_name, last_name, profile_pic FROM users WHERE username = '$added_by'");
				$user_details = mysqli_fetch_array($user_post_details);
				//
				$firstName = $user_details['first_name'];
				$lastName = $user_details['last_name'];
				$photo = $user_details['profile_pic'];





				?>

	

				<?php
				
				//Timeframe
						$date_time_now = date("Y-m-d H:i:s");
						$start_date = new DateTime($date_time); //Time of post
						$end_date = new DateTime($date_time_now); //Current time
						$interval = $start_date->diff($end_date); //Difference between dates 
						if($interval->y >= 1) {
							if($interval == 1)
								$time_message = $interval->y . " year ago"; //1 year ago
							else 
								$time_message = $interval->y . " years ago"; //1+ year ago
						}
						else if ($interval-> m >= 1) {
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


						if($imagePath != "" )
						{
							$imageDiv= "<div class ='postedimage' >
									<img src ='$imagePath'>

								       </div>";

						}
						else {
							$imageDiv = "";
						}

						$comment_query = mysqli_query($this->connection , "SELECT * FROM posts_comments WHERE post_id = '$id'") or die ('error');
						$comments = mysqli_num_rows($comment_query);

						if($comments>0)
						{
							$comment_div = "</div>
								<div class='post_comment' >
								<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>

								</div>";
						}
						else {

							$comment_div = "</div>
								<div class='post_comment'>
								<iframe src='comment_frame.php?post_id=$id'  id='comment_iframe' style = 'max-height: 70px;width: 100%;margin-top: 5px;' frameborder='0'></iframe>

								</div>";
						}


							if($userProfile == $user_logged)
							{
								$del = "<div id = 'del'><form action ='$user_logged' method = 'POST' id='del_form'> <input  type= 'submit'  name ='delete_button$id'  value ='' style='background:url(images/delete2.png) no-repeat ;border: none;cursor:pointer;float: right;width: 50px;height: 40px;'> </form></div>";
							}
							else{
								$del ="";
							}

							if(isset($_POST['delete_button'. $id])){
								$del_query = mysqli_query($this->connection, "UPDATE posts SET deleted = 'yes' WHERE id = '$id' ") or die('error');

								 $num_posts = $this->user_object->getNumPosts();

								 $num_posts--;
								 $query = mysqli_query($this->connection , "UPDATE users SET num_posts = '$num_posts' WHERE username ='$added_by'");
								header("Location: ".$user_logged);
								}
						$str .= "<div class = 'statuspost' >


										<div class ='postprofilepic'>
											<img src ='$photo' width ='60' >


										</div>

										<div class ='postedby'>
												<a id ='poster' href ='$added_by'> $firstName $lastName</a> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id ='time'>$time_message</span>

											</div>	
											$del
											<br>
											

											<div id='post_body'>
												&nbsp; &nbsp;&nbsp;
												$body
												<br>
												<br>
											</div>
											$imageDiv



										
										<div class ='like_frame'>
										<iframe src ='likes.php?post_id=$id'  scrolling= 'no'></iframe>
										</div>



										$comment_div"

							;
			} // while loop

			/*if($count > $limit){
				$str.= "<input type ='hidden' class ='nextPage' value ='" . ($page +1) . "'>
				<input type ='hidden' class ='noMorePosts' value ='false'>";
					}
				else{
					$str.= " <input type ='hidden' class ='noMorePosts' value ='false'><span id = 'no'> No more Posts to show! </span>";


				}*/
			

			echo $str;
		//}

	} 

}


?>