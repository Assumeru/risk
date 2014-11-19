<?php
	define('IN_MYBB',1);
	define('THIS_SCRIPT','EE_risk_memberlist.php');
	$templatelist = 'EE-risk_page,EE-risk_memberlist_member,EE-risk_memberlist';
	require_once '../global.php';
	require_once './inc/functions.php';
	require_once './inc/global.php';

	$lang->load('memberlist');
	$query = $db->query('
			SELECT u.username,u.usergroup,u.displaygroup,s.*
			FROM '.TABLE_PREFIX.'users AS u
			JOIN '.TABLE_PREFIX.'EE_risk_users AS s ON (u.uid=s.uid)
			ORDER BY score DESC');
	$members = '';
	for($n=1;$user = $db->fetch_array($query);$n++) {
		$user['username'] = format_name($user['username'], $user['usergroup'], $user['displaygroup']);
		$user['profilelink'] = build_profile_link($user['username'], $user['uid']);
		eval('$members .= "'.$templates->get('EE-risk_memberlist_member',true,false).'";');
	}

	eval('$page = "'.$templates->get('EE-risk_memberlist',true,false).'";');

	EE_risk::output_page($page,'Players');
?>