<?php
	if(!defined('AJAX')) {
		die;
	}
	if($gid < 1) {
		die('ERROR: Game does not exist.');
	}

	$query = $db->query('SELECT u.uid,u.username,g.color,g.autoroll,g.mission,g.m_uid,g.state
			FROM '.TABLE_PREFIX.'users AS u
			JOIN '.TABLE_PREFIX.'EE_risk_gamesjoined AS g ON(g.uid = u.uid)
			WHERE g.gid = '.$gid.'
			ORDER BY u.username');
	$players = array();
	while($player = $db->fetch_array($query)) {
		$players[] = $player;
	}
	if($mybb->input['action'] != 'update' && !userInPlayers($mybb->user['uid'],$players)) {
		die('ERROR: You have not joined this game.');
	}

	$query = $db->simple_select('EE_risk_games','*','gid = '.$gid);
	$game = $db->fetch_array($query);
	if($game['turn'] == 0) {
		die('The game has ended.');
	}

	if($mybb->input['action'] == 'defend' && isset($mybb->input['a_nat']) && isset($mybb->input['d_nat']) && isset($mybb->input['attacker']) && is_numeric($mybb->input['attacker']) && isset($mybb->input['units'])) {
		require './ajax/game/defend.php';
	} else if($mybb->input['action'] == 'update' && isset($mybb->input['time']) && is_numeric($mybb->input['time']) && $mybb->input['time'] != $game['time']) {
		require './ajax/game/update.php';
	} else if($mybb->input['action'] == 'autoroll') {
		require './ajax/game/autoroll.php';
	} else if($mybb->input['action'] == 'forfeit' && $players[indexOfPlayer($mybb->user['uid'],$players)]['state'] != 1) {
		require './ajax/game/forfeit.php';
	}
	if($mybb->input['action'] == 'defend' || $mybb->input['action'] == 'update' || $mybb->input['action'] == 'autoroll' || $mybb->input['action'] == 'forfeit') {
		return;
	}

	if($mybb->user['uid'] != $game['turn']) {
		die('ERROR: It\'s not your turn.');
	}

	function victory($uid) {
		global $db,$gid,$players;

		$db->delete_query('EE_risk_combatlog','gid = '.$gid);
		$db->delete_query('EE_risk_nations','gid = '.$gid);
		$db->update_query('EE_risk_gamesjoined',array('state' => 3),'gid = '.$gid.' AND uid = '.$uid);
		$db->update_query('EE_risk_games',array(
			'turn' => 0,
			'state' => 4,
			'time' => TIME_NOW
		),'gid = '.$gid);
		$scores = array();
		$uids = array();
		foreach($players as $player) {
			$uids[] = $player['uid'];
		}
		$query = $db->simple_select('EE_risk_users','uid','uid IN('.implode(',',$uids).')');
		while($score = $db->fetch_array($query)) {
			$scores[$score['uid']] = true;
		}
		foreach($players as $player) {
			if(!isset($scores[$player['uid']])) {
				$db->insert_query('EE_risk_users',array('uid' => $player['uid']));
			}
			$update = array();
			if($player['uid'] != $uid) {
				$update['losses'] = 'losses+1';
				$update['score'] = 'score-1';
			} else {
				$update['wins'] = 'wins+1';
				$update['score'] = 'score+'.(count($players)-1);
			}
			$db->update_query('EE_risk_users',$update,'uid = '.$player['uid'],1,true);
		}
	}

	function getNextTurn() {
		global $players,$mybb;
		$mod = count($players);
		$p = indexOfPlayer($mybb->user['uid'],$players);
		$next = $players[$p];
		do {
			$p = ($p+1)%$mod;
			$next = $players[$p];
		} while($next['state'] == 1);
		return $next['uid'];
	}
	
	function getContinentUnits($uid) {
		global $gid,$game,$db;
		$query = $db->simple_select('EE_risk_nations','*','gid = '.$gid.' AND uid = '.$uid);
		$nations = array();
		while($nation = $db->fetch_array($query)) {
			$nations[] = $nation;
		}
		require './game/maps/'.$game['map'].'/inc/mapdata.php';
		$upert = 0;
		foreach($mapdata['continents'] as $continent) {
			if(getContinent($continent,$nations)) {
				$upert += $continent['units'];
			}
		}
		return $upert;
	}
	
	function getNation($nation,$nations) {
		foreach($nations as $nat) {
			if($nat['nation'] == $nation) {
				return true;
			}
		}
		return false;
	}
	
	function getContinent($continent,$nations) {
		foreach($continent['nations'] as $nation) {
			if(!getNation($nation,$nations)) {
				return false;
			}
		}
		return true;
	}

	if($mybb->input['action'] == 'stack' && $game['state'] == 0) {
		require './ajax/game/stack.php';
	} else if($mybb->input['action'] == 'playcards' && $game['state'] < 2 && isset($mybb->input['combination']) && is_numeric($mybb->input['combination'])) {
		require './ajax/game/playcards.php';
	} else if($mybb->input['action'] == 'place_units' && isset($mybb->input['units']) && is_numeric($mybb->input['units']) && isset($mybb->input['nation']) && $game['state'] < 2 && $game['units'] > 0) {
		require './ajax/game/place_units.php';
	} else if($mybb->input['action'] == 'attack' && isset($mybb->input['units']) && is_numeric($mybb->input['units']) && isset($mybb->input['attacker']) && isset($mybb->input['defender']) && isset($mybb->input['transfer_units']) && is_numeric($mybb->input['transfer_units'])) {
		require './ajax/game/attack.php';
	} else if($mybb->input['action'] == 'start_move' && $game['state'] == 2) {
		require './ajax/game/start_move.php';
	} else if($mybb->input['action'] == 'move_units' && isset($mybb->input['to']) && isset($mybb->input['from']) && isset($mybb->input['units']) && is_numeric($mybb->input['units'])) {
		require './ajax/game/move_units.php';
	} else if($mybb->input['action'] == 'endturn') {
		require './ajax/game/endturn.php';
	}
?>