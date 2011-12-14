<?php 
$page = 'My Account';
require 'top.php'; ?>
<h2>My Account</h2><?php 
if($Auth->loggedIn()){ ?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<table>
	<tr><td>Email:</td><td><?php echo $Auth->user->email; ?></td></tr>
	<?php if($Auth->isModerator()){ ?><tr><td>Invite Code:</td><td><?php echo $Auth->user->nid; ?></td></tr><?php } ?>
	<tr><td><label for="name">Name:</label></td>
	<td><input type="text" name="name" value="<?php echo $Auth->user->name; ?>" id="name" /></td></tr>
	<tr><td><label for="organization">Organization:</label></td>
	<td><input type="text" name="organization" value="<?php echo $Auth->user->organization; ?>" id="organization" /></td></tr>
	<tr><td><label for="position">Position:</label></td>
	<td><input type="text" name="position" value="<?php echo $Auth->user->position; ?>" id="position" /></td></tr>
	<tr><td><label for="phone">Phone:</label></td>
	<td><input type="text" name="phone" value="<?php echo format_phone($Auth->user->phone); ?>" id="phone" /></td></tr>
	</table>
	<input type="hidden" name="form" value="update_account" />
	<input type="submit" value="Save" />
	</form><?php
	$permissions = get_permissions($Auth->user);
	echo '<h2>Permissions</h2>';
	if($Auth->isModerator()){
		echo "<a href='manage_permissions.php?id={$Auth->user->id}'>Edit My Permissions</a>";
	}
	if(count($permissions)){
		echo '<ul id="permissions">';
		foreach($permissions as $permission){
			echo "<li>$permission</li>";
		}
		echo '</ul>';
	}
	$users = DBObject::glob('User',"SELECT id,email,name FROM users WHERE parent_user_id={$Auth->user->id} ORDER BY name ASC,email ASC");
	if(count($users)){
		echo '<h2>Delegates</h2>';
		echo '<ul>';
		foreach($users as $user){
			echo "<li><a href='user.php?id={$user->id}'>" . nonempty(array($user->name,$user->email)) . "</a> - ";
			echo "<a href='manage_permissions.php?id={$user->id}'>Manage Permissions</a></li>";
		}
		echo '</ul>';
	}
	$supervisor = new User();
	$supervisor->select($Auth->user->parent_user_id);
	if($supervisor->ok()){
		echo '<h2>Supervisor</h2>';
		echo "<p><a href='user.php?id={$supervisor->id}'>" . nonempty(array($supervisor->name,$supervisor->email)) . "</a></p>";
	}
}
require 'bottom.php'; ?>