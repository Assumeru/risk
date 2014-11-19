<?php
$mapdata = array(
	'nations' => array(
		'eu_be_antwerp' => array(
			'name' => 'Antwerp',
			'borders' => array('eu_be_eastflanders','eu_be_flemishbrabant','eu_be_limburg','eu_nl_northbrabant','eu_nl_zeeland'),
			'continents' => array('eu_belgium')
		),
		'eu_be_brussels' => array(
			'name' => 'Brussels',
			'borders' => array('eu_be_flemishbrabant'),
			'continents' => array('eu_belgium')
		),
		'eu_be_eastflanders' => array(
			'name' => 'East Flanders',
			'borders' => array('eu_be_antwerp','eu_be_flemishbrabant','eu_be_hainaut','eu_be_westflanders','eu_nl_zeeland'),
			'continents' => array('eu_belgium')
		),
		'eu_be_flemishbrabant' => array(
			'name' => 'Flemish Brabant',
			'borders' => array('eu_be_antwerp','eu_be_brussels','eu_be_eastflanders','eu_be_hainaut','eu_be_liege','eu_be_limburg','eu_be_walloonbrabant'),
			'continents' => array('eu_belgium')
		),
		'eu_be_hainaut' => array(
			'name' => 'Hainaut',
			'borders' => array('eu_be_eastflanders','eu_be_flemishbrabant','eu_be_namur','eu_be_walloonbrabant','eu_be_westflanders'),
			'continents' => array('eu_belgium')
		),
		'eu_be_liege' => array(
			'name' => 'Liege',
			'borders' => array('eu_be_flemishbrabant','eu_be_limburg','eu_be_luxembourg','eu_be_namur','eu_be_walloonbrabant','eu_luxembourg','eu_nl_limburg'),
			'continents' => array('eu_belgium')
		),
		'eu_be_limburg' => array(
			'name' => 'Limburg',
			'borders' => array('eu_be_antwerp','eu_be_flemishbrabant','eu_be_liege','eu_nl_limburg','eu_nl_northbrabant'),
			'continents' => array('eu_belgium')
		),
		'eu_be_luxembourg' => array(
			'name' => 'Luxembourg',
			'borders' => array('eu_be_liege','eu_be_namur','eu_luxembourg'),
			'continents' => array('eu_belgium')
		),
		'eu_be_namur' => array(
			'name' => 'Namur',
			'borders' => array('eu_be_hainaut','eu_be_liege','eu_be_luxembourg','eu_be_walloonbrabant'),
			'continents' => array('eu_belgium')
		),
		'eu_be_westflanders' => array(
			'name' => 'West Flanders',
			'borders' => array('eu_be_eastflanders','eu_be_hainaut','eu_nl_zeeland'),
			'continents' => array('eu_belgium')
		),
		'eu_be_walloonbrabant' => array(
			'name' => 'Walloon Brabant',
			'borders' => array('eu_be_flemishbrabant','eu_be_hainaut','eu_be_liege','eu_be_namur'),
			'continents' => array('eu_belgium')
		),
		'eu_luxembourg' => array(
			'name' => 'Grand Duchy of Luxembourg',
			'borders' => array('eu_be_liege','eu_be_luxembourg'),
			'continents' => array()
		),
		'eu_nl_drenthe' => array(
			'name' => 'Drenthe',
			'borders' => array('eu_nl_friesland','eu_nl_groningen','eu_nl_overijssel'),
			'continents' => array('eu_netherlands')
		),
		'eu_nl_flevoland' => array(
			'name' => 'Flevoland',
			'borders' => array('eu_nl_friesland','eu_nl_gelderland','eu_nl_northholland','eu_nl_overijssel','eu_nl_utrecht'),
				'continents' => array('eu_netherlands')
		),
		'eu_nl_friesland' => array(
			'name' => 'Friesland',
			'borders' => array('eu_nl_drenthe','eu_nl_flevoland','eu_nl_groningen','eu_nl_northholland','eu_nl_overijssel'),
			'continents' => array('eu_netherlands')
		),
		'eu_nl_gelderland' => array(
			'name' => 'Gelderland',
			'borders' => array('eu_nl_flevoland','eu_nl_limburg','eu_nl_northbrabant','eu_nl_overijssel','eu_nl_southholland','eu_nl_utrecht'),
			'continents' => array('eu_netherlands')
		),
		'eu_nl_groningen' => array(
			'name' => 'Groningen',
			'borders' => array('eu_nl_drenthe','eu_nl_friesland'),
			'continents' => array('eu_netherlands')
		),
		'eu_nl_limburg' => array(
			'name' => 'Limburg',
			'borders' => array('eu_be_liege','eu_be_limburg','eu_nl_gelderland','eu_nl_northbrabant'),
			'continents' => array('eu_netherlands')
		),
		'eu_nl_northbrabant' => array(
			'name' => 'North Brabant',
			'borders' => array('eu_be_antwerp','eu_be_limburg','eu_nl_gelderland','eu_nl_limburg','eu_nl_southholland','eu_nl_zeeland'),
			'continents' => array('eu_netherlands')
		),
		'eu_nl_northholland' => array(
			'name' => 'North Holland',
			'borders' => array('eu_nl_flevoland','eu_nl_friesland','eu_nl_southholland','eu_nl_utrecht'),
			'continents' => array('eu_netherlands')
		),
		'eu_nl_overijssel' => array(
			'name' => 'Overijssel',
			'borders' => array('eu_nl_drenthe','eu_nl_flevoland','eu_nl_friesland','eu_nl_gelderland'),
			'continents' => array('eu_netherlands')
		),
		'eu_nl_southholland' => array(
			'name' => 'South Holland',
			'borders' => array('eu_nl_gelderland','eu_nl_northbrabant','eu_nl_northholland','eu_nl_utrecht','eu_nl_zeeland'),
			'continents' => array('eu_netherlands')
		),
		'eu_nl_utrecht' => array(
			'name' => 'Utrecht',
			'borders' => array('eu_nl_flevoland','eu_nl_gelderland','eu_nl_northholland','eu_nl_southholland'),
			'continents' => array('eu_netherlands')
		),
		'eu_nl_zeeland' => array(
			'name' => 'Zeeland',
			'borders' => array('eu_be_antwerp','eu_be_eastflanders','eu_be_westflanders','eu_nl_northbrabant','eu_nl_southholland'),
			'continents' => array('eu_netherlands')
		)
	),
	'continents' => array(
		'eu_belgium' => array(
			'name' => 'Kingdom of Belgium',
			'units' => 2,
			'nations' => array('eu_be_antwerp','eu_be_brussels','eu_be_eastflanders','eu_be_flemishbrabant','eu_be_hainaut','eu_be_liege','eu_be_limburg','eu_be_luxembourg','eu_be_namur','eu_be_walloonbrabant','eu_be_westflanders')
		),
		'eu_netherlands' => array(
			'name' => 'The Netherlands',
			'units' => 2,
			'nations' => array('eu_nl_drenthe','eu_nl_flevoland','eu_nl_friesland','eu_nl_gelderland','eu_nl_groningen','eu_nl_limburg','eu_nl_northbrabant','eu_nl_northholland','eu_nl_overijssel','eu_nl_southholland','eu_nl_utrecht','eu_nl_zeeland')
		)
	),
	'missions' => array(
		array(
			'name' => 'World Domination',
			'description' => 'To win this game you will have to conquer all 24 territories on the map.',
			'conditions' => array('territories' => 24)
		)
	),
	'mission_distribution' => array(0,0)
);
?>