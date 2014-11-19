<?php
	if(!defined('AJAX') && !defined('IN_MYBB')) {
		die;
	}

	$url = EE_risk::get_url_map($game['map'],$mapTypes[$game['map']]['name']);

	$playerList = '';
	foreach($players as $player) {
		$host = '';
		$kick = '';
		if($player['uid'] == $game['uid']) {
			$host = ' (host)';
		} else if($mybb->user['uid'] == $game['uid']) {
			eval('$kick = "'.$templates->get('EE-risk_game_before_player_kick',true,false).'";');
		}
		$player['profilelink'] = build_profile_link('<span style="color: #'.$player['color'].'">'.$player['username'].'</span>',$player['uid']);
		eval('$playerList .= "'.$templates->get('EE-risk_game_before_player',true,false).'";');
	}

	$leavejoin = '';
	if(userInPlayers($mybb->user['uid'],$players)) {
		$host = '';
		if($mybb->user['uid'] == $game['uid']) {
			eval('$host = "'.$templates->get('EE-risk_game_before_leave_host',true,false).'";');
		}
		eval('$leavejoin = "'.$templates->get('EE-risk_game_before_leave',true,false).'";');
	} else if(count($players) < $mapTypes[$game['map']]['players']) {
		$colors = '';
		foreach($playerColors as $color) {
			if(!colorInUse($color,$players)) {
				$colors .= '<option value="'.$color.'" style="color: #'.$color.';"';
				if(isset($mybb->input['game_color']) && $mybb->input['game_color'] == $color) {
					$colors .= ' selected';
				}
				$colors .= '>'.$mybb->user['username'].'</option>';
			}
		}
		$passwordField = '';
		if(!empty($game['password'])) {
			if(empty($mybb->input['invite']) || $mybb->input['invite'] != $game['password']) {
				eval('$passwordField = "'.$templates->get('EE-risk_game_before_password',true,false).'";');
			} else {
				$leavejoin = '<input type="hidden" value="'.$mybb->input['invite'].'" name="game_invite" />';
			}
		}
		eval('$leavejoin .= "'.$templates->get('EE-risk_game_before_join',true,false).'";');
	}

	$startgame = '';
	if($mybb->user['uid'] == $game['uid'] && count($players) == $mapTypes[$game['map']]['players']) {
		eval('$startgame = "'.$templates->get('EE-risk_game_before_start',true,false).'";');
	}

	eval('$out = "'.$templates->get('EE-risk_game_before_updating',true,false).'";');
?>