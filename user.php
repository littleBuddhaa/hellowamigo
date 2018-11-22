<?php 
class User {

	private $connection;
	private $user ;

	public function __construct($connection, $user)
	{
		$this->connection = $connection;
		$user_details = mysqli_query($connection,"SELECT * FROM users WHERE username = '$user'");
		$this->user = mysqli_fetch_array($user_details);


	}
	public function getFirstAndLastName(){
		$username = $this->user['username'];
		$query = mysqli_query($this->connection,"SELECT first_name, last_name FROM users WHERE username = '$username'");
		$row = mysqli_fetch_array($query);
		return $row['first_name'] . " " . $row['last_name'];
	}
	public function getFirstName(){
		$username = $this->user['username'];
		$query = mysqli_query($this->connection,"SELECT first_name FROM users WHERE username = '$username'");
		$row = mysqli_fetch_array($query);
		return $row['first_name'] ;
	}


	public function getUsername()
	{
		$username = $this->user['username'];
		return $username;

	}
	public function getProfilePic()
	{
		$propic = $this->user['profile_pic'];
		return $propic;
	}
	//public function getFriendArray()
	//{
	//	$fa = $this->user['friend_array'];
	//	return $fa;

	//}
	public function getNumPosts()
	{
		$username = $this->user['username'];
		$query = mysqli_query($this->connection, "SELECT num_posts FROM users WHERE username = '$username'");
		$row = mysqli_fetch_array($query);
		return $row['num_posts'];

	}
	public function isClosed()
	{
		$username = $this->user['username'];
		$query = mysqli_query($this->connection, "SELECT user_closed FROM users WHERE username='$username'");
		$row = mysqli_fetch_array($query);

		if($row['user_closed'] == 'yes')
			return true;
		else 
			return false;
	}

	public function isFriend($friend_check)
	{
		//$usernameComma = "," . $friend_check . ",";
		//if((strstr($this->user['friend_array'], $usernameComma) || $friend_check == $this->user['username']))
		//{
	//		return true;

	//	}
		
	//	return false ;


		$friendOf = $this->user['username'];

		$query = mysqli_query($this->connection ,"SELECT * FROM friends WHERE (user_from = '$friendOf' AND user_to = '$friend_check' ) OR (user_from = '$friend_check' AND user_to = '$friendOf') ");
		if(mysqli_num_rows($query) >0)
			return true;

		else 
			return false;
	}

	public function receive_request($userFrom)
	{
		$userTo = $this->user['username'];
		$check_request = mysqli_query($this->connection, "SELECT * FROM friend_request WHERE user_to = '$userTo' AND user_from='$userFrom'");
		if(mysqli_num_rows($check_request) >0)
		{
			return true;
		}
		return false;

	}
	public function sent_request($userTo)
	{
		$userFrom = $this->user['username'];
		$check_request = mysqli_query($this->connection, "SELECT * FROM friend_request WHERE user_to = '$userTo' AND user_from='$userFrom'");
		if(mysqli_num_rows($check_request) >0)
		{
			return true;
		}
		return false;
	}

	public function remove_friend($user_remove)
	{
		$friendOf = $this->user['username'];
		/*$query =  mysqli_query($this->connection , "SELECT friend_array FROM users WHERE username = '$user_to_be_remove'");
		$row = mysqli_fetch_array($query);
		$friend_array_of_username = $row['friend_array'];

		$new_friend_array = str_replace($user_to_be_removed . ",", "", $this->user['friend_array']);
		$remove = mysqli_query($this->connection , "UPDATE users SET friend_array = '$new_friend_array' WHERE username = $logged_in" );
		

		$new_friend_array = str_replace($user_to_be_removed . ",", "", $friend_array_of_username);
		$remove = mysqli_query($this->connection , "UPDATE users SET friend_array = '$new_friend_array' WHERE username = $friend_array_of_username" );*/

		$query = mysqli_query($this->connection , "DELETE FROM friends WHERE (user_from = '$friendOf' AND user_to = '$user_remove' ) OR (user_from = '$user_remove' AND user_to = '$friendOf') ");

	}

	public function send_request($user_to)
	{
		$user_from = $this->user['username'];
		$query =  mysqli_query($this->connection , "INSERT INTO friend_request VALUES ('','$user_to','$user_from')");


	}


	public function getMutualFriends($user_to_check)
	{
		/*$mutualFriends = 0;
		$user_array = $this->user['friend_array'];
		$other_user_obj = new User($this->connection, $user_to_check);
		$other_user_array = $other_user_obj->getFriendArray();

		$user_array_explode = explode(",", $user_array);
		$other_user_array_explode = explode("," ,$other_user_array );

		foreach ($user_array_explode as $i ) {
			foreach ($other_user_array_explode as $j ) {
				if($i == $j && $i!="")
					$mutualFriends++;
			}
		}
		return $mutualFriends;*/
	}
}


?>