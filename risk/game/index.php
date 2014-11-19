<?php
	define('IN_MYBB',1);
	define('THIS_SCRIPT','EE_risk_game_index.php');
	$templatelist = 'EE_risk_game,EE_risk_page';
	require_once '../../global.php';
	require_once '../inc/functions.php';
	require_once '../inc/global.php';

	if(!isset($mybb->input['gid']) || !is_numeric($mybb->input['gid'])) {
		header('Location: '.EE_risk::$URL_GAMES);
		die;
	}
	$query = $db->simple_select('EE_risk_games','*','gid = '.((int)$mybb->input['gid']));
	if(!$game = $db->fetch_array($query)) {
		header('Location: '.EE_risk::$URL_GAMES);
		die;
	}
	if($game['turn'] == 0) {
		header('Location: '.EE_risk::get_url_game($game['gid'],$game['name']));
		die;
	}

	$query = $db->simple_select('EE_risk_gamesjoined','1','gid = '.$game['gid'].' AND uid = '.$mybb->user['uid']);
	$INGAME = false;
	if($db->fetch_array($query)) {
		$INGAME = true;
	}
	$chat = '';
	if($INGAME) {
		$chat = '<script src="'.$URL['base'].'/resources/chat.js"></script>';
	}
	$init = '$GAME.init('.$game['gid'].','.$mybb->user['uid'].',\''.EE_risk::$URL.'/\');';
	eval('$page .= "'.$templates->get('EE-risk_game',true,false).'";');
	EE_risk::output($page);
?>