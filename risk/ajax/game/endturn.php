<?php
	if(!defined('AJAX')) {
		die;
	}
	if($game['state'] == 2) {
		$query = $db->simple_select('EE_risk_attacks','1','gid = '.$gid);
		if($db->fetch_array($query)) {
			die('ERROR: You cannot end your turn without finishing all battles.');
		}
	}
	$cards = '';
	if($game['conquered']) {
		$card = -1;
		if(isset($mybb->input['card'])) {
			$card = $mybb->input['card'];
			if($card != 'c_art' && $card != 'c_inf' && $card != 'c_cav' && $card != 'c_jok') {
				$card = -1;
			}
		}
		$query = $db->simple_select('EE_risk_gamesjoined','c_art,c_inf,c_cav,c_jok','gid = '.$gid.' AND uid = '.$mybb->user['uid']);
		$cards = $db->fetch_array($query);
		$numCards = $cards['c_art']+$cards['c_inf']+$cards['c_cav']+$cards['c_jok'];
		if($card != -1 || $numCards < 5) {
			$query = $db->simple_select('EE_risk_gamesjoined','SUM(c_jok) AS wild','gid = '.$gid);
			$wildcards = $db->fetch_field($query,'wild');
			$posCards = array('c_art','c_art','c_art','c_inf','c_inf','c_inf','c_cav','c_cav','c_cav');
			if($wildcards < 2) {
				$posCards[] = 'c_jok';
			}
			$newCard = $posCards[mt_rand(0,count($posCards)-1)];
			if($numCards > 4) {
				$db->update_query('EE_risk_gamesjoined',array(
					$card => $card.'-1',
					$newCard => $newCard.'+1'
				),'uid = '.$mybb->user['uid'].' AND gid = '.$gid,1,true);
				$cards[$card]--;
				$cards[$newCard]++;
			} else {
				$db->update_query('EE_risk_gamesjoined',array($newCard => $newCard.'+1'),'uid = '.$mybb->user['uid'].' AND gid = '.$gid,1,true);
				$cards[$newCard]++;
			}
			$cards = ', "cards": '.json_encode($cards).', "newcard": "'.$newCard.'"';
		} else {
			$cards = ', "cards": '.json_encode($cards).', "newcard": -1';
		}
	}
	require './game/maps/'.$game['map'].'/inc/mapdata.php';
	$query = $db->simple_select('EE_risk_nations','*','gid = '.$gid.' AND uid = '.$mybb->user['uid']);
	$nations = array();
	while($nation = $db->fetch_array($query)) {
		$nations[] = $nation;
	}
	$contCount = 0;
	foreach($mapdata['continents'] as $continent) {
		if(getContinent($continent,$nations)) {
			$contCount++;
		}
	}
	function getContinents($continents,$nations) {
		global $mapdata;
		foreach($continents as $continent) {
			if(!getContinent($mapdata['continents'][$continent],$nations)) {
				return false;
			}
		}
		return true;
	}
	function getNations($nationList,$nations) {
		foreach($nationList as $nation) {
			if(!getNation($nation,$nations)) {
				return false;
			}
		}
		return true;
	}
	$player = $players[indexOfPlayer($mybb->user['uid'],$players)];
	$victory = true;
	foreach($mapdata['missions'][$player['mission']]['conditions'] as $key => $condition) {
		if(	($key == 'eliminate' && $player['state'] != 2) ||
			($key == 'territories' && count($nations) < $condition) ||
			($key == 'continents' && $contCount < $condition) ||
			($key == 'continent' && !getContinents($condition,$nations)) ||
			($key == 'nation' && !getNations($condition,$nations))
		) {
			$victory = false;
			break;
		}
	}
	$turn = getNextTurn();
	if($victory) {
		victory($mybb->user['uid']);
	} else {
		$db->insert_query('EE_risk_combatlog',array(
			'gid' => $gid,
			'message' => $db->escape_string('<strong style="color: #'.$player['color'].'">'.$player['username'].'</strong>\'s turn has ended.'),
			'time' => TIME_NOW
		));
		$db->update_query('EE_risk_games',array(
			'turn' => $turn,
			'state' => 0,
			'time' => TIME_NOW,
			'units' => getContinentUnits($turn),
			'conquered' => 0
		),'gid = '.$gid);
	}
	$page = '{"time": '.TIME_NOW.', "turn": '.$turn.$cards.'}';
?>