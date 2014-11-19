<?php
	if(!defined('AJAX')) {
		die;
	}
	$query = $db->simple_select('EE_risk_nations','COUNT(gid) AS num','gid = '.$gid.' AND uid = '.$mybb->user['uid']);
	$nations = $db->fetch_field($query,'num');

	$upert = floor($nations/3);
	if($upert < 3) {
		$upert = 3;
	}
	$upert += $game['units'];

	$db->update_query('EE_risk_games',array(
		'state' => 1,
		'units' => $upert
	),'gid = '.$gid);
	$page = '{"state": 1, "units": '.$upert.'}';
?>