

<?php 

class Message {

	private $connection;
	private $user_object ;
	//private $str ="";
	public function __construct($connection, $user)
	{
		$this->connection = $connection;
		//$user_details = mysqli_query($connection,"SELECT * FROM users WHERE username = '$user'");
		$this->user_object = new User($connection , $user);


	}

	public function getMostRecent()
	{
		$userLogged = $this->user_object->getUsername();
		$query = mysqli_query($this->connection, "SELECT user_to,user_from FROM messages WHERE user_to = '$userLogged' OR user_from = '$userLogged' ORDER BY id DESC LIMIT 1");

		if(mysqli_num_rows($query) ==0 )
			return false ;

		else {
			$row = mysqli_fetch_array($query);
			$user_to = $row['user_to'];
			$user_from = $row['user_from'];

			if($user_to != $userLogged)
			{
				return $user_to;

			}

			else
			 return $user_from;
		}
		//return false;

	}
	public function sendMessage($user_to, $body, $date)
	{
		if($body != "")
		{
			$userLogged=$this->user_object->getUsername();
			$query= mysqli_query($this->connection, "INSERT INTO messages VALUES('', '$user_to', '$userLogged', '$body', '$date', 'no', 'no', 'no')") or die ('error message');
			header("Location: messages.php");
			/*if ($connection->query($query) === TRUE) {
    		echo "New record created successfully";
			}*/
			//echo "added messgae";
		}
	}
	public function getMessages($otherUser)
	{
		$userLogged=$this->user_object->getUsername();
		$data="";
		$query = mysqli_query($this->connection, "UPDATE messages SET opened='yes' WHERE user_to='$userLogged' AND user_from='$otherUser' ");
		$get_messages_query = mysqli_query($this->connection, "SELECT * FROM messages WHERE (user_to='$userLogged' AND user_from='$otherUser') OR (user_from='$userLogged' AND user_to='$otherUser')");
		while($row = mysqli_fetch_array($get_messages_query))
		{
			$user_to = $row['user_to'];
			$user_from = $row['user_from'];
			$body = $row['body'];
			$id = $row['id'];

			$div_top =  ($user_to == $userLogged) ? "<div class='portion'><div class='message$id' id='green' style ='border: 1px solid #000;border-radius: 5px;padding: 5px;display: block;color: #25120e;'> " : "<div class= 'portion'><div class='message$id' style='border: 1px solid #000;border-radius: 5px;padding: 5px;display: block;color: #25120e;' id='blue'>";
			$data = $data . $div_top . $body . "</div></div><br><br><br><br>";
		}
		return $data;
	}
	public function getLatestMessage($userLogged,$user2)
	{
		$details_array = array();

		$query = mysqli_query($this->connection,"SELECT body, user_to, date from messages WHERE (user_to = '$userLogged' AND user_from = '$user2') OR (user_from = '$userLogged' AND user_to = '$user2') ORDER BY id DESC LIMIT 1 ");
		$row = mysqli_fetch_array($query);
		$ob = new User($this->connection, $row['user_to']);
		$fname = $ob->getFirstName();
		$sent_by = ($row['user_to'] ==  $userLogged) ? $fname ." said: " : "You said: ";
						$date_time_now = date("Y-m-d H:i:s");
						$start_date = new DateTime($row['date']); //Time of post
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
								$time_message = $interval->m . " month". $days;
							}
							else {
								$time_message = $interval->m . " months". $days;
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
			array_push($details_array, $sent_by);
			array_push($details_array, $row['body']);
			array_push($details_array, $time_message);

			return $details_array;				
	}
	public function getConvos()
	{
		$userLogged= $this->user_object->getUsername();
		$return_string = "";
		$convos = array();

		$query = mysqli_query($this->connection, "SELECT user_to ,user_from from messages where user_to='$userLogged' or user_from='$userLogged' order by id desc");
		while ($row = mysqli_fetch_array($query)) 
		{
			$user_to_push = ($row['user_to'] != $userLogged) ? $row['user_to'] : $row['user_from'];
			if(!in_array($user_to_push, $convos))
			{
				array_push($convos, $user_to_push);
			}
		}

		foreach ($convos as $username) 
		{
			$user_found_obj = new User($this->connection,$username);
			$latest_message_details = $this->getLatestMessage($userLogged,$username);

			$dots = ( strlen($latest_message_details[1]) >=20 )? "..." : "";

			$split = str_split($latest_message_details[1], 20);
			$split=$split[0] . $dots ;
			$name = $user_found_obj->getFirstAndLastName();
			$return_string .= "<a id ='hv' style='text-decoration:none' href ='Messages.php?u=$username'> <div class='user_found_messages'>
			<img src='". $user_found_obj->getProfilePic() . "'style='    width: 27%;border-radius: 5px;margin-right: 5px;height: auto;border-radius: 100px;'>
			 " . "<span id ='poster'> $name </span>". "<br>
			 <span id='timem'>" . $latest_message_details[2] . "</span>
			 <span style='color: #4d4a4a;'> " . $latest_message_details[0]. $split. "</span>
			 </div>
			 </a>" ;

		}
		return $return_string;

	}
}?>
