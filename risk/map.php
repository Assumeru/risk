<?php
	define('IN_MYBB',1);
	define('THIS_SCRIPT','EE_risk_map.php');

	require_once '../global.php';
	require_once './inc/functions.php';
	require_once './inc/global.php';
	require_once './inc/maps.php';

	if(isset($mybb->input['map']) && is_numeric($mybb->input['map']) && isset($mapTypes[(int)$mybb->input['map']])) {
		$map = (int)$mybb->input['map'];
		eval('$page = "'.$templates->get('EE-risk_mapinfo_'.$map,true,false).'";');
		EE_risk::output_page($page,$mapTypes[$map]['name']);
	} else {
		header('Location: '.EE_risk::$URL_MAPS);
		die;
	}
?>