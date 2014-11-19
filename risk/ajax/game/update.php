<?php
	if(!defined('AJAX')) {
		die;
	}

	$query = $db->simple_select('EE_risk_nations','nation,uid,units','gid = '.$gid,array('order_by' => 'nation'));
	$nations = array();
	while($nation = $db->fetch_array($query)) {
		$nations[] = $nation;
	}

	require './game/maps/'.$game['map'].'/inc/mapdata.php';
	$page = '{"GAME": '.json_encode($game,JSON_NUMERIC_CHECK).', "NATIONS": '.json_encode($nations,JSON_NUMERIC_CHECK);

	if(userInPlayers($mybb->user['uid'],$players)) {
		$query = $db->simple_select('EE_risk_attacks','a_nation,d_nation,a_uid,d_uid,a_roll','gid = '.$gid);
		$attacks = array();
		while($attack = $db->fetch_array($query)) {
			$attacks[] = $attack;
		}
		$query = $db->simple_select('EE_risk_gamesjoined','c_art, c_inf, c_cav, c_jok, mission, m_uid','gid = '.$gid.' AND uid = '.$mybb->user['uid']);
		$gamesjoined = $db->fetch_array($query);

		$mission = array('mission' => $gamesjoined['mission'], 'm_uid' => $gamesjoined['m_uid']);
		$cards = array('c_art' => (int)$gamesjoined['c_art'], 'c_inf' => (int)$gamesjoined['c_inf'], 'c_cav' => (int)$gamesjoined['c_cav'], 'c_jok' => (int)$gamesjoined['c_jok']);

		$options = array('order_by' => 'time');
		if(isset($mybb->input['logs']) && is_numeric($mybb->input['logs']) && $mybb->input['logs'] > 0) {
			$options['limit'] = 100;
			$options['limit_start'] = (int)$mybb->input['logs'];
		}
		$query = $db->simple_select('EE_risk_combatlog','message,time','gid = '.$gid,$options);
		$combatLog = array();
		while($log = $db->fetch_array($query)) {
			$combatLog[] = array('message' => $log['message'], 'time' => date(DATE_ATOM,$log['time']));
		}

		$page .= ', "ATTACKS": '.json_encode($attacks).
		', "CARDS": '.json_encode($cards).
		', "USER": {"m_uid": '.$mission['m_uid'].
		', "mission": '.json_encode($mapdata['missions'][$mission['mission']]).
		', "inGame": '.($players[indexOfPlayer($mybb->user['uid'],$players)]['state'] == 1 ? 'false' : 'true').'}'.
		', "combatlog": '.json_encode($combatLog);
	}
	if(isset($mybb->input['update']) && $mybb->input['update'] == 'all') {
		$outputPlayers = array();
		foreach($players as $player) {
			$outputPlayers[$player['uid']] = array(
				'uid' => $player['uid'],
				'displayname' => $player['username'],
				'color' => $player['color'],
				'autoroll' => !!$player['autoroll'],
				'url' => $mybb->settings['bburl'].'/'.get_profile_link($player['uid'])
			);
		}
		$page .= ', "PLAYERS": '.json_encode($outputPlayers).', "MAPDATA": {"NATIONS": '.json_encode($mapdata['nations']).', "CONTINENTS": '.json_encode($mapdata['continents']).'}';
	}
	$page .= '}';
?>