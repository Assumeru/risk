<?php
	define('IN_MYBB',1);
	define('THIS_SCRIPT','EE_risk_game.php');
	$templatelist = 'EE-risk_page,EE-risk_game_after_player,EE-risk_game_after,EE-risk_game_before_error,EE-risk_game_before_leave,EE-risk_game_before_join,EE-risk_game_before';
	require_once '../global.php';
	require_once './inc/functions.php';
	require_once './inc/global.php';

	if(!isset($mybb->input['gid']) || !is_numeric($mybb->input['gid'])) {
		header('Location: '.EE_risk::$URL_GAMES);
		die;
	}
	$query = $db->simple_select('EE_risk_games','*','gid = '.((int)$mybb->input['gid']));
	if(!$game = $db->fetch_array($query)) {
		header('Location: '.EE_risk::$URL_GAMES);
		die;
	}

	function getChatHeader($after = false) {
		global $players,$mybb,$game;
		$canEdit = 'false';
		if($game['uid'] == $mybb->user['uid'] || is_super_admin($mybb->user['uid'])) {
			$canEdit = 'true';
		}
		$playerColors = array();
		foreach($players as $player) {
			$playerColors[$player['uid']] = array('color' => $player['color']);
		}
		$str = '';
		if(!$after) {
			$str .= '<script src="'.EE_risk::$URL.'/resources/pregame.js"></script>';
		}
		$str .= '<script src="'.EE_risk::$URL.'/resources/chat.js"></script>
		<script>jQuery(document).ready(function() {$';
		$str .= $after ? 'CHAT' : 'PREGAME';
		$str .= '.init('.$canEdit.','.$game['gid'].','.json_encode($playerColors).',true,\''.EE_risk::$URL.'/ajax.php\');})</script>
		<link rel="stylesheet" type="text/css" href="'.EE_risk::$URL.'/resources/chat.css" />';
		return $str;
	}

	function userInPlayers($user,$players) {
		foreach($players as $player) {
			if($player['uid'] == $user) {
				return true;
			}
		}
		return false;
	}

	function colorInUse($color,$players) {
		foreach($players as $player) {
			if($player['color'] == $color) {
				return true;
			}
		}
		return false;
	}

	$header = '';

	require_once './inc/colors.php';
	require_once './inc/maps.php';

	$query = $db->query('
			SELECT u.username,.u.uid,g.color,g.state,g.mission,g.m_uid
			FROM '.TABLE_PREFIX.'users AS u
			JOIN '.TABLE_PREFIX.'EE_risk_gamesjoined AS g ON(g.uid = u.uid)
			WHERE g.gid = '.$game['gid'].'
			ORDER BY u.username');
	$players = array();
	while($player = $db->fetch_array($query)) {
		$players[] = $player;
	}

	$page = '';
	if($game['state'] == 4) {
		require_once './game/maps/'.$game['map'].'/inc/mapdata.php';

		function missionDescriptionUser($uid,$players) {
			if($uid == 0) {
				return 0;
			}
			for($n=0,$length=count($players);$n < $length;$n++) {
				if($players[$n]['uid'] == $uid) {
					return $n;
				}
			}
		}

		$playerlist = '';
		foreach($players as $player) {
			$player['profilelink'] = build_profile_link('<span style="color: #'.$player['color'].'">'.$player['username'].'</span>',$player['uid']);
			$won = '';
			if($player['state'] == 3) {
				$won = ' (Winner)';
			}
			$mission = str_ireplace('<user>',$players[missionDescriptionUser($player['m_uid'],$players)]['username'],$mapdata['missions'][$player['mission']]['description']);
			eval('$playerlist .= "'.$templates->get('EE-risk_game_after_player',true,false).'";');
		}

		if(userInPlayers($mybb->user['uid'],$players)) {
			$header = getChatHeader(true);
		}

		eval('$page = "'.$templates->get('EE-risk_game_after',true,false).'";');
	} else if($game['turn'] == 0) {
		if(isset($mybb->input['game_lj'])) {
			if($mybb->input['game_lj'] == 'join' && !userInPlayers($mybb->user['uid'],$players)) {
				$error = '';
				if(!empty($game['password']) && (empty($mybb->input['game_invite']) || $mybb->input['game_invite'] != $game['password']) && (empty($mybb->input['game_password']) || $game['password'] != EE_risk::game_password_hash($mybb->input['game_password']))) {
					$error = 'The password you entered was incorrect.';
				} else {
					if(count($players) < $mapTypes[$game['map']]['players']) {
						if(isset($mybb->input['game_color']) && in_array($mybb->input['game_color'],$playerColors,TRUE) && !colorInUse($mybb->input['game_color'],$players)) {
							$db->insert_query('EE_risk_gamesjoined',array(
								'uid' => $mybb->user['uid'],
								'gid' => $game['gid'],
								'color' => $mybb->input['game_color']
							));
							header('Location: '.EE_risk::get_url_game($game['gid'],$game['name']));
							die;
						} else {
							$error = 'Please select a colour that is not yet in use.';
						}
					} else {
						$error = 'The game is full.';
					}
				}
				if(!empty($error)) {
					eval('$page .= "'.$templates->get('EE-risk_game_before_error',true,false).'";');
				}
			} else if($mybb->input['game_lj'] == 'leave' && userInPlayers($mybb->user['uid'],$players)) {
				if($mybb->user['uid'] == $game['uid']) {
					EE_risk::delete_game($game['gid']);
				} else {
					$db->delete_query('EE_risk_gamesjoined','uid = '.$mybb->user['uid'].' AND gid = '.$game['gid']);
				}
				header('Location: '.EE_risk::$URL_GAMES);
				die;
			}
		} else if(isset($mybb->input['game_start']) && $mybb->input['game_start'] == 'start' && $mybb->user['uid'] == $game['uid']) {
			$numPlayers = count($players);
			if($numPlayers == $mapTypes[$game['map']]['players']) {
				require_once './game/maps/'.$game['map'].'/inc/mapdata.php';
				$nations = array_keys($mapdata['nations']);
				$numNations = count($nations)/$numPlayers;
				shuffle($nations);
				$insert = array();
				for($n=0;$n < $numNations;$n++) {
					for($i=0;$i < $numPlayers;$i++) {
						$insert[] = array(
							'gid' => $game['gid'],
							'nation' => $nations[$n*$numPlayers+$i],
							'uid' => $players[$i]['uid'],
							'units' => 3
						);
					}
				}
				$db->insert_query_multiple('EE_risk_nations',$insert);
				$db->update_query('EE_risk_games',array('turn' => $players[mt_rand(0,$numPlayers-1)]['uid']),'gid = '.$game['gid']);
				shuffle($mapdata['mission_distribution']);
				foreach($players as $player) {
					$mNo = array_pop($mapdata['mission_distribution']);
					$update = array('mission' => $mNo);
					if(isset($mapdata['missions'][$mNo]['conditions']['eliminate'])) {
						$mUser = mt_rand(0,$numPlayers-2);
						if($players[$mUser]['uid'] == $player['uid']) {
							$mUser = $numPlayers-1;
						}
						$update['m_uid'] = $players[$mUser]['uid'];
					}
					$db->update_query('EE_risk_gamesjoined',$update,'uid = '.$player['uid'].' AND gid = '.$game['gid']);
				}
				header('Location: '.EE_risk::get_url_game($game['gid'],$game['name'],true));
				die;
			} else {
				$error = 'Not enough players.';
				eval('$page .= "'.$templates->get('EE-risk_game_before_error',true,false).'";');
			}
		}
		if(userInPlayers($mybb->user['uid'],$players)) {
			$header = getChatHeader();
		}
		require './ajax/pregame_output.php';
		$updating = $out;
		$invitelink = '';
		if($mybb->user['uid'] == $game['uid'] && !empty($game['password'])) {
			$invitelink = EE_risk::get_url_game($game['gid'],$game['name']);
			$invitelink .= strpos($invitelink,'?') === false ? '?' : '&amp;';
			$invitelink .= 'invite='.$game['password'];
			eval('$invitelink = "'.$templates->get('EE-risk_game_before_invitelink',true,false).'";');
		}
		eval('$page .= "'.$templates->get('EE-risk_game_before',true,false).'";');
	} else {
		header('Location: '.EE_risk::get_url_game($game['gid'],$game['name'],true));
		die;
	}

	EE_risk::output_page($page,$game['name'],$header);
?>