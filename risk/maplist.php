<?php
	define('IN_MYBB',1);
	define('THIS_SCRIPT','EE_risk_maplist.php');
	$templatelist = 'EE-risk_page,EE-risk_maplist_map,EE-risk_maplist';
	require_once '../global.php';
	require_once './inc/functions.php';
	require_once './inc/global.php';
	require_once './inc/maps.php';

	$maps = '';
	$mapList = EE_risk::getSortedMaps($mapTypes);
	foreach($mapList as $map) {
		$map['url'] = EE_risk::get_url_map($map['mid'],$map['name']);
		eval('$maps .= "'.$templates->get('EE-risk_maplist_map',true,false).'";');
	}

	eval('$page = "'.$templates->get('EE-risk_maplist',true,false).'";');

	EE_risk::output_page($page,'Maps');
?>