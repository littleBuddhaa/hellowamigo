<?php
require 'connection.php';
include("user.php");
include("posts.php");


$limit = 10;

$posts = new Post($connect , $_REQUEST['user_logged']);

$posts->displayPosts($_REQUEST, $limit);

?>