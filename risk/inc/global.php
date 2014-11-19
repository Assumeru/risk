<?php
	if(!defined('IN_MYBB')) {
		die;
	}
	if(!$db->table_exists('EE_risk_games')) {
		header('Location: '.$mybb->settings['bburl']);
		die;
	}
	$plugins_cache = $cache->read('plugins');
	if(!$plugins_cache['active']['EE_risk']) {
		header('Location: '.$mybb->settings['bburl']);
		die;
	}
	if($mybb->user['uid'] == 0) {
		header('Location: '.$mybb->settings['bburl'].'/member.php?action=login');
		die;
	}
?>