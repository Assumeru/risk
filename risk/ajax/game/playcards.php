<?php
	if(!defined('AJAX')) {
		die;
	}
	$com = (int)$mybb->input['combination'];
	if($com != 4 && $com != 6 && $com != 8 && $com != 10) {
		die;
	}
	$query = $db->simple_select('EE_risk_gamesjoined','c_art,c_inf,c_cav,c_jok','gid = '.$gid.' AND uid = '.$mybb->user['uid']);
	$cards = $db->fetch_array($query);
	foreach($cards as $key=>$value) {
		$cards[$key] = (int)$value;
	}
	$numCards = $cards['c_art']+$cards['c_inf']+$cards['c_cav']+$cards['c_jok'];
	$oriCards = $cards;
	if($numCards < 3) {
		die('ERROR: Not enough cards.');
	}
	$units = $game['units']+$com;
	if($com != 10) {
		$index = 'c_art';
		if($com == 6) {
			$index = 'c_inf';
		} else if($com == 8) {
			$index = 'c_cav';
		}
		if($cards[$index] > 2) {
			$cards[$index] -= 3;
		} else if($cards[$index] == 2 && $cards['c_jok'] == 1) {
			$cards[$index] -= 2;
			$cards['c_jok']--;
		} else if($cards[$index] == 1 && $cards['c_jok'] == 2) {
			$cards[$index]--;
			$cards['c_jok'] -= 2;
		}
	} else {
		if($cards['c_art'] > 0 && $cards['c_inf'] > 0 && $cards['c_cav'] > 0) {
			$cards['c_art']--;
			$cards['c_inf']--;
			$cards['c_cav']--;
		} else if($cards['c_jok'] == 1) {
			$cards['c_jok'] -= 1;
			if($cards['c_art'] > 0 && $cards['c_inf'] > 0) {
				$cards['c_art']--;
				$cards['c_inf']--;
			} else if($cards['c_art'] > 0 && $cards['c_cav'] > 0) {
				$cards['c_art']--;
				$cards['c_cav']--;
			} else if($cards['c_inf'] > 0 && $cards['c_cav'] > 0) {
				$cards['c_inf']--;
				$cards['c_cav']--;
			}
		} else if($cards['c_jok'] == 2) {
			$cards['c_jok'] -= 2;
			if($cards['c_art'] > 0) {
				$cards['c_art']--;
			} else if($cards['c_inf'] > 0) {
				$cards['c_inf']--;
			} else {
				$cards['c_cav']--;
			}
		}
	}
	if($cards != $oriCards) {
		$chat = '';
		$alt = array('c_art' => 'Artillery', 'c_inf' => 'Infantry', 'c_cav' => 'Cavalry', 'c_jok' => 'Wildcard');
		foreach($cards as $key => $value) {
			while($oriCards[$key] > $cards[$key]) {
				$chat .= '<img src="'.EE_risk::$URL.'/resources/game/'.$key.'.png" alt="'.$alt[$key].'" class="card" style="height: 50px;" />';
				$oriCards[$key]--;
			}
		}
		$chat = '<strong style="color: #'.$players[indexOfPlayer($mybb->user['uid'],$players)]['color'].'">'.$mybb->user['username'].'</strong> played '.$chat.' for '.$com.' units.';
		$db->update_query('EE_risk_gamesjoined',array(
			'c_art' => $cards['c_art'],
			'c_inf' => $cards['c_inf'],
			'c_cav' => $cards['c_cav'],
			'c_jok' => $cards['c_jok']
		),'gid = '.$gid.' AND uid = '.$mybb->user['uid']);
		$db->insert_query('EE_risk_combatlog',array(
			'gid' => $gid,
			'message' => $db->escape_string($chat),
			'time' => TIME_NOW
		));
		$db->update_query('EE_risk_games',array('units' => $units),'gid = '.$gid);
		$page = '{"units": '.$units.', "cards": '.json_encode($cards).'}';
	} else {
		$page = 'ERROR: Invalid combination.';
	}
?>