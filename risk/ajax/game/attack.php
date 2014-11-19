<?php
	if(!defined('AJAX')) {
		die;
	}
	$units = (int)$mybb->input['units'];
	if($units < 1 || $units > 3) {
		die('ERROR: You cannot attack with '.$units.' units.');
	}
	require './game/maps/'.$game['map'].'/inc/mapdata.php';
	if(!isset($mapdata['nations'][$mybb->input['attacker']]) || !isset($mapdata['nations'][$mybb->input['defender']])) {
		die('ERROR: Territory does not exist.');
	}
	$canAttack = false;
	foreach($mapdata['nations'][$mybb->input['attacker']]['borders'] as $borderNation) {
		if($borderNation == $mybb->input['defender']) {
			$canAttack = true;
			break;
		}
	}
	if(!$canAttack) {
		die('ERROR: You cannot attack this territory from here.');
	}
	$query = $db->simple_select('EE_risk_attacks','1','gid = '.$gid.' AND (a_nation = "'.$mybb->input['attacker'].'" OR d_nation = "'.$mybb->input['defender'].'")');
	if($db->fetch_array($query)) {
		die('ERROR: One of these territories is already in combat.');
	}
	$query = $db->simple_select('EE_risk_nations','*','gid = '.$gid.' AND (nation = "'.$mybb->input['attacker'].'" OR nation = "'.$mybb->input['defender'].'")',array('limit' => 2));
	while($nation = $db->fetch_array($query)) {
		if($nation['uid'] == $mybb->user['uid']) {
			$attacker = $nation;
		} else {
			$defender = $nation;
		}
	}
	if($attacker['uid'] != $mybb->user['uid']) {
		die('ERROR: You can only attack from your own territories.');
	}
	if($defender['uid'] == $attacker['uid']) {
		die('ERROR: You cannot attack your own territories.');
	}
	if($attacker['units'] <= $units) {
		die('ERROR: You cannot attack with this many units.');
	}
	$attackRoll = array();
	for($n=0;$n < $units;$n++) {
		$attackRoll[] = mt_rand(1,6);
	}
	sort($attackRoll);
	$attackRoll = array_reverse($attackRoll);
	$defPlayer = $players[indexOfPlayer($defender['uid'],$players)];
	$attPlayer = $players[indexOfPlayer($attacker['uid'],$players)];
	$gameUpdate = array('time' => TIME_NOW, 'state' => 2);
	$defendRoll = array();
	$transferUnits = (int)$mybb->input['transfer_units'];
	$attackLosses = 0;
	$defendLosses = 0;
	if($defender['units'] == 1 || !!$defPlayer['autoroll'] || ($units == 1 && $attackRoll[0] == 1)) {
		$defendRoll[] = mt_rand(1,6);
		if($defender['units'] > 1) {
			if($units == 1 || ($attackRoll[0]+$attackRoll[1])/2 <= 3.5 || $attackRoll[1] < 4) {
				$defendRoll[] = mt_rand(1,6);
			}
		}
		sort($defendRoll);
		$defendRoll = array_reverse($defendRoll);

		for($n=0;$n < count($defendRoll) && $n < $units;$n++) {
			if($attackRoll[$n] > $defendRoll[$n]) {
				$defendLosses++;
			} else {
				$attackLosses++;
			}
		}
		$dbAttN = '<a href="#nation-'.$mybb->input['attacker'].'" style="color: #'.$attPlayer['color'].'">'.$mapdata['nations'][$mybb->input['attacker']]['name'].'</a>';
		$dbDefN = '<a href="#nation-'.$mybb->input['defender'].'" style="color: #'.$defPlayer['color'].'">'.$mapdata['nations'][$mybb->input['defender']]['name'].'</a>';
		$dbAttD = '';
		foreach($attackRoll as $die) {
			$dbAttD .= '<div class="d'.$die.' die_attack die_inline"></div>';
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
			if($transferUnits < 1) {
				$transferUnits = 1;
			}
			if($transferUnits >= $attacker['units']-$attackLosses) {
				$transferUnits = $attacker['units']-$attackLosses-1;
			}
			$db->update_query('EE_risk_nations',array(
				'units' => $transferUnits,
				'uid' => $attacker['uid']
			),'gid = '.$gid.' AND nation = "'.$defender['nation'].'"');
			$db->update_query('EE_risk_nations',array('units' => $attacker['units']-$attackLosses-$transferUnits),'gid = '.$gid.' AND nation = "'.$attacker['nation'].'"');
			if(!$game['conquered']) {
				$gameUpdate['conquered'] = 1;
			}
			$query = $db->simple_select('EE_risk_nations','COUNT(gid) AS num','gid = '.$gid.' AND uid = '.$defender['uid']);
			$numNations = $db->fetch_field($query,'num');
			if($numNations == 0) {
				$db->update_query('EE_risk_gamesjoined',array('state' => 1),'gid = '.$gid.' AND uid = '.$defender['uid']);
				$len = count($players);
				for($n=0,$length=count($mapdata['missions']);$n < $length;$n++) {
					if(isset($mapdata['missions'][$n]['conditions']['eliminate'])) {
						if($attPlayer['mission'] == $n && $attPlayer['m_uid'] == $defPlayer['uid']) {
							$db->update_query('EE_risk_gamesjoined',array('state'=> 2),'gid = '.$gid.' AND uid = '.$attacker['uid']);
						}
						for($i=0;$i < $len;$i++) {
							if($players[$i]['uid'] != $attacker['uid'] && $players[$i]['mission'] == $n && $players[$i]['m_uid'] == $defPlayer['uid']) {
								$db->update_query('EE_risk_gamesjoined',array('mission' => $mapdata['missions'][$n]['fallback']),'gid = '.$gid.' AND uid = '.$players[$i]['uid']);
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
	} else {
		$db->insert_query('EE_risk_attacks',array(
			'gid' => $gid,
			'a_nation' => $attacker['nation'],
			'd_nation' => $defender['nation'],
			'a_uid' => $attacker['uid'],
			'd_uid' => $defender['uid'],
			'a_roll' => implode('',$attackRoll),
			'transfer' => $transferUnits
		));
	}
	$db->update_query('EE_risk_games',$gameUpdate,'gid = '.$gid);
	$page = '{"attackroll": '.json_encode($attackRoll).', "defendroll": '.json_encode($defendRoll).', "time": '.TIME_NOW.', "state": 2, "defendLosses": '.$defendLosses.', "attackLosses": '.$attackLosses.', "transfer": '.$transferUnits.'}';
?>