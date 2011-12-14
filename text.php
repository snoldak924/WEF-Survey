<?php 
$fileName = 'facilities.csv';
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header('Content-Description: File Transfer');
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename={$fileName}");
header("Expires: 0");
header("Pragma: public");
$fh = @fopen( 'php://output', 'w' ); 
require 'includes/master.inc.php';
$db = Database::getDatabase();
$results = $db->getRows("SELECT * FROM facilities WHERE facility_state='".mysql_real_escape_string($_GET['state'])."'".((!empty($county) && $county!='all')?" AND facility_county='$county'":'').' ORDER BY name ASC');
fputcsv($fh, array_keys($results[0]));
foreach ( $results as $data ) {
	fputcsv($fh, $data);
}
fclose($fh);