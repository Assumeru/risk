<?php
	define('IN_MYBB',1);
	define('THIS_SCRIPT','EE_risk_yourgames.php');

	$templatelist = 'EE-risk_games_game,EE-risk_page,EE-risk_games';

	require_once '../global.php';
	require_once './inc/functions.php';
	require_once './inc/global.php';
	require_once './inc/maps.php';

	//List games
	$query = $db->query('
			SELECT g.gid,g.name,g.map,COUNT(gj.uid) AS players,g.uid,g.state,g.turn,u.username,g.password
			FROM '.TABLE_PREFIX.'EE_risk_games AS g
			JOIN '.TABLE_PREFIX.'EE_risk_gamesjoined as gj ON(g.gid = gj.gid)
			JOIN '.TABLE_PREFIX.'users AS u ON(u.uid = g.uid)
			WHERE gj.gid IN(
				SELECT gid
				FROM '.TABLE_PREFIX.'EE_risk_gamesjoined
				WHERE uid = '.$mybb->user['uid'].' 
			)
			GROUP BY gj.gid
			ORDER BY map,players ASC');
	$numGames = 0;
	$games = '';
	while($game = $db->fetch_array($query)) {
		if(!empty($game['players'])) {
			$class = '';
			if($game['state'] == 4) {
				$class = 'game_ended';
			} else if($game['turn'] != 0) {
				$class = 'game_started';
			}
			$profilelink = build_profile_link($game['username'],$game['uid']);
			$mapName = $mapTypes[$game['map']]['name'];
			$maxPlayers = $mapTypes[$game['map']]['players'];
			$game['url'] = EE_risk::get_url_game($game['gid'],$game['name']);
			$game['mapurl'] = EE_risk::get_url_map($game['map'],$mapName);
			$passwordRequired = '';
			if(!empty($game['password'])) {
				eval('$passwordRequired = "'.$templates->get('EE-risk_games_game_password',true,false).'";');
			}
			eval('$games .= "'.$templates->get('EE-risk_games_game',true,false).'";');
			$numGames++;
		}
	}

	$header = '<script src="'.EE_risk::$URL.'/resources/chat.js"></script>
	<link rel="stylesheet" type="text/css" href="'.EE_risk::$URL.'/resources/chat.css" />
	<script>jQuery(document).ready(function() {$CHAT.init(';
	$header .= is_super_admin($mybb->user['uid']) ? 'true' : 'false';
	$header .= ');});</script>';

	$page = '';
	if($numGames == 0) {
		eval('$page = "'.$templates->get('EE-risk_games_nogames',true,false).'";');
	} else {
		eval('$page = "'.$templates->get('EE-risk_games',true,false).'";');
	}

	EE_risk::output_page($page,'Your Games',$header);
?>