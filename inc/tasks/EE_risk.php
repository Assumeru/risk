<?php
	if(!defined('IN_MYBB')) {
		die;
	}

	function task_EE_risk($task) {
		global $db;

		$yesterday = TIME_NOW - 24*60*60;
		$twoweeksago = TIME_NOW - 2*7*24*60*60;
		//delete old chats
		$query = $db->query('SELECT COUNT(*) AS num FROM '.TABLE_PREFIX.'EE_risk_chat GROUP BY gid HAVING gid = 0');
		$numChats = max(0,$db->fetch_field($query,'num')-10);
		if($numChats > 0) {
			$db->delete_query('EE_risk_chat','time < '.$yesterday.' AND gid = 0',$numChats);
		}

		require_once MYBB_ROOT.'inc/plugins/EE_risk.php';
		//delete finished/old games
		$query = $db->simple_select('EE_risk_games','gid','(state = 4 AND time < '.$yesterday.') OR time < '.$twoweeksago);
		$gids = array();
		while($gid = $db->fetch_field($query,'gid')) {
			$gids[] = $gid;
		}
		if(!empty($gids)) {
			EE_risk_deletegames($gids);
		}
		add_task_log($task,'EE_risk cleanup task completed: '.$numChats.' chat messages and '.count($gids).' games deleted.');
	}
?>