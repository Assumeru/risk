<?php
	if(!defined('AJAX')) {
		die;
	}
	$query = $db->simple_select('EE_risk_attacks','1','gid = '.$gid,array('limit' => 1));
	if($db->fetch_array($query)) {
		die('ERROR: All battles need to finish before units can be moved.');
	}
	$db->update_query('EE_risk_games',array(
		'units' => 7,
		'state' => 3
	),'gid = '.$gid);
	$page = '{"state": 3, "units": 7}';
?>