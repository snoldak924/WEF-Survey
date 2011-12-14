<?php 
$page = empty($_GET['id'])?'Add ':''.'Contact';
require 'top.php'; ?>
<h2><?php echo empty($_GET['id'])?'Add ':''; ?>Contact</h2><?php 
$contact = new Contact();
if($Auth->loggedIn()){
	if(empty($_GET['id']) && !empty($_GET['facility'])){ ?>
		<form action="<?php echo $_SERVER['HTTP_REFERER']; ?>" method="post">
		<table>
		<tr><td><label for="name">Name:</label></td>
		<td><input type="text" name="name" value="" id="name" /></td></tr>
		<tr><td><label for="email">Email:</label></td>
		<td><input type="text" name="email" value="" id="email" /></td></tr>
		<tr><td><label for="organization">Organization:</label></td>
		<td><input type="text" name="organization" value="" id="organization" /></td></tr>
		<tr><td><label for="position">Position:</label></td>
		<td><input type="text" name="position" value="" id="position" /></td></tr>
		<tr><td><label for="phone_w">Work Phone:</label></td>
		<td><input type="text" name="phone_w" value="" id="phone_w" /></td></tr>
		<tr><td><label for="phone_c">Cell Phone:</label></td>
		<td><input type="text" name="phone_c" value="" id="phone_c" /></td></tr>
		<tr><td><label for="fax">Fax:</label></td>
		<td><input type="text" name="fax" value="" id="fax" /></td></tr>
		<tr><td><label for="address">Address:</label></td>
		<td><input type="text" name="address" value="" id="address" /></td></tr>
		<tr><td>&nbsp;</td>
		<td><input type="text" name="address2" value="" id="address2" /></td></tr>
		<tr><td><label for="city">City:</label></td>
		<td><input type="text" name="city" value="" id="city" /></td></tr>
		<tr><td><label for="state">State:</label></td>
		<td><select name="state" >
			<option value="">&nbsp;&nbsp;</option><?php
			foreach($states as $state){
				echo "<option value='$state'>$state</option>";
			}?>
			</select></td></tr>
		<tr><td><label for="zip">Zip Code:</label></td>
		<td><input type="text" name="zip" value="" id="zip" /></td></tr>
		<tr><td><label for="notes">Notes:</label></td>
		<td><textarea name="notes" rows="5" cols="40" id="notes"></textarea></td></tr>
		</table>
		<input type="hidden" name="facility_id" value="<?php echo intval($_GET['facility']); ?>" />
		<input type="hidden" name="form" value="add_contact" />
		<input type="submit" value="Save" />
		<input type="button" value="Cancel" onclick="window.location = '<?php echo $_SERVER['HTTP_REFERER']; ?>'" />
		</form><?php
	}else{
		$contact->select(intval($_GET['id']));
		if($contact->ok()){
			if($contact->added_by == $Auth->user->id || $Auth->isModerator()){
				$states = array("AL", "AK", "AZ", "AR", "CA", "CO", "CT", "DE", "DC", "FL", "GA", "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MD", "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ", "NM", "NY", "NC", "ND", "OH", "OK", "OR", "PA", "RI", "SC", "SD", "TN", "TX", "UT", "VT", "VA", "WA", "WV", "WI", "WY");?>

				<form action="<?php echo $_SERVER['HTTP_REFERER']; ?>" method="post">
				<table>
				<tr><td><label for="name">Name:</label></td>
				<td><input type="text" name="name" value="<?=$contact->name;?>" id="name" /></td></tr>
				<tr><td><label for="email">Email:</label></td>
				<td><input type="text" name="email" value="<?=$contact->email;?>" id="email" /></td></tr>
				<tr><td><label for="organization">Organization:</label></td>
				<td><input type="text" name="organization" value="<?=$contact->organization;?>" id="organization" /></td></tr>
				<tr><td><label for="position">Position:</label></td>
				<td><input type="text" name="position" value="<?=$contact->position;?>" id="position" /></td></tr>
				<tr><td><label for="phone_w">Work Phone:</label></td>
				<td><input type="text" name="phone_w" value="<?=$contact->phone_w;?>" id="phone_w" /></td></tr>
				<tr><td><label for="phone_c">Cell Phone:</label></td>
				<td><input type="text" name="phone_c" value="<?=$contact->phone_c;?>" id="phone_c" /></td></tr>
				<tr><td><label for="fax">Fax:</label></td>
				<td><input type="text" name="fax" value="<?=$contact->fax;?>" id="fax" /></td></tr>
				<tr><td><label for="address">Address:</label></td>
				<td><input type="text" name="address" value="<?=$contact->address;?>" id="address" /></td></tr>
				<tr><td>&nbsp;</td>
				<td><input type="text" name="address2" value="<?=$contact->address2;?>" id="address2" /></td></tr>
				<tr><td><label for="city">City:</label></td>
				<td><input type="text" name="city" value="<?=$contact->city;?>" id="city" /></td></tr>
				<tr><td><label for="state">State:</label></td>
				<td><select name="state" >
					<option value="">&nbsp;&nbsp;</option><?php
					foreach($states as $state){
						echo "<option value='$state'".(($contact->state==$state)?' selected="selected"':'').">$state</option>";
					}?>
					</select></td></tr>
				<tr><td><label for="zip">Zip Code:</label></td>
				<td><input type="text" name="zip" value="<?=$contact->zip;?>" id="zip" /></td></tr>
				<tr><td><label for="notes">Notes:</label></td>
				<td><textarea name="notes" rows="5" cols="40" id="notes"><?=$contact->notes;?></textarea></td></tr>
				</table>
				<input type="hidden" name="form" value="edit_contact" />
				<input type="hidden" name="id" value="<?=$contact->id; ?>" />
				<input type="submit" value="Save" />
				<input type="button" value="Cancel" onclick="window.location = '<?php echo $_SERVER['HTTP_REFERER']; ?>'" />
				</form><?php
			}else{ ?>
				<table>
				<tr><td><label for="name">Name:</label></td>
				<td><?=$contact->name;?></td></tr>
				<tr><td><label for="email">Email:</label></td>
				<td><?=$contact->email;?></td></tr>
				<tr><td><label for="organization">Organization:</label></td>
				<td><?=$contact->organization;?></td></tr>
				<tr><td><label for="position">Position:</label></td>
				<td><?=$contact->position;?></td></tr>
				<tr><td><label for="phone_w">Work Phone:</label></td>
				<td><?=$contact->phone_w;?></td></tr>
				<tr><td><label for="phone_c">Cell Phone:</label></td>
				<td><?=$contact->phone_c;?></td></tr>
				<tr><td><label for="fax">Fax:</label></td>
				<td><?=$contact->fax;?></td></tr>
				<tr><td><label for="address">Address:</label></td>
				<td><?=$contact->address;?></td></tr>
				<tr><td>&nbsp;</td>
				<td><?=$contact->address2;?></td></tr>
				<tr><td><label for="city">City:</label></td>
				<td><?=$contact->city;?></td></tr>
				<tr><td><label for="state">State:</label></td>
				<td><?=$contact->state;?></td></tr>
				<tr><td><label for="zip">Zip Code:</label></td>
				<td><?=$contact->zip;?></td></tr>
				<tr><td><label for="notes">Notes:</label></td>
				<td><?=$contact->notes;?></td></tr>
				</table><?php
			}
		}
	}
}else{
	$contact->select(intval($_GET['id']));
	if($contact->ok()){ ?>
		<table>
		<tr><td><label for="name">Name:</label></td>
		<td><?=$contact->name;?></td></tr>
		<tr><td><label for="email">Email:</label></td>
		<td><?=$contact->email;?></td></tr>
		<tr><td><label for="organization">Organization:</label></td>
		<td><?=$contact->organization;?></td></tr>
		<tr><td><label for="position">Position:</label></td>
		<td><?=$contact->position;?></td></tr>
		<tr><td><label for="phone_w">Work Phone:</label></td>
		<td><?=$contact->phone_w;?></td></tr>
		<tr><td><label for="phone_c">Cell Phone:</label></td>
		<td><?=$contact->phone_c;?></td></tr>
		<tr><td><label for="fax">Fax:</label></td>
		<td><?=$contact->fax;?></td></tr>
		<tr><td><label for="address">Address:</label></td>
		<td><?=$contact->address;?></td></tr>
		<tr><td>&nbsp;</td>
		<td><?=$contact->address2;?></td></tr>
		<tr><td><label for="city">City:</label></td>
		<td><?=$contact->city;?></td></tr>
		<tr><td><label for="state">State:</label></td>
		<td><?=$contact->state;?></td></tr>
		<tr><td><label for="zip">Zip Code:</label></td>
		<td><?=$contact->zip;?></td></tr>
		<tr><td><label for="notes">Notes:</label></td>
		<td><?=$contact->notes;?></td></tr>
		</table><?php
	}
}
require 'bottom.php'; ?>