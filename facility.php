<?php 
$page = 'Facility';
require 'top.php';
$facility = new Facility();
if(array_key_exists('id',$_GET)){
	$facility->select($_GET['id']);
}
if($facility->ok()){ ?>
	<div id="facility-form">
	<h2>Facility</h2>
	<?php if($Auth->loggedIn()){ ?>
		<form action="<?php echo $_SERVER['PHP_SELF']."?id=".$facility->id; ?>" method="post">
	<? } ?>
	<ul>
	<?=li('FRS_ID','','EPA Registry ID','','short');?>
	<li><strong>NPDES #: </strong><?=$facility->NPDES;?></li>
	<?=li('name');?>
	<li><strong>Data Sources: </strong><?=data_sources($facility->id);?></li>
	<?=li('authority','Authority/Operator');?>
	<li><strong>Facility Address</strong>
	<ul>
		<?=li('facility_address','Street');?>
		<?=li('facility_city','City','','','short');?>
		<li><strong>State: </strong><?php 
		if($Auth->loggedIn()){ ?>
		<select name="facility_state" >
		<option value="">&nbsp;&nbsp;</option><?php
		foreach($states as $state){
			echo "<option value='$state'".(($facility->facility_state==$state)?' selected="selected"':'').">$state</option>";
		}?>
		</select><? 
		}else{?>
			<?=$facility->facility_state;?>
		<?}?>
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
	<div id="phase2">
	<h3><a href="#">Phase 2</a></h3>
	<div>
	<ul>
	<?=li('avg_daily_biogas_production','Biogas Production','MCF/day','notMABA','numeric');?>
	<li><strong>Stabilization: </strong><?php 
		if($Auth->loggedIn()){ ?>
		<select name="stabilization" >
		<option value="">&nbsp;&nbsp;</option><?php
		foreach(range(1,10) as $stabilization){
			echo "<option value='$stabilization'".(($facility->stabilization==$stabilization)?' selected="selected"':'').">$stabilization</option>";
		}?>
		</select><? 
		}else{?>
			<?=$facility->stabilization;?>
		<?}?>
		</li>
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
	</ul>
	</div>
	</div>
	<?php if($Auth->loggedIn()){ ?>
	<input type="hidden" name="form" value="edit_facility" />
	<input type="hidden" name="facility_id" value="<?=$facility->id;?>" />
	<button type="submit"  class="large-button">Submit Edit</button>
	<button type="reset"  class="large-button">Reset to Original</button>
	<button type="button" class="large-button" onclick="window.close();" >Cancel</button>
	</form>
	<? } ?>
	</div>
	<div id="contacts"><h2>Contacts</h2><?=contacts($facility->id);?></div>
	<?php comments($facility->id);
}
require 'bottom.php'; ?>