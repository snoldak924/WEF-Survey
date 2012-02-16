<?php 
$page = 'Edit';
$moderator=true;
require 'top.php';
$edit = new Edit();
if(array_key_exists('id',$_GET)){
	$edit->select(intval($_GET['id']));
}
if($edit->ok()){ 
	$facility = new Facility();
	$facility->select($edit->facility_id); ?>
	<div id="facility-form">
	<h2>Facility Edit</h2><?php 
	$user = new User();
	$user->select($edit->user_id);?>
	<form action="<?=$_SERVER['PHP_SELF']."?id=".$edit->id; ?>" method="post" id="edit">
	<ul>
	<li><strong>Submitted by: </strong><a href='user.php?id=<?=$user->id;?>'><?=nonempty(array($user->name,$user->email));?></a></li>
	<?=li('FRS_ID','','','','short');?>
	<li><strong>NPDES #: </strong><?=$facility->NPDES;?></li>
	<?=li('name');?>
	<? /*<li><strong>Data Sources: </strong><?=data_sources($facility->id);?></li> */ ?>
	<?=li('authority','Authority/Operator');?>
	<li><strong>Facility Address</strong>
		<ul>
		<?=li('facility_address','Street');?>
		<?=li('facility_city','City','','','short');?>
		<li><strong>State: </strong>
			<select name="facility_state" >
			<option value="">&nbsp;&nbsp;</option><?php
			foreach($states as $state){
				echo "<option value='$state'".(($edit->facility_state==$state)?' selected="selected"':'').">$state</option>";
			}?>
			</select>
		</li>
		<?=li('facility_zip','Zip','','','short');?>
		<?=li('facility_county','County');?>
		<?=li('facility_lat','Latitude','','','short');?>
		<?=li('facility_long','Longitude','','','short');?>
		</ul>
	</li>
	<?=li('facility_phone','','','','phone');?>
	<?=li('flow_design','Design Flow','MGD','none','numeric','Put best data on design flow here.');?>
	<?=li('flow_avg','Average Flow','MGD','none','numeric','Put best data on average flow here.');?>
	<?=li('flow_capacity','','MGD','none','numeric');?>
	<?=li('flow_mgd','Flow MGD','MGD','none','numeric');?>
	<?=li('flow_permit','','MGD','none','numeric');?>
	<?=li_radio('anaerobic_digestion','','','notMABA');?>
	<?=li_radio('waste_import','Does the WWTP take in hauled or piped additional waste that is fed directly into the digester(s)?','','notMABA');?>
	<li id="ad_details"<?=((get_YN($facility->anaerobic_digestion)=='Y' && !$Auth->isMABA())?'':' style="display:none"');?>>
	<strong>Anaerobic Digestion Details</strong>
	<ul>
		<?=li_radio('ad_type','Type of Digestion','','notMABA',array('Mesophilic','Thermophilic','Both','UNKNOWN'));?>
		<li><strong>Biogas Use Details</strong>
		<ul>
		<?=li_radio('biosolids_digester_gas_utilization','Biogas/Digester Gas Utilization','','notMABA');?>
		<?=li_radio('flare','','','notMABA');?>
		<?=li_radio('drive_process_machinery','Drive Process Machinery/Blowers','','notMABA');?>
		<?=li_radio('digester_heating','','','notMABA');?>
		<?=li_radio('building_heat_ac','Building Heat/AC','','notMABA');?>
		<?=li_radio('direct_pipeline_injection','Direct Injection into Pipeline','','notMABA');?>
		</ul>
		</li>
		<li><strong>Electricity Generation</strong>
		<ul>
			<?=li_radio('generation_ice','Internal Combustion Engine','','notMABA');?>
			<?=li_radio('generation_turbine','Turbine','','notMABA');?>
			<?=li_radio('generation_microturbine','Microturbine','','notMABA');?>
			<?=li_radio('generation_fuel_cell','Fuel Cell','','notMABA');?>
		</ul>
		</li>
		<?=li_radio('generation_2_grid','Power Generation to Grid','','notMABA');?>
	</ul>
	</li>
	</ul>
	<strong>Phase 2</strong>
	<ul>
	<?=li('avg_daily_biogas_production','Biogas Production','MCF/day','notMABA','numeric');?>
	<?=li('stabilization');?>
	<?=li('sludge_production','Sludge production, total','dry tons/year','MABA','numeric');?>
	<?=li('sludge_use','Sludge land applied','dry tons/year','MABA','numeric');?>
	<?=li('sludge_disposal','Sludge disposed by other methods','dry tons/year','MABA','numeric');?>
	<?=li('sludge_incineration','Sludge incinerated','dry tons/year','MABA','numeric');?>
	<?=li('sludge_disposed_landfill','Sludge disposed in landfill','dry tons/year','MABA','numeric');?>
	<?=li('sludge_disposed_unit','Sludge disposed in surface unit','dry tons/year','MABA','numeric');?>
	<?=li_radio('beneficial_biosolids_use');?>
	<?=li_radio('biosolids_heat_recovery');?>
	<?=li_radio('biosolids_distribution');?>
	<?=li('url','URL');?>
	<?=li_radio('methane_use_web');?>
	<?=li_radio('beneficial_biosolids_reuse_web');?>
	<?=li('wastewater_flow','','MMGal/year','none','numeric');?>
	<li><strong>IS THIS FACILITY ENTRY COMPLETE?(don't forget to approve the edit)</strong>
	<div class="jui">
	<input type="radio" name="approved" value="1" id="approved-1" <?=($facility->approved=="1")?' checked="checked"':''; ?>/><label for="approved-1">Yes</label>
	<input type="radio" name="approved" value="0" id="approved-0" <?=($facility->approved=="0")?' checked="checked"':''; ?>/><label for="approved-0">No</label>
	</div>
	</li></ul>
	<div class="jui">
	<?php /*<input type="radio" name="status" value="draft" id="draft" <?=($edit->status=="draft")?' checked="checked"':''; ?>/><label for="draft">Draft</label>
	<input type="radio" name="status" value="approved" id="approved" <?=($edit->status=="approved")?' checked="checked"':''; ?>/><label for="approved">Approved</label>
	<input type="radio" name="status" value="rejected" id="rejected" <?=($edit->status=="rejected")?' checked="checked"':''; ?>/><label for="rejected">Rejected</label>
	*/ ?></div>
	<?php /*<button type="submit" name="submit" value="draft" class="large-button">Update Edit</button> */ ?>
	<button type="submit" name="submit" value="approve" class="large-button">Approve Edit</button>
	<button type="submit" name="submit" value="reject" class="large-button">Reject Edit</button>
	
	
	<input type="hidden" name="form" value="edit" />
	<input type="hidden" name="facility_id" value="<?=$edit->facility_id; ?>" />
	<input type="hidden" name="id" value="<?=$edit->id; ?>" />
	<!--input type="submit" value="Update" style="margin-left:40px;" /-->
	</form>
	</div>
	<div id="contacts"><h2>Contacts</h2><?=contacts($facility->id);?></div><?php
	comments($edit->facility_id);
}
require 'bottom.php'; ?>