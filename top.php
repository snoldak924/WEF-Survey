<?php 
if(empty($page))$page='';
$goodmsg=array();
$badmsg=array();
$states = array(
	"AL", "AK", "AZ", "AR", "CA", "CO", "CT", "DE", "DC", "FL",
	"GA", "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME",
	"MD", "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH",
	"NJ", "NM", "NY", "NC", "ND", "OH", "OK", "OR", "PA", "RI",
	"SC", "SD", "TN", "TX", "UT", "VT", "VA", "WA", "WV", "WI", "WY");
$EPARegions = array(
	"1" => array("CT","MA","ME","NH","RI","VT"),
	"2" => array("NJ","NY"),
	"3" => array("DC","DE", "MD", "PA", "VA", "WV"),
	"4" => array("AL", "FL", "GA", "KY", "MS", "NC", "SC", "TN"),
	"5" => array("IL","IN","MI","MN","OH","WI"),
	"6" => array("AR","LA","NM","OK","TX"),
	"7" => array("IA","KS","MO","NE"),
	"8" => array("CO","MT","ND","SD","UT","WY"),
	"9" => array("AZ","CA","HI","NV"),
	"10" => array("AK","ID","OR","WA")
	);
require 'includes/master.inc.php';
$db = Database::getDatabase();
require 'user_functions.php';
require 'process.php';
$county = array_key_exists('county',$_GET)?mysql_real_escape_string($_GET['county']):''; 
$state = array_key_exists('state',$_GET)?mysql_real_escape_string($_GET['state']):''; 
if(!empty($moderator)){
	$Auth->requireModerator();
}?><!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<meta name="author" content="Sam Oldak">
<title><?php echo ($Auth->isMABA()?'MABA':'WEF Biogas'); ?> Data Collection Tool</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
<link rel="stylesheet" type="text/css" href="/jquery-ui-1.8.16.custom.css" /><?php
if(!empty($_GET['state']) || $page=='Edits'){ ?>
	<link rel="stylesheet" type="text/css" href="/media/css/demo_table_jui.css" />
	<link rel="stylesheet" type="text/css" href="/extras/ColVis/media/css/ColVis.css" />
	<link rel="stylesheet" type="text/css" href="/extras/TableTools/media/css/TableTools_JUI.css" />
	<?php
} ?>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-20683824-4']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
</head>
<body>
<div id="container">
	<div id="header">
		<h1><a href="/" style="color:#333;text-decoration:none;"><?php echo ($Auth->isMABA()?'MABA':'WEF Biogas'); ?> Data Collection Tool</a></h1>
	</div><?php
	if($Auth->loggedIn()){ ?>
	<div id="navigation">
		<ul>
			<li><a href="/">Home</a></li><?php
			if($Auth->isModerator()){ ?>
				<li><a href="edits.php">Edits</a></li><?php
			} ?>
			<li><a href="myaccount.php">My Account</a></li>
			<li><a href="contact_us.php">Contact Us</a></li>
			<li><a href="logout.php">Log out</a></li>
		</ul>
	</div><?php
	} ?>
	<div id="content-container"><?php 
	if(!empty($goodmsg)){
		foreach($goodmsg as $msg){
			echo "<div class='good msg'>$msg</div>";
		}
	}
	if(!empty($badmsg)){
		foreach($badmsg as $msg){
			echo "<div class='bad msg'>$msg</div>";
		}
	} ?>
		<div id="content-wide">