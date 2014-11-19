<?php
	if(!defined('AJAX')) {
		die;
	}
	if($game['state'] == 2) {
		$query = $db->simple_select('EE_risk_attacks','1','gid = '.$gid.' AND d_uid = '.$mybb->user['uid']);
		if($db->fetch_array($query)) {
			die('ERROR: You cannot forfeit without finishing all battles.');
		}
	}

	$p = indexOfPlayer($mybb->user['uid'],$players);

	$db->update_query('EE_risk_gamesjoined',array('state' => 1, 'autoroll' => 1),'gid = '.$gid.' AND uid = '.$mybb->user['uid']);
	$db->insert_query('EE_risk_combatlog',array(
		'gid' => $gid,
		'message' => $db->escape_string('<strong style="color: #'.$players[$p]['color'].'">'.$players[$p]['username'].'</strong> has forfeited.'),
		'time' => TIME_NOW
	));

	$players[$p]['state'] = 1;
	$numRemainingPlayers = 0;
	$lastPlayer = $players[$p];
	foreach($players as $player) {
		if($player['state'] != 1) {
			$numRemainingPlayers++;
			$lastPlayer = $player;
		}
	}

	if($numRemainingPlayers < 2) {
		victory($lastPlayer['uid']);
	} else if($game['turn'] == $mybb->user['uid']) {
		$turn = getNextTurn();
		$db->update_query('EE_risk_games',array(
				'turn' => $turn,
				'state' => 0,
				'time' => TIME_NOW,
				'units' => getContinentUnits($turn),
				'conquered' => 0
		),'gid = '.$gid);
	}
?>