<?php
	if(!isset($_POST['mode']) && !isset($_GET['mode'])) {
		die;
	}
	define('IN_MYBB',1);
	define('THIS_SCRIPT','EE_risk_ajax.php');
	define('AJAX',true);

	require_once '../global.php';
	require_once './inc/functions.php';
	require_once './inc/global.php';

	function indexOfPlayer($user,$players) {
		for($n=0,$length=count($players);$n < $length;$n++) {
			if($players[$n]['uid'] == $user) {
				return $n;
			}
		}
		return -1;
	}
	
	function userInPlayers($user,&$players) {
		return indexOfPlayer($user,$players) !== -1;
	}

	$page = '';
	if($mybb->request_method == 'post' && isset($mybb->input['mode'])) {
		if(isset($mybb->input['gid']) && is_numeric($mybb->input['gid'])) {
			$gid = (int)$mybb->input['gid'];
			if($mybb->input['mode'] == 'chat') {
				require './ajax/chat.php';
			} else if(isset($mybb->input['action'])) {
				if($mybb->input['mode'] == 'game') {
					require './ajax/game.php';
				} else if($mybb->input['mode'] == 'pregame') {
					require './ajax/pregame.php';
				}
			}
		}
	} else if($mybb->request_method == 'get' && isset($mybb->input['mode'])) {
		if($mybb->input['mode'] == 'game' && isset($mybb->input['action']) && $mybb->input['action'] == 'getgui') {
			eval('$page .= "'.$templates->get('EE-risk_game_gui',true,false).'";');
		}
	} else {
		header('Location: '.EE_risk::$URL);
	}
	if(!empty($page)) {
		EE_risk::output($page);
	}
?>