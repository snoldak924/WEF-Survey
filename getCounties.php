<?php 
if(!empty($_GET['state'])){
	require 'includes/master.inc.php';
	echo get_options('facilities','facility_county','facility_county',null,' WHERE facility_state="'.mysql_escape_string($_GET['state']).'" GROUP BY facility_county');
}