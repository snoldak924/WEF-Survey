<?php 
$start=microtime();
require 'top.php';
if($Auth->loggedIn()){ ?>
	<h2>Select a Facility</h2>
	<form action="\" method="get" name="county_form" id="county_form">
	<select name="state" onchange='load_counties(this.value)'>
	<option value="">Select a State</option><? 
	echo get_options('permissions','state','state',(!empty($_GET['state'])?mysql_real_escape_string($_GET['state']):null),' WHERE user_id='. $Auth->user->id.' ORDER BY state ASC');
	echo "</select>";
	if(array_key_exists('state',$_GET)){
		echo "<select name='county' id='county'><option value='all'>All Counties</option>";
		echo get_options('facilities','facility_county','facility_county',$county,' WHERE facility_state="'.mysql_real_escape_string($_GET['state']).'" GROUP BY facility_county');
		echo "</select>";
		echo "<input type='submit' value='View' id='countySubmit' />";
	} ?>
	</form><?php
	if(array_key_exists('state',$_GET)) { ?>
		<h2>List of Facilities in <?=$county?ucwords(strtolower(str_ireplace(' county','',$county)))." County, ":'';?><?=htmlspecialchars($_GET['state']);?></h2>
		<div id="loading">Loading table <img src="images/ajax-loader.gif" /></div>
		<table class="display" id="facilities" style="width:100%;display:none">
		<thead>
		<tr>
			<th>NPDES</th>
			<th>FRS ID</th>
			<th>Name</th>
			<th>Facility Address</th>
			<th>County</th>
			<th>Phone</th>
			<th>AD</th>
			<th>Stabilization</th>
			<th>Plant Capacity</th>
			<th>Average Flow</th>
			<th>MGD</th>
			<th>Design Flow</th>
			<th>Permitted Flow</th>
			<th>Sludge Production</th>
			<th>Sludge land applied</th>
			<th>Sludge disposed (other)</th>
			<th>Sludge incinerated</th>
			<th>Sludge disposed in landfill</th>
			<th>Sludge disposed in surface unit</th>
			<th>Authority</th>
			<th><img src="/images/view.png" alt="View"/></th>
			<th><img src="/images/comments.png" alt="Comments"/></th>
			<th>Edits</th>
			<th>Approved</th>
		</tr>
		</thead>
		<tbody><?php
		$facilities = DBObject::glob('Facility',"SELECT * FROM facilities WHERE facility_state='".mysql_real_escape_string($_GET['state'])."'".((!empty($county) && $county!='all')?" AND facility_county='$county'":'').' ORDER BY name ASC');
		
		foreach($facilities as $facility){ 
			$comments = $db->getValue("SELECT count(1) FROM comments WHERE facility_id={$facility->id}");
			$edits = $db->getValue("SELECT count(1) FROM facilities_edits WHERE facility_id={$facility->id}");
			?>
			<tr>
			<td><?=$facility->NPDES; ?></td>
			<td><?=$facility->FRS_ID; ?></td>
			<td><?=nonempty(array($facility->name,$facility->authority));?></td>
			<td><?=(nonempty(array($facility->facility_address,$facility->facility_city))?$facility->facility_address.', '.$facility->facility_city:'')?></td>
			<td><?=$facility->facility_county; ?></td>
			<td><?=$facility->facility_phone; ?></td>
			<td><?=YN($facility->anaerobic_digestion);?></td>
			<td><?=stabilization($facility->stabilization); ?></td>
			<td><?=pretty_num($facility->flow_capacity);?></td>
			<td><?=pretty_num($facility->flow_avg);?></td>
			<td><?=pretty_num($facility->flow_mgd);?></td>
			<td><?=pretty_num($facility->flow_design);?></td>
			<td><?=pretty_num($facility->flow_permit);?></td>
			<td><?=pretty_num($facility->sludge_production);?></td>
			<td><?=pretty_num($facility->sludge_use);?></td>
			<td><?=pretty_num($facility->sludge_disposal);?></td>
			<td><?=pretty_num($facility->sludge_incineration);?></td>
			<td><?=pretty_num($facility->sludge_disposed_landfill);?></td>
			<td><?=pretty_num($facility->sludge_disposed_unit);?></td>
			<td><?=$facility->authority; ?></td>
			<td><a href="facility.php?id=<?=$facility->id; ?>" target="_blank" ><img src="/images/view.png" alt="View" /></a></td>
			<td><a class="spch-bub-inside" href="/facility.php?id=<?=$facility->id; ?>"  target="_blank" ><span class="point"></span><em><?=$comments; ?></em></a></td>
			<td><?=$edits; ?></td>
			<td><?php 
			if(!$Auth->isModerator()){ 
				echo ($facility->approved?'<div class="greencheck">&#10004;</div>':'<div class="redex">&#10008;</div>');
			}else{ ?>
				<span style="display:none;"><?=$facility->approved;?></span>
				<div class="admin jui" style="width:6em;">
					<input onclick="approve_yes(<?=$facility->id;?>)" type="radio" id="approved-yes-<?=$facility->id;?>" name="approved-group-<?=$facility->id;?>" <?=($facility->approved?'checked="checked"':'');?> />
					<label class="good" for="approved-yes-<?=$facility->id;?>">
						<div class="check">&#10004;</div>
					</label>
					<input onclick="approve_no(<?=$facility->id;?>)"type="radio" id="approved-no-<?=$facility->id;?>" name="approved-group-<?=$facility->id;?>" <?=($facility->approved?'':'checked="checked"');?> />
					<label class="bad" for="approved-no-<?=$facility->id;?>">
						<div class="ex">&#10008;</div>
					</label>
				</div><?php 
			} ?>
			</td>
			</tr><?php
		} ?>
		</table><?php
	}
} else { ?>
	<div style="margin:20px auto;width:600px">
	<div style="width:300px;float:left;">
	<h2>Log in</h2>
	<form action="<?=$_SERVER['PHP_SELF']; ?>" method="post">
	<p><label for="email">Email:</label> <input type="text" name="email" value="" id="email" /></p>
	<p><label for="password">Password:</label> <input type="password" name="password" value="" id="password" /></p>
	<p><input type="submit" name="btnlogin" value="Login" id="btnlogin" /></p>
	<input type="hidden" name="form" value="login">
	</form>
	</div>
	<div style="width:300px;float:left">
	<h2>Create Account</h2>
	<form action="/" method="post">
	<p><label for="email">Email:</label> <input type="text" name="email" value="" id="email" /></p>
	<p><label for="password">Password:</label> <input type="password" name="password" value="" id="password" /></p>
	<p><label for="invite">Invite Code:</label> <input type="text" name="invite" value="" id="invite" /></p>
	<p><input type="submit" name="btnlogin" value="Create Account" id="btnlogin" /></p>
	<input type="hidden" name="form" value="create">
	</form>
	</div>
	<br style="clear:both;" />
	</div><?php
}
require 'bottom.php'; 
?>