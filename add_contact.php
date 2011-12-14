<?php 
$page = 'Add Contact';
require 'top.php'; ?>
<h2>Add Contact</h2><?php 
if($Auth->loggedIn()){ 
$states = array("AL", "AK", "AZ", "AR", "CA", "CO", "CT", "DE", "DC", "FL", "GA", "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MD", "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ", "NM", "NY", "NC", "ND", "OH", "OK", "OR", "PA", "RI", "SC", "SD", "TN", "TX", "UT", "VT", "VA", "WA", "WV", "WI", "WY");?>

	<form action="facility.php?id=<?php echo $_GET['facility']; ?>" method="get">
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
	<td><input type="text" name="adress2" value="" id="address2" /></td></tr>
	<tr><td><label for="city">City:</label></td>
	<td><input type="text" name="city" value="" id="city" /></td></tr>
	<tr><td><label for="state">State:</label></td>
	<td><select name="facility_state" >
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
	<input type="hidden" name="form" value="add_contact" />
	<input type="submit" value="Save" />
	</form><?php
}
require 'bottom.php'; ?>