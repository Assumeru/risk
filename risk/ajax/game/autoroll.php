<?php
	if(!defined('AJAX')) {
		die;
	}
	if($players[indexOfPlayer($mybb->user['uid'],$players)]['autoroll'] == 0) {
		$db->update_query('EE_risk_gamesjoined',array('autoroll' => 1),'gid = '.$gid.' AND uid = '.$mybb->user['uid']);
		$page = 'true';
	} else {
		$db->update_query('EE_risk_gamesjoined',array('autoroll' => 0),'gid = '.$gid.' AND uid = '.$mybb->user['uid']);
		$page = 'false';
	}
?>