<?php
	define('IN_MYBB',1);
	define('THIS_SCRIPT','EE_risk_newgame.php');
	$templatelist = 'EE-risk_page,EE-risk_newgame';
	require_once '../global.php';
	require_once './inc/functions.php';
	require_once './inc/global.php';
	require_once './inc/colors.php';
	require_once './inc/maps.php';

	$maps = '';
	$colors = '';
	$gameName = $mybb->user['username'].'\'s Game';
	$gamePassword = '';
	$gameMap = 0;
	$gameColor = $playerColors[0];
	$errors = '';

	if($mybb->request_method == 'post' && isset($mybb->input['newgame_name']) && isset($mybb->input['newgame_map']) && isset($mybb->input['newgame_color'])) {
		$gameName = trim($mybb->input['newgame_name']);
		$gameMap = (int)$mybb->input['newgame_map'];
		$gameColor = $mybb->input['newgame_color'];
		$gamePassword = $mybb->input['newgame_password'];
		if(my_strlen($gameName) < 4 || my_strlen($gameName) > 128) {
			$errors .= '<li>Please enter a valid game name.</li>';
		}
		if(!isset($mapTypes[$gameMap])) {
			$errors .= '<li>Please select a valid map.</li>';
		}
		if(!in_array($gameColor,$playerColors,true)) {
			$errors .= '<li>Please select a predefined color.</li>';
		}
		if(empty($errors)) {
			$insert = array(
				'map' => $gameMap,
				'name' => EE_risk::clean($gameName),
				'uid' => $mybb->user['uid'],
				'time' => TIME_NOW
			);
			if(!empty($gamePassword)) {
				$insert['password'] = EE_risk::game_password_hash($gamePassword);
			}
			$db->insert_query('EE_risk_games',$insert);
			$ins = $db->insert_id();
			$db->insert_query('EE_risk_gamesjoined',array(
				'uid' => $mybb->user['uid'],
				'gid' => $ins,
				'color' => $gameColor
			));
			header('Location: '.EE_risk::get_url_game($ins,$gameName));
			die;
		} else {
			$errors = '<ul class="error">'.$errors.'</ul>';
		}
	}

	$maps = '';
	$mapList = EE_risk::getSortedMaps($mapTypes);
	foreach($mapList as $map) {
		$maps .= '<option value="'.$map['mid'].'"';
		if($gameMap == $map['mid']) {
			$maps .= ' selected';
		}
		$maps .= '>'.$map['name'].' - '.$map['players'].' players</option>';
	}

	$colors = '';
	foreach($playerColors as $color) {
		$colors .= '<option value="'.$color.'" style="color: #'.$color.';"';
		if($gameColor == $color) {
			$colors .= ' selected';
		}
		$colors .= '>'.$mybb->user['username'].'</option>';
	}

	eval('$page = "'.$templates->get('EE-risk_newgame',true,false).'";');
	EE_risk::output_page($page,'New Game');
?>