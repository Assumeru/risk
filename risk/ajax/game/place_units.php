<?php
	if(!defined('AJAX')) {
		die;
	}
	$units = (int)$mybb->input['units'];
	if($units > $game['units']) {
		die('ERROR: You cannot place this many units.');
	}
	require './game/maps/'.$game['map'].'/inc/mapdata.php';
	if(!isset($mapdata['nations'][$mybb->input['nation']])) {
		die('ERROR: Territory does not exist.');
	}
	$query = $db->simple_select('EE_risk_nations','units','gid = '.$gid.' AND uid = '.$mybb->user['uid'].' AND nation = "'.$mybb->input['nation'].'"');
	if(!$nation = $db->fetch_array($query)) {
		die('ERROR: You do not own this territory.');
	}
	$nationu = $nation['units'] + $units;
	$db->update_query('EE_risk_nations',array('units' => $nationu),'gid = '.$gid.' AND uid = '.$mybb->user['uid'].' AND nation = "'.$mybb->input['nation'].'"');
	$db->update_query('EE_risk_games',array('units' => $game['units']-$units),'gid = '.$gid);
	$page = '{"units": '.($game['units']-$units).', "turn": '.$game['turn'].', "state": '.$game['state'].', "nat_units": '.$nationu.', "time": '.$game['time'].'}';
?>