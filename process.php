<?php 
if(!empty($_POST['form'])){
	if(!$Auth->loggedIn()){
		if ($_POST['form']=='create'){
			if($Auth->createNewUser($_POST['email'],$_POST['invite'],$_POST['password'])){
				$Auth->login($_POST['email'], $_POST['password']);
			}else{
				$badmsg[] = 'Account could not be created.';
			}
		}else if ($_POST['form']=='login'){
			if($Auth->login($_POST['email'], $_POST['password'])){
				//redirect('/');
			}
		}
	}else{
		if ($_POST['form']=='update_account'){
			$user = new User();
			$user->select($Auth->user->id);
			$user->name = $_POST['name'];
			$user->phone = preg_replace('#[^0-9]#', '', $_POST['phone']); 
			$user->organization = $_POST['organization'];
			$user->position = $_POST['position'];
			$user->update();
			$Auth->user->name = $_POST['name'];
			$Auth->user->organization = $_POST['organization'];
			$Auth->user->position = $_POST['position'];
			$Auth->user->phone = preg_replace('#[^0-9]#', '', $_POST['phone']); 
			$goodmsg[] = 'Account Updated.';
		}else if ($_POST['form']=='comment'){
			$comment = new Comment();
			$comment->facility_id = $_POST['facility_id'];
			$comment->user_id = $Auth->user->id;
			$comment->comment = $_POST['comment'];
			$comment->insert();
		}else if ($_POST['form']=='manage_permissions'){
			$user = new User();
			$user->select($_POST['user_id']);
			if($user->ok() && ($user->parent_user_id==$Auth->user->id || ($user->id==$Auth->user->id && $Auth->isModerator()))){
				remove_permissions_from_user($user);
				add_state_permissions($user,$_POST['states']);
			}
			$goodmsg[]='Permissions updated.';
		}else if($_POST['form']=='edit_contact'){
			$contact = new Contact();
			$contact->select($_POST['id']);
			if($contact->added_by == $Auth->user->id || $Auth->isModerator()){
				$contact->name = $_POST['name'];
				$contact->phone_w = $_POST['phone_w'];
				$contact->phone_c = $_POST['phone_c'];
				$contact->fax = $_POST['fax'];
				$contact->address = $_POST['address'];
				$contact->address2 = $_POST['address2'];
				$contact->city = $_POST['city'];
				$contact->state = $_POST['state'];
				$contact->zip = $_POST['zip'];
				$contact->email = $_POST['email'];
				$contact->organization = $_POST['organization'];
				$contact->position = $_POST['position'];
				$contact->notes = $_POST['notes'];
				$contact->update();
			}
		}else if($_POST['form']=='add_contact'){
			$contact = new Contact();
			$contact->facility_id = ($_POST['facility_id']);
			$contact->name = $_POST['name'];
			$contact->phone_w = $_POST['phone_w'];
			$contact->phone_c = $_POST['phone_c'];
			$contact->fax = $_POST['fax'];
			$contact->address = $_POST['address'];
			$contact->address2 = $_POST['address2'];
			$contact->city = $_POST['city'];
			$contact->state = $_POST['state'];
			$contact->zip = $_POST['zip'];
			$contact->email = $_POST['email'];
			$contact->position = $_POST['position'];
			$contact->organization = $_POST['organization'];
			$contact->notes = $_POST['notes'];
			$contact->added_by = $Auth->user->id;
			$contact->insert();
			$goodmsg[]='Contact added.';
		}else if($_POST['form']=='edit_facility'){
			$edit = new Edit();
			$edit->facility_id = $_POST['facility_id'];
			$edit->user_id = $Auth->user->id;
			$edit->FRS_ID = $_POST['FRS_ID'];
			$edit->name = $_POST['name'];
			$edit->authority = $_POST['authority'];
			$edit->facility_address = $_POST['facility_address'];
			$edit->facility_city = $_POST['facility_city'];
			if(!empty($_POST['facility_state'])){
				$edit->facility_state = $_POST['facility_state'];
			}else{
				$badmsg[]='Please enter the facility state.';
			}
			$edit->facility_zip = $_POST['facility_zip'];
			$edit->facility_county = $_POST['facility_county'];
			$edit->facility_phone = preg_replace('#[^0-9]#', '', $_POST['facility_phone']); 
			$edit->facility_lat = $_POST['facility_lat'];
			$edit->facility_long = $_POST['facility_long'];
			$edit->flow_capacity = floatval(str_replace(",","",$_POST['flow_capacity']));
			$edit->flow_avg = floatval(str_replace(",","",$_POST['flow_avg']));
			$edit->flow_mgd = floatval(str_replace(",","",$_POST['flow_mgd']));
			$edit->flow_design = floatval(str_replace(",","",$_POST['flow_design']));
			$edit->flow_permit = floatval(str_replace(",","",$_POST['flow_permit']));
			$edit->universe = postVar('universe');
			$edit->avg_daily_biogas_production = $_POST['avg_daily_biogas_production'];
			$edit->flare = $_POST['flare'];
			$edit->stabilization = $_POST['stabilization'];
			$edit->anaerobic_digestion = postVar('anaerobic_digestion');
			$edit->ad_type = postVar('ad_type');
			$edit->biosolids_digester_gas_utilization = postVar('biosolids_digester_gas_utilization');
			$edit->beneficial_biosolids_use = postVar('beneficial_biosolids_use');
			$edit->biosolids_distribution = postVar('biosolids_distribution');
			$edit->url = $_POST['url'];
			$edit->methane_use_web = $_POST['methane_use_web'];
			$edit->beneficial_biosolids_reuse_web = $_POST['beneficial_biosolids_reuse_web'];
			$edit->wastewater_flow = floatval(str_replace(",","",$_POST['wastewater_flow']));
			$edit->sludge_production = floatval(str_replace(",","",$_POST['sludge_production']));
			$edit->sludge_use = floatval(str_replace(",","",$_POST['sludge_use']));
			$edit->sludge_disposal = floatval(str_replace(",","",$_POST['sludge_disposal']));
			$edit->sludge_incineration = floatval(str_replace(",","",$_POST['sludge_incineration']));
			$edit->sludge_disposed_unit = floatval(str_replace(",","",$_POST['sludge_disposed_unit']));
			$edit->sludge_disposed_landfill = floatval(str_replace(",","",$_POST['sludge_disposed_landfill']));
			$edit->biosolids_heat_recovery = postVar('biosolids_heat_recovery');
			$edit->drive_process_machinery = postVar('drive_process_machinery');
			$edit->digester_heating = postVar('digester_heating');
			$edit->building_heat_ac = postVar('building_heat_ac');
			$edit->direct_pipeline_injection = postVar('direct_pipeline_injection');
			$edit->generation_ice = postVar('generation_ice');
			$edit->generation_turbine = postVar('generation_turbine');
			$edit->generation_microturbine = postVar('generation_microturbine');
			$edit->generation_fuel_cell = postVar('generation_fuel_cell');
			$edit->generation_2_grid = postVar('generation_2_grid');
			$edit->waste_import = postVar('waste_import');
			if(!count($badmsg)){
				$edit->insert();
				unset($edit);
				$goodmsg[]='Edit Submitted.  The facility will be updated when a moderator approves your edit.';
			}
		}else if($_POST['form']=='edit' && $Auth->isModerator()){
			if($_POST['submit']=='approve'){
				$edit = new Edit();
				$edit->select($_POST['id']);
				$edit->status = 'approved';
				$edit->facility_id = $_POST['facility_id'];
				$edit->FRS_ID = $_POST['FRS_ID'];
				$edit->name = $_POST['name'];
				$edit->authority = $_POST['authority'];
				$edit->facility_address = $_POST['facility_address'];
				$edit->facility_city = $_POST['facility_city'];
				$edit->facility_state = $_POST['facility_state'];
				$edit->facility_zip = $_POST['facility_zip'];
				$edit->facility_county = $_POST['facility_county'];
				$edit->facility_phone = preg_replace('#[^0-9]#', '', $_POST['facility_phone']); 
				$edit->facility_lat = $_POST['facility_lat'];
				$edit->facility_long = $_POST['facility_long'];
				$edit->flow_capacity = floatval(str_replace(",","",$_POST['flow_capacity']));
				$edit->flow_avg = floatval(str_replace(",","",$_POST['flow_avg']));
				$edit->flow_mgd = floatval(str_replace(",","",$_POST['flow_mgd']));
				$edit->flow_design = floatval(str_replace(",","",$_POST['flow_design']));
				$edit->flow_permit = floatval(str_replace(",","",$_POST['flow_permit']));
				$edit->universe = postVar('universe');
				$edit->avg_daily_biogas_production = $_POST['avg_daily_biogas_production'];
				$edit->flare = postVar('flare');
				$edit->stabilization = $_POST['stabilization'];
				$edit->anaerobic_digestion = postVar('anaerobic_digestion');
				$edit->ad_type = postVar('ad_type');
				$edit->biosolids_digester_gas_utilization = postVar('biosolids_digester_gas_utilization');
				$edit->beneficial_biosolids_use = postVar('beneficial_biosolids_use');
				$edit->biosolids_distribution = postVar('biosolids_distribution');
				$edit->url = $_POST['url'];
				$edit->methane_use_web = postVar('methane_use_web');
				$edit->beneficial_biosolids_reuse_web = postVar('beneficial_biosolids_reuse_web');
				$edit->wastewater_flow = floatval(str_replace(",","",$_POST['wastewater_flow']));
				$edit->sludge_production = floatval(str_replace(",","",$_POST['sludge_production']));
				$edit->sludge_use = floatval(str_replace(",","",$_POST['sludge_use']));
				$edit->sludge_disposal = floatval(str_replace(",","",$_POST['sludge_disposal']));
				$edit->sludge_incineration = floatval(str_replace(",","",$_POST['sludge_incineration']));
				$edit->sludge_disposed_unit = floatval(str_replace(",","",$_POST['sludge_disposed_unit']));
				$edit->sludge_disposed_landfill = floatval(str_replace(",","",$_POST['sludge_disposed_landfill']));
				$edit->biosolids_heat_recovery = postVar('biosolids_heat_recovery');
				$edit->drive_process_machinery = postVar('drive_process_machinery');
				$edit->digester_heating = postVar('digester_heating');
				$edit->building_heat_ac = postVar('building_heat_ac');
				$edit->direct_pipeline_injection = postVar('direct_pipeline_injection');
				$edit->generation_ice = postVar('generation_ice');
				$edit->generation_turbine = postVar('generation_turbine');
				$edit->generation_microturbine = postVar('generation_microturbine');
				$edit->generation_fuel_cell = postVar('generation_fuel_cell');
				$edit->generation_2_grid = postVar('generation_2_grid');
				$edit->waste_import = postVar('waste_import');
				$edit->update();
				
				$facility = new Facility();
				$facility->select($_POST['facility_id']);
				$facility->approved=$_POST['approved'];
				$facility->FRS_ID = $_POST['FRS_ID'];
				$facility->name = $_POST['name'];
				$facility->authority = $_POST['authority'];
				$facility->facility_address = $_POST['facility_address'];
				$facility->facility_city = $_POST['facility_city'];
				$facility->facility_state = $_POST['facility_state'];
				$facility->facility_zip = $_POST['facility_zip'];
				$facility->facility_county = $_POST['facility_county'];
				$facility->facility_phone = preg_replace('#[^0-9]#', '', $_POST['facility_phone']); 
				$facility->facility_lat = $_POST['facility_lat'];
				$facility->facility_long = $_POST['facility_long'];
				$facility->flow_capacity = floatval(str_replace(",","",$_POST['flow_capacity']));
				$facility->flow_avg = floatval(str_replace(",","",$_POST['flow_avg']));
				$facility->flow_mgd = floatval(str_replace(",","",$_POST['flow_mgd']));
				$facility->flow_design = floatval(str_replace(",","",$_POST['flow_design']));
				$facility->flow_permit = floatval(str_replace(",","",$_POST['flow_permit']));
				$facility->universe = postVar('universe');
				$facility->avg_daily_biogas_production = $_POST['avg_daily_biogas_production'];
				$facility->flare = postVar('flare');
				$facility->stabilization = $_POST['stabilization'];
				$facility->anaerobic_digestion = postVar('anaerobic_digestion');
				$facility->ad_type = postVar('ad_type');
				$facility->biosolids_digester_gas_utilization = postVar('biosolids_digester_gas_utilization');
				$facility->beneficial_biosolids_use = postVar('beneficial_biosolids_use');
				$facility->biosolids_distribution = postVar('biosolids_distribution');
				$facility->url = $_POST['url'];
				$facility->methane_use_web = postVar('methane_use_web');
				$facility->beneficial_biosolids_reuse_web = postVar('beneficial_biosolids_reuse_web');
				$facility->wastewater_flow = floatval(str_replace(",","",$_POST['wastewater_flow']));
				$facility->sludge_production = floatval(str_replace(",","",$_POST['sludge_production']));
				$facility->sludge_use = floatval(str_replace(",","",$_POST['sludge_use']));
				$facility->sludge_disposal = floatval(str_replace(",","",$_POST['sludge_disposal']));
				$facility->sludge_incineration = floatval(str_replace(",","",$_POST['sludge_incineration']));
				$facility->sludge_disposed_unit = floatval(str_replace(",","",$_POST['sludge_disposed_unit']));
				$facility->sludge_disposed_landfill = floatval(str_replace(",","",$_POST['sludge_disposed_landfill']));
				$facility->biosolids_heat_recovery = postVar('biosolids_heat_recovery');
				$facility->drive_process_machinery = postVar('drive_process_machinery');
				$facility->digester_heating = postVar('digester_heating');
				$facility->building_heat_ac = postVar('building_heat_ac');
				$facility->direct_pipeline_injection = postVar('direct_pipeline_injection');
				$facility->generation_ice = postVar('generation_ice');
				$facility->generation_turbine = postVar('generation_turbine');
				$facility->generation_microturbine = postVar('generation_microturbine');
				$facility->generation_fuel_cell = postVar('generation_fuel_cell');
				$facility->generation_2_grid = postVar('generation_2_grid');
				$facility->waste_import = postVar('waste_import');
				$facility->update();
				$goodmsg[]='Edit Approved';
			}elseif($_POST['submit']=='draft'){
				$edit = new Edit();
				$edit->select($_POST['id']);
				$edit->status = 'draft';
				$edit->facility_id = $_POST['facility_id'];
				$edit->user_id = $Auth->user->id;
				$edit->FRS_ID = $_POST['FRS_ID'];
				$edit->name = postVar('name');
				$edit->authority = $_POST['authority'];
				$edit->facility_address = $_POST['facility_address'];
				$edit->facility_city = $_POST['facility_city'];
				$edit->facility_state = $_POST['facility_state'];
				$edit->facility_zip = $_POST['facility_zip'];
				$edit->facility_county = $_POST['facility_county'];
				$edit->facility_phone = preg_replace('#[^0-9]#', '', $_POST['facility_phone']); 
				$edit->facility_lat = $_POST['facility_lat'];
				$edit->facility_long = $_POST['facility_long'];
				$edit->flow_capacity = floatval(str_replace(",","",$_POST['flow_capacity']));
				$edit->flow_avg = floatval(str_replace(",","",$_POST['flow_avg']));
				$edit->flow_mgd = floatval(str_replace(",","",$_POST['flow_mgd']));
				$edit->flow_design = floatval(str_replace(",","",$_POST['flow_design']));
				$edit->flow_permit = floatval(str_replace(",","",$_POST['flow_permit']));
				$edit->universe = postVar('universe');
				$edit->avg_daily_biogas_production = $_POST['avg_daily_biogas_production'];
				$edit->flare = postVar('flare');
				$edit->stabilization = $_POST['stabilization'];
				$edit->anaerobic_digestion = postVar('anaerobic_digestion');
				$edit->ad_type = postVar('ad_type');
				$edit->biosolids_digester_gas_utilization = postVar('biosolids_digester_gas_utilization');
				$edit->beneficial_biosolids_use = postVar('beneficial_biosolids_use');
				$edit->biosolids_distribution = postVar('biosolids_distribution');
				$edit->url = $_POST['url'];
				$edit->methane_use_web = postVar('methane_use_web');
				$edit->beneficial_biosolids_reuse_web = postVar('beneficial_biosolids_reuse_web');
				$edit->wastewater_flow = floatval(str_replace(",","",$_POST['wastewater_flow']));
				$edit->sludge_production = floatval(str_replace(",","",$_POST['sludge_production']));
				$edit->sludge_use = floatval(str_replace(",","",$_POST['sludge_use']));
				$edit->sludge_disposal = floatval(str_replace(",","",$_POST['sludge_disposal']));
				$edit->sludge_incineration = floatval(str_replace(",","",$_POST['sludge_incineration']));
				$edit->sludge_disposed_unit = floatval(str_replace(",","",$_POST['sludge_disposed_unit']));
				$edit->sludge_disposed_landfill = floatval(str_replace(",","",$_POST['sludge_disposed_landfill']));
				$edit->biosolids_heat_recovery = postVar('biosolids_heat_recovery');
				$edit->drive_process_machinery = postVar('drive_process_machinery');
				$edit->digester_heating = postVar('digester_heating');
				$edit->building_heat_ac = postVar('building_heat_ac');
				$edit->direct_pipeline_injection = postVar('direct_pipeline_injection');
				$edit->generation_ice = postVar('generation_ice');
				$edit->generation_turbine = postVar('generation_turbine');
				$edit->generation_microturbine = postVar('generation_microturbine');
				$edit->generation_fuel_cell = postVar('generation_fuel_cell');
				$edit->generation_2_grid = postVar('generation_2_grid');
				$edit->waste_import = postVar('waste_import');
				$edit->update();
				
				$goodmsg[]='Edit Updated';
			}elseif($_POST['submit']=='reject'){
				$edit = new Edit();
				$edit->select($_POST['id']);
				$edit->status = 'rejected';
				$edit->update();
				$goodmsg[]='Edit rejected';
			}
		}
	}
}
if(!empty($_GET) && $Auth->loggedIn()){
	if(!empty($_GET['deleteContact'])){
		$contact = new Contact();
		$contact->select(intval($_GET['deleteContact']));
		if(($contact->added_by == $Auth->user->id || $Auth->isModerator()) && $contact->ok()){
			$contact->delete();
			redirect($_SERVER['HTTP_REFERER']);
		}
	}
}
?>