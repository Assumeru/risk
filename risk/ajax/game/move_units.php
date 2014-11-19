<?php
	if(!defined('AJAX')) {
		die;
	}
	$units = (int)$mybb->input['units'];
	if($units < 1 || $units > $game['units']) {
		die('ERROR: You cannot transfer '.$units.' units.');
	}
	require './game/maps/'.$game['map'].'/inc/mapdata.php';
	if(!isset($mapdata['nations'][$mybb->input['to']]) || !isset($mapdata['nations'][$mybb->input['from']])) {
		die('ERROR: Territory does not exist.');
	}
	$canMove = false;
	foreach($mapdata['nations'][$mybb->input['from']]['borders'] as $borderNation) {
		if($borderNation == $mybb->input['to']) {
			$canMove = true;
			break;
		}
	}
	if(!$canMove) {
		die('ERROR: You cannot reach this territory from here.');
	}
	$query = $db->simple_select('EE_risk_nations','*','gid = '.$gid.' AND (nation = "'.$mybb->input['to'].'" OR nation = "'.$mybb->input['from'].'")',array('limit' => 2));
	while($nation = $db->fetch_array($query)) {
		if($nation['nation'] == $mybb->input['to']) {
			$to = $nation;
		} else {
			$from = $nation;
		}
	}
	if($to['uid'] != $mybb->user['uid'] || $from['uid'] != $mybb->user['uid']) {
		die('ERROR: You do not own both territories.');
	}
	if($units >= $from['units']) {
		die('ERROR: You cannot transfer this many units.');
	}
	$to['units'] += $units;
	$from['units'] -= $units;
	$game['units'] -= $units;
	$db->update_query('EE_risk_nations',array('units' => $to['units']),'nation = "'.$to['nation'].'" AND gid = '.$gid);
	$db->update_query('EE_risk_nations',array('units' => $from['units']),'nation = "'.$from['nation'].'" AND gid = '.$gid);
	$db->update_query('EE_risk_games',array(
		'time' => TIME_NOW,
		'units' => $game['units']
	),'gid = '.$gid);
	$page = '{"to": "'.$to['nation'].'", "from": "'.$from['nation'].'", "to_units": '.$to['units'].', "from_units": '.$from['units'].', "units": '.$game['units'].', "time": '.TIME_NOW.'}';
?>