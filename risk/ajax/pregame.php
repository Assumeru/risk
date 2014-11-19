<?php
	if(!defined('AJAX')) {
		die;
	}

	$query = $db->simple_select('EE_risk_games','*','gid = '.$gid);
	if(!$game = $db->fetch_array($query)) {
		die('This game no longer exists.');
	}
	if($game['turn'] != 0) {
		die('The game has started.');
	}
	if($game['state'] == 4) {
		die('This game has ended.');
	}

	if($mybb->input['action'] == 'update') {
		require_once './inc/maps.php';
		require_once './inc/colors.php';
	
		$query = $db->query('
				SELECT u.uid,u.username,g.color
				FROM '.TABLE_PREFIX.'EE_risk_gamesjoined AS g
				JOIN '.TABLE_PREFIX.'users AS u ON(g.uid = u.uid)
				WHERE gid = '.$gid.'
				ORDER BY u.username');
		$players = array();
		while($player = $db->fetch_array($query)) {
			$players[] = $player;
		}
	
		function colorInUse($color,$players) {
			foreach($players as $player) {
				if($player['color'] == $color) {
					return true;
				}
			}
			return false;
		}
	
		require './ajax/pregame_output.php';
	
		$page = '{"page": '.json_encode($out).', "players": '.json_encode($players).'}';
	} else if($mybb->input['action'] == 'kick' && $mybb->user['uid'] == $game['uid'] && isset($mybb->input['uid']) && is_numeric($mybb->input['uid'])) {
		$uid = (int)$mybb->input['uid'];
		$db->delete_query('EE_risk_gamesjoined','gid = '.$game['gid'].' AND uid = '.$uid);
	}
?>