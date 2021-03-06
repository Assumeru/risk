<?php
$mapdata = array(
	'nations' => array(
		'af_central' => array(
			'name' => 'Central Africa',
			'borders' => array('af_east','af_north','af_south'),
			'continents' => array('africa')
		),
		'af_east' => array(
			'name' => 'East Africa',
			'borders' => array('af_central','af_egypt','af_madagascar','af_north','af_south','as_middleeast'),
			'continents' => array('africa')
		),
		'af_egypt' => array(
			'name' => 'Egypt',
			'borders' => array('af_east','af_north','as_middleeast','eu_south'),
			'continents' => array('africa')
		),
		'af_madagascar' => array(
			'name' => 'Madagascar',
			'borders' => array('af_east','af_south'),
			'continents' => array('africa')
		),
		'af_north' => array(
			'name' => 'North Africa',
			'borders' => array('af_central','af_east','af_egypt','eu_west','sa_brazil'),
			'continents' => array('africa')
		),
		'af_south' => array(
			'name' => 'South Africa',
			'borders' => array('af_central','af_east','af_madagascar'),
			'continents' => array('africa')
		),
		'as_china' => array(
			'name' => 'China',
			'borders' => array('as_india','as_japan','as_ru_kamchatka','as_kazakhstan','as_mongolia','as_ru_siberia','as_southeast','as_ru_yakutsk'),
			'continents' => array('asia')
		),
		'as_india' => array(
			'name' => 'India',
			'borders' => array('as_china','as_kazakhstan','as_middleeast','as_southeast'),
			'continents' => array('asia')
		),
		'as_japan' => array(
			'name' => 'Japan',
			'borders' => array('as_china','as_ru_kamchatka'),
			'continents' => array('asia')
		),
		'as_ru_kamchatka' => array(
			'name' => 'Kamchatka',
			'borders' => array('as_china','as_japan','as_ru_siberia','na_usa_alaska'),
			'continents' => array('asia')
		),
		'as_kazakhstan' => array(
			'name' => 'Kazakhstan',
			'borders' => array('as_china','as_india','as_middleeast','as_mongolia','as_ru_ural','as_ru_yakutsk','eu_east'),
			'continents' => array('asia')
		),
		'as_middleeast' => array(
			'name' => 'Middle East',
			'borders' => array('af_east','af_egypt','as_india','as_kazakhstan','eu_east','eu_south'),
			'continents' => array('asia')
		),
		'as_mongolia' => array(
			'name' => 'Mongolia',
			'borders' => array('as_china','as_kazakhstan','as_ru_siberia','as_ru_yakutsk'),
			'continents' => array('asia')
		),
		'as_ru_siberia' => array(
			'name' => 'Siberia',
			'borders' => array('as_china','as_ru_kamchatka','as_mongolia','as_ru_yakutsk'),
			'continents' => array('asia')
		),
		'as_southeast' => array(
			'name' => 'Southeast Asia',
			'borders' => array('as_china','as_india','oc_indonesia'),
			'continents' => array('asia')
		),
		'as_ru_ural' => array(
			'name' => 'Ural',
			'borders' => array('as_kazakhstan','as_ru_yakutsk','eu_east'),
			'continents' => array('asia')
		),
		'as_ru_yakutsk' => array(
			'name' => 'Yakutsk',
			'borders' => array('as_china','as_kazakhstan','as_mongolia','as_ru_siberia','as_ru_ural'),
			'continents' => array('asia')
		),
		'eu_britain' => array(
			'name' => 'British Isles',
			'borders' => array('eu_iceland','eu_north','eu_scandinavia','eu_west'),
			'continents' => array('europe')
		),
		'eu_east' => array(
			'name' => 'Eastern Europe',
			'borders' => array('as_kazakhstan','as_middleeast','as_ru_ural','eu_north','eu_scandinavia','eu_south'),
			'continents' => array('europe')
		),
		'eu_iceland' => array(
			'name' => 'Iceland',
			'borders' => array('eu_britain','eu_scandinavia','na_dk_greenland'),
			'continents' => array('europe')
		),
		'eu_north' => array(
			'name' => 'Northern Europe',
			'borders' => array('eu_britain','eu_east','eu_scandinavia','eu_south','eu_west'),
			'continents' => array('europe')
		),
		'eu_scandinavia' => array(
			'name' => 'Scandinavia',
			'borders' => array('eu_britain','eu_east','eu_iceland','eu_north'),
			'continents' => array('europe')
		),
		'eu_south' => array(
			'name' => 'Southern Europe',
			'borders' => array('af_egypt','as_middleeast','eu_east','eu_north','eu_west'),
			'continents' => array('europe')
		),
		'eu_west' => array(
			'name' => 'Western Europe',
			'borders' => array('af_north','eu_britain','eu_north','eu_south'),
			'continents' => array('europe')
		),
		'na_usa_alaska' => array(
			'name' => 'Alaska',
			'borders' => array('as_ru_kamchatka','na_ca_alberta','na_ca_northwestterritories'),
			'continents' => array('northamerica')
		),
		'na_ca_alberta' => array(
			'name' => 'Alberta',
			'borders' => array('na_ca_northwestterritories','na_ca_quebec','na_dk_greenland','na_usa_alaska','na_usa_east','na_usa_west'),
			'continents' => array('northamerica')
		),
		'na_central' => array(
			'name' => 'Central America',
			'borders' => array('na_usa_east','na_usa_west','sa_venezuela'),
			'continents' => array('northamerica')
		),
		'na_usa_east' => array(
			'name' => 'Eastern United States',
			'borders' => array('na_ca_alberta','na_ca_quebec','na_central','na_usa_west'),
			'continents' => array('northamerica')
		),
		'na_dk_greenland' => array(
			'name' => 'Greenland',
			'borders' => array('eu_iceland','na_ca_alberta','na_ca_northwestterritories','na_ca_quebec'),
			'continents' => array('northamerica')
		),
		'na_ca_northwestterritories' => array(
			'name' => 'Northwest Territories',
			'borders' => array('na_ca_alberta','na_dk_greenland','na_usa_alaska'),
			'continents' => array('northamerica')
		),
		'na_ca_quebec' => array(
			'name' => 'Quebec',
			'borders' => array('na_ca_alberta','na_dk_greenland','na_usa_east'),
			'continents' => array('northamerica')
		),
		'na_usa_west' => array(
			'name' => 'Western United States',
			'borders' => array('na_ca_alberta','na_central','na_usa_east'),
			'continents' => array('northamerica')
		),
		'oc_au_east' => array(
			'name' => 'Eastern Australia',
			'borders' => array('oc_newguinea','oc_au_west'),
			'continents' => array('oceania')
		),
		'oc_indonesia' => array(
			'name' => 'Indonesia',
			'borders' => array('as_southeast','oc_newguinea','oc_au_west'),
			'continents' => array('oceania')
		),
		'oc_newguinea' => array(
			'name' => 'New Guinea',
			'borders' => array('oc_au_east','oc_indonesia','oc_au_west'),
			'continents' => array('oceania')
		),
		'oc_au_west' => array(
			'name' => 'Western Autralia',
			'borders' => array('oc_au_east','oc_indonesia','oc_newguinea'),
			'continents' => array('oceania')
		),
		'sa_argentina' => array(
			'name' => 'Argentina',
			'borders' => array('sa_brazil','sa_peru'),
			'continents' => array('southamerica')
		),
		'sa_brazil' => array(
			'name' => 'Brazil',
			'borders' => array('af_north','sa_argentina','sa_peru','sa_venezuela'),
			'continents' => array('southamerica')
		),
		'sa_peru' => array(
			'name' => 'Peru',
			'borders' => array('sa_argentina','sa_brazil','sa_venezuela'),
			'continents' => array('southamerica')
		),
		'sa_venezuela' => array(
			'name' => 'Venezuela',
			'borders' => array('na_central','sa_brazil','sa_peru'),
			'continents' => array('southamerica')
		)
	),
	'continents' => array(
		'africa' => array(
			'name' => 'Africa',
			'units' => 3,
			'nations' => array('af_central','af_east','af_egypt','af_madagascar','af_north','af_south')
		),
		'asia' => array(
			'name' => 'Asia',
			'units' => 7,
			'nations' => array('as_china','as_india','as_japan','as_ru_kamchatka','as_kazakhstan','as_middleeast','as_mongolia','as_ru_siberia','as_southeast','as_ru_ural','as_ru_yakutsk')
		),
		'europe' => array(
			'name' => 'Europe',
			'units' => 5,
			'nations' => array('eu_britain','eu_east','eu_iceland','eu_north','eu_scandinavia','eu_south','eu_west')
		),
		'northamerica' => array(
			'name' => 'North America',
			'units' => 5,
			'nations' => array('na_usa_alaska','na_ca_alberta','na_central','na_usa_east','na_dk_greenland','na_ca_northwestterritories','na_ca_quebec','na_usa_west')
		),
		'oceania' => array(
			'name' => 'Oceania',
			'units' => 2,
			'nations' => array('oc_au_east','oc_indonesia','oc_newguinea','oc_au_west')
		),
		'southamerica' => array(
			'name' => 'South America',
			'units' => 2,
			'nations' => array('sa_argentina','sa_brazil','sa_peru','sa_venezuela')
		)
	),
	'missions' => array(
		array(
			'name' => 'Domination',
			'description' => 'To win this game you will have to conquer 24 territories.',
			'conditions' => array('territories' => 24)
		),
		array(
			'name' => 'Rivalry',
			'description' => 'To win this game you will have to conquer the last of <user>\'s territories.',
			'conditions' => array('eliminate' => 1),
			'fallback' => 0
		),
		array(
			'name' => 'Zeeland and New Zealand',
			'description' => 'To win this game you will have to conquer Europe, Oceania and a third continent.',
			'conditions' => array('continent' => array('europe','oceania'), 'continents' => 3)
		),
		array(
			'name' => 'Colonization',
			'description' => 'To win this game you will have to conquer Europe, South America and a third continent.',
			'conditions' => array('continent' => array('europe','southamerica'), 'continents' => 3)
		),
		array(
			'name' => 'Slavery',
			'description' => 'To win this game you will have to conquer North America and Africa.',
			'conditions' => array('continent' => array('northamerica','africa'))
		),
		array(
			'name' => 'Pacific Empire',
			'description' => 'To win this game you will have to conquer North America and Oceania.',
			'conditions' => array('continent' => array('northamerica','oceania'))
		),
		array(
			'name' => 'Fusang',
			'description' => 'To win this game you will have to conquer Asia and South America.',
			'conditions' => array('continent' => array('asia','southamerica'))
		),
		array(
			'name' => 'Cradle',
			'description' => 'To win this game you will have to conquer Asia and Africa.',
			'conditions' => array('continent' => array('asia','africa'))
		)
	),
	'mission_distribution' => array(1,1,1,1,2,3,4,5,6,7)
);
?>