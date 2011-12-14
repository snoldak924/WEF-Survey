<?php 
$page = 'User Details';
require 'top.php'; ?>
<h2>User Details</h2><?php 
if($Auth->loggedIn()){ 
	$user = new User();
	$user->select($_GET['id']); 
	if($user->ok()){?>
	<table>
	<tr><td>Email:</td><td><a href="mailto:<?php echo $user->email; ?>"><?php echo $user->email; ?></a></td></tr>
	<tr><td>Name:</td>
	<td><?php echo $user->name; ?></td></tr>
	<tr><td>Organization:</td>
	<td><?php echo $user->organization; ?></td></tr>
	<tr><td>Position:</td>
	<td><?php echo $user->position; ?></td></tr>
	<tr><td>Phone:</td>
	<td><?php echo formatPhone($user->phone); ?></td></tr><?php
	if ($user->parent_user_id){
		$parent = new User();
		$parent->select($user->parent_user_id); ?>
		<tr><td>Supervisor:</td>
		<td><a href='user.php?id=<?php echo $parent->id; ?>'><?php 
		echo nonempty(array($parent->name,$parent->email)); ?>
		</a></td></tr><?php
	} ?>
	</table><?php
	}else{
		echo 'Could not find user.';
	}
}
require 'bottom.php'; ?>