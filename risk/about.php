<?php
	define('IN_MYBB',1);
	define('THIS_SCRIPT','EE_risk_about.php');
	$templatelist = 'EE-risk_about,EE-risk_page';
	require_once '../global.php';
	require_once './inc/functions.php';
	require_once './inc/global.php';

	eval('$page = "'.$templates->get('EE-risk_about',true,false).'";');
	EE_risk::output_page($page,'About');
?>