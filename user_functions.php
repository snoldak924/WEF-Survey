<?php
//echo Y/N as a substring function
function YN($string){
	echo strpbrk($string,'yY1')?'Y':'N';
}
function get_YN($string){
	return strpbrk($string,'yY1')?'Y':'N';
}

//return first non-empty
function nonempty($stringArray){
	foreach ($stringArray as $string){
		if(!empty($string))
			return $string;
	}
	return '';
}

//return string or '' if empty
function postVar($string){
	if(!empty($_POST[$string]))
		return $_POST[$string];
	else
		return '';
}

function formatPhone($num){ 
    return format_phone($num); 
}
function comments($facility_id){
	global $Auth;
	echo '<div id="comments"><h2>Comments</h2>';
	if($Auth->loggedIn()){
		echo '<form action="' . $_SERVER['PHP_SELF'] . "?id=" . $facility_id . '" method="post">'.
			'<textarea id="newComment" name="comment" rows="5" style="width:100%"></textarea><br />'.
			'<button type="button" onclick="addComment(' . $facility_id . ')">Add Comment</button>';
			//'<input type="submit" name="submit" value="submit" />'.
			//'<input type="hidden" name="form" value="comment" />'.
			//'<input type="hidden" name="facility_id" value="' .  $facility_id . '" />'.
		echo '</form>';
	}
	echo '<div id="comment-list">';
	$comments = Comment::fetch("SELECT comments.*,users.name AS user_name,users.email AS user_email FROM comments LEFT JOIN users ON comments.user_id=users.id WHERE facility_id='" . $facility_id . "' ORDER BY comments.created DESC");
	foreach($comments as $comment){
		echo '<div class="comment" id="comment-' . $comment->id . '" style="word-wrap:break-word;">';
			if($comment->user_id == $Auth->user->id){ 
				echo '<div style="float:right;">
				<button onclick="deleteComment(' . $comment->id . ');"><span style="color:#991111;">&#10008;</span></button>
				</div>';
			}
		echo '<a href="user.php?id=' . $comment->user_id . '"><b>'.nonempty(array($comment->user_name,$comment->user_email)).'</b></a> - ';
		echo htmlspecialchars($comment->comment).'<br />';
		echo '<span style="color:#888;">'.time2str($comment->created).'</span>';
		echo '</div>';
	}
	echo '</div>';
	echo '</div>';
}

function contacts($facility_id){
	global $Auth;
	$contacts = DBObject::glob('Contact',"SELECT * FROM contacts WHERE facility_id='" . $facility_id . "' ORDER BY name ASC");
	if(count($contacts)){
		echo '<table>';
		echo "\n";
		foreach($contacts as $contact){
			echo '<tr><td><a href="contact.php?id=' .
			$contact->id .
			'" target="_blank">' .
			nonempty(array($contact->name,$contact->position,$contact->organization,$contact->email,$contact->phone_w)) .
			'</a></td>';
			echo "\n";
			echo '<td>';
			echo ($contact->added_by == $Auth->user->id || $Auth->isModerator())?"<a href='#' onclick='if(confirm(\"Are you sure you want to delete this contact?  This is not reversible.\")){window.location=\"" .
			$_SERVER['PHP_SELF'].
			'?deleteContact='.$contact->id.
			(empty($_SERVER['QUERY_STRING'])?'':'&amp;' . 
			$_SERVER['QUERY_STRING']) .
			"\";}' style='text-decoration:none;'><span style='color:#991111;'>&#10008;</span></a>":'&nbsp;';
			echo '</td></tr>';
		}
		echo '</table>';
	}
	if($Auth->loggedIn()){
		echo "<br /><a href='contact.php?facility=$facility_id' target='_blank'>Add a Contact</a>";
	}
}

function remove_querystring_var($url, $key) { 
  $url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&'); 
  $url = substr($url, 0, -1); 
  return $url; 
}

function default_permissions($user_id){
	add_state_permissions($user_id,array("MD","VA","PA","NJ","NY","DC","DE","WV"));
}

function add_state_permissions($user,$states){
	global $db;
	$insert = "REPLACE INTO permissions (state,user_id) VALUES ";
	$values = array();
	foreach($states as $state){
		$values[] = "('".$db->escape($state)."',{$user->id})";
	}
	$db->query($insert . implode(',',$values));
}
function add_region_permissions($user,$regions){
	global $db, $EPARegions;
	$insert = "REPLACE INTO permissions (state,user_id) VALUES ";
	$values = array();
	foreach($regions as $region){
		foreach($EPARegions[$region] as $state){
			$values[] = "('$state',{$user->id})";
		}
	}
	$db->query($insert . implode(',',$values));
}
function get_permissions($user){
	global $db;
	return $db->getValues("SELECT state FROM permissions WHERE user_id={$user->id} ORDER BY state ASC");
}
function remove_permissions_from_user($user){
	global $db;
	$db->query("DELETE FROM permissions WHERE user_id={$user->id}");
}

function user_from_invite($invite){
	$user = new User();
	$user->select($invite,'nid');
	return $user;
}

function data_sources($facility_id){
	global $db;
	echo implode(', ', $db->getValues("SELECT name FROM data_source_map LEFT JOIN data_sources ON data_sources.id=data_source_map.source_id WHERE data_source_map.facility_id=$facility_id"));
}
function data_sources2($facility_id){
	global $db;
	return $db->getValues("SELECT name FROM data_source_map LEFT JOIN data_sources ON data_sources.id=data_source_map.source_id WHERE data_source_map.facility_id=$facility_id");
}
function input($field,$restrict='none',$type='text'){
	global $facility,$edit,$Auth;
	$field2 = ($edit==null?$facility->$field:$edit->$field);
	$return = "<input type='text' name='$field' value='";
	$return .= ($type=='numeric'?number_format($field2,2):($type=='phone'?format_phone($field2):$field2));
	$return .= "' class='$type";
	if($facility->$field!=$field2){
		$return .= " changed' title='Original: " . ($type=='numeric'?number_format($facility->$field,2):$facility->$field)."'";
	}else{
		$return .= "'";
	}
	$return .= '/>';
	return $return;
}
function li($field,$title='',$description='',$restrict='none',$type='text',$subtitle=''){
	global $Auth,$edit,$facility;
	$title=($title?$title:ucwords(str_replace('_',' ',$field)));
	$field2 = ($edit==null?$facility->$field:$edit->$field);
	$return = "<li title='$description'";
	switch($restrict){
	case 'notMABA':
		$return.= $Auth->isMABA()?' style="display:none"':'';
		break;
	case 'MABA':
		$return.= $Auth->isMABA() || $Auth->isAdmin()?'':' style="display:none"';
		break;
	}
	$return .= "><strong>$title: </strong>";
	$return .= $type=='numeric'?'<span style="display:inline-block">':'';
	if($Auth->loggedIn()){
		$return .= input($field,$restrict,$type);
	}else{
		$return .= $type=='numeric'?number_format($field2,2):htmlspecialchars($type=='phone'?format_phone($field2):$field2);
	}
	$return .= ($type=='numeric'?" $description</span>":'');
	$return .= !empty($subtitle)?"<br /><span style='font-size:.8em;color:#444;font-style:italic;'>$subtitle</span>":'';
	$return .= "</li>\n";
	return $return;
}
function li_radio($field,$title='',$description='',$restrict='none',$values=array('Y','N','UNKNOWN')){
	global $Auth,$edit,$facility;
	$title=($title?$title:ucwords(str_replace('_',' ',$field)));
	$field2 = ($edit==null?$facility->$field:$edit->$field);
	$return = "<li title='$description'";
	switch($restrict){
	case 'notMABA':
		$return.= $Auth->isMABA()?' style="display:none"':'';
		break;
	case 'MABA':
		$return.= $Auth->isMABA() || $Auth->isAdmin()?'':' style="display:none"';
		break;
	}
	$return .= "><strong>$title:</strong>";
	
	if($Auth->loggedIn()){
	
		$return .= '<div class="jui">';
		foreach($values as $key=>$value){
			$return.= "<input ".
			($field=="anaerobic_digestion"?"onclick='toggle_AD_details();' ":'').
			"type='radio' name='$field' value='$value' id='$field-$key' ".
			($value==$field2?' checked="checked" ':'').
			" /><label for='$field-$key' ".
			($facility->$field!=$field2?'title="Original: ' .
			($facility->$field?$facility->$field:'blank').
			'" class="labelChanged" style="background:#bFe5ef;"':'').
			">".
			ucwords(strtolower($value)).
			"</label>";
		}
		$return .= '</div>';
	}else{
		$return .= $field2;
	}
	$return .= "</li>\n";
	return $return;
}

function pretty_num($number){
	return number_format($number,1)?number_format($number,1):'-';
}
function stabilization($number){
	$map = array(
	1=>'38% reduction of volatile solids',
	2=>'additional digestion <17% over 40 days at >30&deg;C',
	3=>'additional digestion <15% over 30 days at 20&deg;C',
	4=>'specific oxygen uptake rate <1.5 mg/hr-g at 20&deg;C',
	5=>'aerobic process 14 days >40&deg;C throughout', //composting
	6=>'through lime addition, pH >12 for 2 hr, >11.5 for additional 22 hr',
	7=>'percent solids >75% in stabilized primary sludge prior to blending',
	8=>'percent solids >90% in unstabilized primary sludge before prior to blending',
	9=>'unstabilized sludge is injected below the soil surface',
	10=>'unstabilized sludge is incorporated into the soil within 6 hours',
	''=>'',
	0=>''
	);
	return ucfirst($map[$number]);
}