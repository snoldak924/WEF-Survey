<?php 
require 'includes/master.inc.php';
if(!empty($_GET['id']) && $Auth->isModerator()){
	$facility = new Facility();
	$facility->select($_GET['id']);
	$facility->approved=0;
	$facility->update();
}