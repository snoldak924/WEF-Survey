<?php
    // Stick your DBOjbect subclasses in here (to help keep things tidy).

    class User extends DBObject
    {
        public function __construct($id = null)
        {
            parent::__construct('users', array('nid', 'email', 'password', 'level','name','organization','position','phone','parent_user_id'), $id);
        }
    }

	class Facility extends DBObject
    {
        public function __construct($id = null)
        {
            parent::__construct('facilities', array(
				'approved',
				'FRS_ID',
				'NPDES',
				'name',
				'authority',
				'facility_address',
				'facility_city',
				'facility_state',
				'facility_zip',
				'facility_county',
				'facility_phone',
				'facility_lat',
				'facility_long',
				'flow_capacity',
				'flow_avg',
				'flow_mgd',
				'flow_design',
				'flow_permit',
				'flare',
				'stabilization',
				'anaerobic_digestion',
				'ad_type',
				'biosolids_digester_gas_utilization',
				'beneficial_biosolids_use',
				'biosolids_distribution',
				'url',
				'methane_use_web',
				'beneficial_biosolids_reuse_web',
				'wastewater_flow',
				'biosolids_heat_recovery',
				'avg_daily_biogas_production',
				'sludge_production',
				'sludge_use',
				'sludge_incineration',
				'sludge_disposal',
				'sludge_disposed_unit',
				'sludge_disposed_landfill',
				'drive_process_machinery',
				'digester_heating',
				'building_heat_ac',
				'direct_pipeline_injection',
				'generation_ice',
				'generation_turbine',
				'generation_microturbine',
				'generation_fuel_cell',
				'generation_2_grid',
				'waste_import'
			), $id);
        }
    }
	class Edit extends DBObject
    {
        public function __construct($id = null)
        {
            parent::__construct('facilities_edits', array(
				'status',
				'facility_id',
				'user_id',
				'FRS_ID',
				'name',
				'authority',
				'facility_address',
				'facility_city',
				'facility_state',
				'facility_zip',
				'facility_county',
				'facility_phone',
				'facility_lat',
				'facility_long',
				'flow_capacity',
				'flow_avg',
				'flow_mgd',
				'flow_design',
				'flow_permit',
				'flare',
				'stabilization',
				'anaerobic_digestion',
				'ad_type',
				'biosolids_digester_gas_utilization',
				'beneficial_biosolids_use',
				'biosolids_distribution',
				'url',
				'methane_use_web',
				'beneficial_biosolids_reuse_web',
				'wastewater_flow',
				'biosolids_heat_recovery',
				'avg_daily_biogas_production',
				'created',
				'sludge_production',
				'sludge_use',
				'sludge_incineration',
				'sludge_disposal',
				'sludge_disposed_unit',
				'sludge_disposed_landfill',
				'drive_process_machinery',
				'digester_heating',
				'building_heat_ac',
				'direct_pipeline_injection',
				'generation_ice',
				'generation_turbine',
				'generation_microturbine',
				'generation_fuel_cell',
				'generation_2_grid',
				'waste_import'
			), $id);
        }
    }
	class Comment extends DBObject
    {
        public function __construct($id = null)
        {
            parent::__construct('comments', array('user_id','facility_id','comment','created','user_name','user_email'), $id);
        }
    }
	class Contact extends DBObject
    {
        public function __construct($id = null)
        {
            parent::__construct('contacts', array('facility_id','name','phone_w','phone_c','fax','address','address2','city','state','zip','email','organization','position','notes','added_by'), $id);
        }
    }