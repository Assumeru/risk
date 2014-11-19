<?php
	if(!defined('AJAX')) {
		die;
	}

	$units = (int)$mybb->input['units'];
	if($units != 1 && $units != 2) {
		die('ERROR: You cannot attack with '.$units.' units.');
	}
	$a_uid = (int)$mybb->input['attacker'];
	if($a_uid < 1 || !userInPlayers($a_uid,$players)) {
		die('ERROR: Invalid attacker.');
	}
	require './game/maps/'.$game['map'].'/inc/mapdata.php';
	if(!isset($mapdata['nations'][$mybb->input['a_nat']]) || !isset($mapdata['nations'][$mybb->input['d_nat']])) {
		die('ERROR: Territory does not exist.');
	}

	$query = $db->simple_select('EE_risk_attacks','*','gid = '.$gid.' AND d_uid = '.$mybb->user['uid'].' AND a_uid = '.$a_uid.' AND a_nation = "'.$mybb->input['a_nat'].'" AND d_nation = "'.$mybb->input['d_nat'].'"');
	if(!$roll = $db->fetch_array($query)) {
		die('ERROR: Attack not found.');
	}

	$query = $db->simple_select('EE_risk_nations','*','gid = '.$gid.' AND (nation = "'.$mybb->input['a_nat'].'" OR nation = "'.$mybb->input['d_nat'].'")',array('limit' => 2));
	while($nation = $db->fetch_array($query)) {
		if($nation['uid'] == $mybb->user['uid']) {
			$defender = $nation;
		} else {
			$attacker = $nation;
		}
	}

	$defendRoll = array(mt_rand(1,6));
	if($units == 2) {
		$defendRoll[] = mt_rand(1,6);
	}
	sort($defendRoll);
	$defendRoll = array_reverse($defendRoll);
	$defendLosses = 0;
	$attackLosses = 0;
	for($n=0;$n < $units && $n < strlen($roll['a_roll']);$n++) {
		if($defendRoll[$n] < $roll['a_roll'][$n]) {
			$defendLosses++;
		} else {
			$attackLosses++;
		}
	}
	$gameUpdate = array('time' => TIME_NOW);
	$attPlayer = $players[indexOfPlayer($attacker['uid'],$players)];
	$defPlayer = $players[indexOfPlayer($defender['uid'],$players)];
	$dbAttN = '<a href="#nation-'.$mybb->input['a_nat'].'" style="color: #'.$attPlayer['color'].'">'.$mapdata['nations'][$mybb->input['a_nat']]['name'].'</a>';
	$dbDefN = '<a href="#nation-'.$mybb->input['d_nat'].'" style="color: #'.$defPlayer['color'].'">'.$mapdata['nations'][$mybb->input['d_nat']]['name'].'</a>';
	$dbAttD = '';
	for($n=0;$n < strlen($roll['a_roll']);$n++) {
		$dbAttD .= '<div class="d'.$roll['a_roll'][$n].' die_attack die_inline"></div>';
	}
	$dbDefD = '';
	foreach($defendRoll as $die) {
		$dbDefD .= '<div class="d'.$die.' die_defend die_inline"></div>';
	}
	$db->insert_query('EE_risk_combatlog',array(
		'gid' => $gid,
		'message' => $db->escape_string($dbAttN.' vs '.$dbDefN.': '.$dbAttD.' '.$dbDefD),
		'time' => TIME_NOW
	));
	if($defendLosses == $defender['units']) {
		$db->insert_query('EE_risk_combatlog',array(
			'gid' => $gid,
			'message' => $db->escape_string('<strong style="color: #'.$attPlayer['color'].'">'.$attPlayer['username'].'</strong> has conquered '.$dbDefN),
			'time' => TIME_NOW
		));
		if($roll['transfer'] < 1) {
			$roll['transfer'] = 1;
		}
		if($roll['transfer'] >= $attacker['units']-$attackLosses) {
			$roll['transfer'] = $attacker['units']-$attackLosses-1;
		}
		$db->update_query('EE_risk_nations',array(
			'units' => $roll['transfer'],
			'uid' => $attacker['uid']
		),'gid = '.$gid.' AND nation = "'.$defender['nation'].'"');
		$db->update_query('EE_risk_nations',array('units' => $attacker['units']-$attackLosses-$roll['transfer']),'gid = '.$gid.' AND nation = "'.$attacker['nation'].'"');
		if(!$game['conquered']) {
			$gameUpdate['conquered'] = 1;
		}
		$query = $db->simple_select('EE_risk_nations','COUNT(gid) AS num','gid = '.$gid.' AND uid = '.$mybb->user['uid']);
		$numNations = $db->fetch_field($query,'num');
		if($numNations == 1) {
			$db->update_query('EE_risk_gamesjoined',array('state' => 1),'gid = '.$gid.' AND uid = '.$mybb->user['uid']);
			$m = -1;
			for($n=0,$length=count($mapdata['missions']);$n < $length;$n++) {
				if(isset($mapdata['missions'][$n]['conditions']['eliminate'])) {
					$m = $n;
					break;
				}
			}
			if($m != -1) {
				if($attPlayer['mission'] == $m && $attPlayer['m_id'] == $mybb->user['uid']) {
					$db->update_query('EE_risk_gamesjoined',array('state'=> 2),'gid = '.$gid.' AND uid = '.$attacker['uid']);
				} else {
					for($n=0,$length=count($players);$n < $length;$n++) {
						if($players[$n]['mission'] == $m && $players[$n]['m_id'] == $defPlayer['uid']) {
							$db->update_query('EE_risk_gamesjoined',array('mission' => $mapdata['missions'][$n]['fallback']),'gid = '.$gid.' AND uid = '.$players[$n]['uid']);
							break;
						}
					}
				}
			}
		}
	} else {
		if($defendLosses > 0) {
			$db->update_query('EE_risk_nations',array('units' => $defender['units']-$defendLosses),'gid = '.$gid.' AND nation = "'.$defender['nation'].'"');
		}
		if($attackLosses > 0) {
			$db->update_query('EE_risk_nations',array('units' => $attacker['units']-$attackLosses),'gid = '.$gid.' AND nation = "'.$attacker['nation'].'"');
		}
	}
	$db->update_query('EE_risk_games',$gameUpdate,'gid = '.$gid);
	$db->delete_query('EE_risk_attacks','gid = '.$gid.' AND d_uid = '.$mybb->user['uid'].' AND a_uid = '.$a_uid.' AND a_nation = "'.$mybb->input['a_nat'].'" AND d_nation = "'.$mybb->input['d_nat'].'"');

	$page .= '{"roll": '.json_encode($defendRoll).', "time": '.TIME_NOW.', "defendLosses": '.$defendLosses.', "attackLosses": '.$attackLosses.', "transfer": '.$roll['transfer'].'}';
?>