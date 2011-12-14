<?php 
require 'includes/master.inc.php';
if(!empty($_GET['id']) && $Auth->loggedIn()){
	$db = Database::getDatabase();
	$db->query("DELETE FROM comments WHERE id=".mysql_real_escape_string($_GET['id'])." AND user_id={$Auth->user->id} LIMIT 1");
}