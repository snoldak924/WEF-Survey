<?php 
$page = 'Manage Permissions';
$moderator=true;
require 'top.php'; ?>
<h2>Manage Permissions</h2><?php 
if($Auth->loggedIn() && !empty($_GET['id'])){ 
	$user = new User();
	$user->select($_GET['id']);
	if($user->ok() && $user->parent_user_id==$Auth->user->id || ($user->id==$Auth->user->id && $Auth->isModerator())){
		echo "<h3>". nonempty(array($user->name,$user->email)). "</h3>"; ?>
		<form action="<?php echo $_SERVER['HTTP_REFERER']; ?>" method="post">
		<table><?php
		$permissions = $db->getValues("SELECT state FROM permissions WHERE user_id={$user->id} ORDER BY state ASC");
		foreach($EPARegions as $region => $states){
			echo "<tr><td><span style='font-weight:900;font-size:1.2em;'>Region $region</span></td>";
			//echo "<div class='jui2'>";
			echo "<td><input id='btn' onclick='toggleRegion($region);' value='Toggle Region' type='button'></td><td>";
			foreach($states as $state){
				echo "<input type='checkbox' name='states[]' id='$state' value='$state' " . (in_array($state,$permissions)?' checked="checked" ':'') . "/><label for='$state'>$state</label> ";
			}
			//echo "</div>";
			echo "</td></tr>";
		}?>
		</table>
		<p><input type="hidden" name="form" value="manage_permissions" />
		<input type="hidden" name="user_id" value="<?php echo mysql_real_escape_string($_GET['id']); ?>" />
		<input type="submit" value="Save" />
		<input type="button" value="Cancel" onclick="window.location = '<?php echo $_SERVER['HTTP_REFERER']; ?>'" /></p>
		</form><?php
	}
}
require 'bottom.php'; ?>