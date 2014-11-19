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
			'borders' => array('eu_be_flemishbrabant','eu_be_limburg','eu_be_luxembourg','eu_be_namur','eu_be_walloonbrabant','eu_de_northrhinewestphalia','eu_de_rhinelandpalatinate','eu_luxembourg','eu_nl_limburg'),
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
		'eu_de_badenwurttemberg' => array(
			'name' => 'Baden-Wurttemberg',
			'borders' => array('eu_de_bavaria','eu_de_hesse','eu_de_rhinelandpalatinate'),
			'continents' => array('eu_germany')
		),
		'eu_de_bavaria' => array(
			'name' => 'Bavaria',
			'borders' => array('eu_de_badenwurttemberg','eu_de_hesse','eu_de_saxony','eu_de_thuringia'),
			'continents' => array('eu_germany')
		),
		'eu_de_berlin' => array(
			'name' => 'Berlin',
			'borders' => array('eu_de_brandenburg'),
			'continents' => array('eu_germany')
		),
		'eu_de_brandenburg' => array(
			'name' => 'Brandenburg',
			'borders' => array('eu_de_berlin','eu_de_lowersaxony','eu_de_mecklenburgvorpommern','eu_de_saxony','eu_de_saxonyanhalt'),
			'continents' => array('eu_germany')
		),
		'eu_de_bremen' => array(
			'name' => 'Bremen',
			'borders' => array('eu_de_lowersaxony'),
			'continents' => array('eu_germany')
		),
		'eu_de_hamburg' => array(
			'name' => 'Hamburg',
			'borders' => array('eu_de_lowersaxony','eu_de_schleswigholstein'),
			'continents' => array('eu_germany')
		),
		'eu_de_hesse' => array(
			'name' => 'Hesse',
			'borders' => array('eu_de_badenwurttemberg','eu_de_bavaria','eu_de_lowersaxony','eu_de_northrhinewestphalia','eu_de_rhinelandpalatinate','eu_de_thuringia'),
			'continents' => array('eu_germany')
		),
		'eu_de_lowersaxony' => array(
			'name' => 'Lower Saxony',
			'borders' => array('eu_de_brandenburg','eu_de_bremen','eu_de_hamburg','eu_de_hesse','eu_de_mecklenburgvorpommern','eu_de_northrhinewestphalia','eu_de_saxonyanhalt','eu_de_schleswigholstein','eu_de_thuringia','eu_nl_drenthe','eu_nl_groningen','eu_nl_overijssel'),
			'continents' => array('eu_germany')
		),
		'eu_de_mecklenburgvorpommern' => array(
			'name' => 'Mecklenburg-Vorpommern',
			'borders' => array('eu_de_brandenburg','eu_de_lowersaxony','eu_de_schleswigholstein'),
			'continents' => array('eu_germany')
		),
		'eu_de_northrhinewestphalia' => array(
			'name' => 'North Rhine-Westphalia',
			'borders' => array('eu_be_liege','eu_de_hesse','eu_de_lowersaxony','eu_de_rhinelandpalatinate','eu_nl_gelderland','eu_nl_limburg','eu_nl_overijssel'),
			'continents' => array('eu_germany')
		),
		'eu_de_rhinelandpalatinate' => array(
			'name' => 'Rhineland-Palatinate',
			'borders' => array('eu_be_liege','eu_de_badenwurttemberg','eu_de_hesse','eu_de_northrhinewestphalia','eu_de_saarland','eu_luxembourg'),
			'continents' => array('eu_germany')
		),
		'eu_de_saarland' => array(
			'name' => 'Saarland',
			'borders' => array('eu_de_rhinelandpalatinate','eu_luxembourg'),
			'continents' => array('eu_germany')
		),
		'eu_de_saxony' => array(
			'name' => 'Saxony',
			'borders' => array('eu_de_bavaria','eu_de_brandenburg','eu_de_saxonyanhalt','eu_de_thuringia'),
			'continents' => array('eu_germany')
		),
		'eu_de_saxonyanhalt' => array(
			'name' => 'Saxony-Anhalt',
			'borders' => array('eu_de_brandenburg','eu_de_lowersaxony','eu_de_saxony','eu_de_thuringia'),
			'continents' => array('eu_germany')
		),
		'eu_de_schleswigholstein' => array(
			'name' => 'Schleswig-Holstein',
			'borders' => array('eu_de_hamburg','eu_de_lowersaxony','eu_de_mecklenburgvorpommern'),
			'continents' => array('eu_germany')
		),
		'eu_de_thuringia' => array(
			'name' => 'Thuringia',
			'borders' => array('eu_de_bavaria','eu_de_hesse','eu_de_lowersaxony','eu_de_saxony','eu_de_saxonyanhalt'),
			'continents' => array('eu_germany')
		),
		'eu_luxembourg' => array(
			'name' => 'Grand Duchy of Luxembourg',
			'borders' => array('eu_be_liege','eu_be_luxembourg','eu_de_rhinelandpalatinate','eu_de_saarland'),
			'continents' => array()
		),
		'eu_nl_drenthe' => array(
			'name' => 'Drenthe',
			'borders' => array('eu_de_lowersaxony','eu_nl_friesland','eu_nl_groningen','eu_nl_overijssel'),
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
			'borders' => array('eu_de_northrhinewestphalia','eu_nl_flevoland','eu_nl_limburg','eu_nl_northbrabant','eu_nl_overijssel','eu_nl_southholland','eu_nl_utrecht'),
			'continents' => array('eu_netherlands')
		),
		'eu_nl_groningen' => array(
			'name' => 'Groningen',
			'borders' => array('eu_de_lowersaxony','eu_nl_drenthe','eu_nl_friesland'),
			'continents' => array('eu_netherlands')
		),
		'eu_nl_limburg' => array(
			'name' => 'Limburg',
			'borders' => array('eu_be_liege','eu_be_limburg','eu_de_northrhinewestphalia','eu_nl_gelderland','eu_nl_northbrabant'),
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
			'borders' => array('eu_de_lowersaxony','eu_de_northrhinewestphalia','eu_nl_drenthe','eu_nl_flevoland','eu_nl_friesland','eu_nl_gelderland'),
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
			'units' => 6,
			'nations' => array('eu_be_antwerp','eu_be_brussels','eu_be_eastflanders','eu_be_flemishbrabant','eu_be_hainaut','eu_be_liege','eu_be_limburg','eu_be_luxembourg','eu_be_namur','eu_be_walloonbrabant','eu_be_westflanders')
		),
		'eu_germany' => array(
			'name' => 'Federal Republic of Germany',
			'units' => 8,
			'nations' => array('eu_de_badenwurttemberg','eu_de_bavaria','eu_de_berlin','eu_de_brandenburg','eu_de_bremen','eu_de_hamburg','eu_de_hesse','eu_de_lowersaxony','eu_de_mecklenburgvorpommern','eu_de_northrhinewestphalia','eu_de_rhinelandpalatinate','eu_de_saarland','eu_de_saxony','eu_de_saxonyanhalt','eu_de_schleswigholstein','eu_de_thuringia')
		),
		'eu_netherlands' => array(
			'name' => 'The Netherlands',
			'units' => 7,
			'nations' => array('eu_nl_drenthe','eu_nl_flevoland','eu_nl_friesland','eu_nl_gelderland','eu_nl_groningen','eu_nl_limburg','eu_nl_northbrabant','eu_nl_northholland','eu_nl_overijssel','eu_nl_southholland','eu_nl_utrecht','eu_nl_zeeland')
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
			'name' => 'Benelux',
			'description' => 'To win this game you will have to conquer the Kingdom of Belgium, the Netherlands, and the Grand Duchy of Luxembourg.',
			'conditions' => array('continent' => array('eu_belgium','eu_netherlands'), 'nation' => array('eu_luxembourg'))
		),
		array(
			'name' => 'Greater Germany',
			'description' => 'To win this game you will have to conquer the Federal Republic of Germany and one additional region.',
			'conditions' => array('continent' => array('eu_germany'), 'continents' => 2)
		)
	),
	'mission_distribution' => array(1,1,1,1,2,3)
);
?>