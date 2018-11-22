

<?php 
include("connection.php");
include("user.php");
include("index.php");

$query=$_POST['query'];
$userLogged = $_POST['userLogged'];

$names = explode(" ",$query);

/*if(strpos($query,"_") !== false)
{
	$usersReturned = mysqli_query($this->connection,"SELECT * from users where username LIKE '$quer%' and user_closed='no' LIMIT 8 ");
}*/

$usersReturned = mysqli_query($this->connection,"SELECT * users where (first_name like'%names[0]%' and last_name like '%names[1]%') and user_closed='no' limit 8");
if($query != "")
{
	while($row = mysqli_fetch_array($userReturned)
	{
		$user = new User ($connection,$userLogged);
		if($row['username'] != $userLogged)
		{
			$mutual_friends = $user->getMutualFriends($row['username']) . "friends in common";
		}
		else
		{
			$mutual_friends = "";
		}
		if($user->isFriend($row['username']))
		{
			echo "<div class='resultDisplay' 
					<a href='Messages.php?u='". $row['username']. "' style = color: #000'>
					<div class='liveSearchProfilePic' >
					<img src= '". $row['profile_pic'] . "'>
					</div>
					<div class='liveSearchText'>
						". $row['first_name']." ". $row['last_name'] . "
						<p id='grey'>".$mutual_friends."</p>
						</div>
					</a>
				</div>"
		}

	}
}
?>
