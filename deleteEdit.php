<?php 
require 'includes/master.inc.php';
if(!empty($_GET['id']) && $Auth->isModerator()){
	$edit = new Edit();
	$edit->select($_GET['id']);
	$edit->delete();
}