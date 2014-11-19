<?php
	if(!defined('AJAX')) {
		die;
	}

	if($gid != 0) {
		$query = $db->query('SELECT 1 FROM '.TABLE_PREFIX.'EE_risk_gamesjoined WHERE gid = '.$gid.' AND uid = '.$mybb->user['uid']);
		if(!$db->fetch_array($query)) {
			die('ERROR: You have not joined this game.');
		}
	}
	if(isset($mybb->input['msg'])) {
		$msg = trim($mybb->input['msg']);
		if(my_strlen($msg) > 255) {
			die('ERROR: Message too long.');
		}
		$db->insert_query('EE_risk_chat',array(
			'gid' => $gid,
			'uid' => $mybb->user['uid'],
			'message' => EE_risk::clean($msg),
			'time' => TIME_NOW
		));
		$page = 'SUCCESS';
	} else if(isset($mybb->input['update']) && is_numeric($mybb->input['update'])) {
		$limit = '';
		if($mybb->input['update'] > 0) {
			$limit = ' LIMIT '.(int)$mybb->input['update'].',100';
		}
		$query = $db->query('SELECT c.uid,u.username,c.message,c.time
				FROM '.TABLE_PREFIX.'EE_risk_chat AS c
				JOIN '.TABLE_PREFIX.'users AS u ON(c.uid = u.uid)
				WHERE c.gid = '.$gid.'
				ORDER BY c.time'.$limit);

		$page = '[';
		$first = true;
		while($chat = $db->fetch_array($query)) {
			if(!$first) {
				$page .= ', ';
			} else {
				$first = false;
			}
			$page .= '{"uid": '.$chat['uid'].', "name": '.json_encode($chat['username']).', "url": '.json_encode($mybb->settings['bburl'].'/'.get_profile_link($chat['uid'])).', "time": "'.date(DATE_ATOM,$chat['time']).'", "message": '.json_encode($chat['message']).'}';
		}
		$page .= ']';
	} else if(isset($mybb->input['uid']) && isset($mybb->input['time']) && is_numeric($mybb->input['uid']) && is_numeric($mybb->input['time'])) {
		$uid = 0;
		if(!is_super_admin($mybb->user['uid'])) {
			$query = $db->simple_select('EE_risk_games','uid','gid = '.$gid);
			if($game = $db->fetch_array($query)) {
				$uid = $game['uid'];
			}
		}
		if($uid == $mybb->user['uid'] || is_super_admin($mybb->user['uid'])) {
			$db->delete_query('EE_risk_chat','gid = '.$gid.' AND uid = '.(int)$mybb->input['uid'].' AND time = '.(int)$mybb->input['time'],1);
		}
	}
?>