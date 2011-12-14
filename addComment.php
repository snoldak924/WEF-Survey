<?php 
require 'includes/master.inc.php';
if(!empty($_GET['id']) && $Auth->loggedIn()){
	$db = Database::getDatabase();
	$comment = new Comment();
	$comment->facility_id = $_GET['id'];
	$comment->user_id = $Auth->user->id;
	$comment->comment = $_GET['comment'];
	$comment->insert();
	echo $comment->id;
}