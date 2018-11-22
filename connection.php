<?php
ob_start();
	session_start();
$connect = mysqli_connect("localhost" ,"root", "iiita123", "social");

$timezone = date_default_timezone_set("Asia/Kolkata");

if(mysqli_connect_errno())
{
	echo "Failed Connection :" . mysqli_connect_errno();
}

?>