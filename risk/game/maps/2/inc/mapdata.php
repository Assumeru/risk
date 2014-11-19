<?php
$mapdata = array(
	'nations' => array(
		'na_ca_alberta' => array(
			'name' => 'Alberta',
			'borders' => array('na_ca_britishcolombia','na_ca_northwestterritories','na_ca_saskatchewan','na_usa_montana'),
			'continents' => array('na_canada')
		),
		'na_ca_britishcolombia' => array(
			'name' => 'British Colombia',
			'borders' => array('na_ca_alberta','na_ca_northwestterritories','na_ca_yukon','na_usa_alaska','na_usa_idaho','na_usa_montana','na_usa_washington'),
			'continents' => array('na_canada')
		),
		'na_ca_manitoba' => array(
			'name' => 'Manitoba',
			'borders' => array('na_ca_nunavut','na_ca_ontario','na_ca_saskatchewan','na_usa_minnesota','na_ca_northwestterritories','na_usa_northdakota'),
			'continents' => array('na_canada')
		),
		'na_ca_newbrunswick' => array(
			'name' => 'New Brunswick',
			'borders' => array('na_ca_novascotia','na_ca_princeedwardisland','na_ca_quebec','na_usa_maine'),
			'continents' => array('na_canada')
		),
		'na_ca_newfoundland' => array(
			'name' => 'Newfoundland',
			'borders' => array('na_ca_novascotia','na_ca_princeedwardisland','na_ca_quebec'),
			'continents' => array('na_canada')
		),
		'na_ca_northwestterritories' => array(
			'name' => 'Northwest Territories',
			'borders' => array('na_ca_alberta','na_ca_britishcolombia','na_ca_manitoba','na_ca_nunavut','na_ca_saskatchewan','na_ca_yukon'),
			'continents' => array('na_canada')
		),
		'na_ca_novascotia' => array(
			'name' => 'Nova Scotia',
			'borders' => array('na_ca_newbrunswick','na_ca_newfoundland','na_ca_princeedwardisland'),
			'continents' => array('na_canada')
		),
		'na_ca_nunavut' => array(
			'name' => 'Nunavut',
			'borders' => array('na_ca_manitoba','na_ca_northwestterritories','na_ca_quebec','na_ca_saskatchewan'),
			'continents' => array('na_canada')
		),
		'na_ca_ontario' => array(
			'name' => 'Ontario',
			'borders' => array('na_ca_manitoba','na_ca_quebec','na_usa_michigan','na_usa_minnesota','na_usa_newyork'),
			'continents' => array('na_canada')
		),
		'na_ca_princeedwardisland' => array(
			'name' => 'Prince Edward Island',
			'borders' => array('na_ca_newbrunswick','na_ca_newfoundland','na_ca_novascotia'),
			'continents' => array('na_canada')
		),
		'na_ca_quebec' => array(
			'name' => 'Quebec',
			'borders' => array('na_ca_newbrunswick','na_ca_newfoundland','na_ca_nunavut','na_ca_ontario','na_usa_maine','na_usa_newhampshire','na_usa_newyork','na_usa_vermont'),
			'continents' => array('na_canada')
		),
		'na_ca_saskatchewan' => array(
			'name' => 'Saskatchewan',
			'borders' => array('na_ca_alberta','na_ca_manitoba','na_ca_northwestterritories','na_ca_nunavut','na_usa_montana','na_usa_northdakota'),
			'continents' => array('na_canada')
		),
		'na_ca_yukon' => array(
			'name' => 'Yukon',
			'borders' => array('na_ca_britishcolombia','na_ca_northwestterritories','na_usa_alaska'),
			'continents' => array('na_canada')
		),
		'na_cuba' => array(
			'name' => 'Republic of Cuba',
			'borders' => array('na_mx_quintanaroo','na_usa_florida'),
			'continents' => array('na_newspain')
		),
		'na_mx_aguascalientes' => array(
			'name' => 'Aguascalientes',
			'borders' => array('na_mx_jalisco','na_mx_zacatecas'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_bajacalifornia' => array(
			'name' => 'Baja California',
			'borders' => array('na_mx_bajacaliforniasur','na_mx_sonora','na_usa_arizona','na_usa_california'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_bajacaliforniasur' => array(
			'name' => 'Baja California Sur',
			'borders' => array('na_mx_bajacalifornia','na_mx_sonora'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_campeche' => array(
			'name' => 'Campeche',
			'borders' => array('na_mx_quintanaroo','na_mx_tabasco','na_mx_yucatan'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_chiapas' => array(
			'name' => 'Chiapas',
			'borders' => array('na_mx_oaxaca','na_mx_tabasco','na_mx_veracruz'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_chihuahua' => array(
			'name' => 'Chihuahua',
			'borders' => array('na_mx_coahuila','na_mx_durango','na_mx_sinaloa','na_mx_sonora','na_usa_arizona','na_usa_newmexico','na_usa_texas'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_coahuila' => array(
			'name' => 'Coahuila',
			'borders' => array('na_mx_chihuahua','na_mx_durango','na_mx_nuevoleon','na_mx_sanluispotosi','na_mx_zacatecas','na_usa_texas'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_colima' => array(
			'name' => 'Colima',
			'borders' => array('na_mx_jalisco','na_mx_michoacan'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_durango' => array(
			'name' => 'Durango',
			'borders' => array('na_mx_chihuahua','na_mx_coahuila','na_mx_jalisco','na_mx_nayarit','na_mx_sinaloa','na_mx_zacatecas'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_guanajuato' => array(
			'name' => 'Guanajuato',
			'borders' => array('na_mx_jalisco','na_mx_michoacan','na_mx_queretaro','na_mx_sanluispotosi','na_mx_zacatecas'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_guerrero' => array(
			'name' => 'Guerrero',
			'borders' => array('na_mx_mexico','na_mx_michoacan','na_mx_morelos','na_mx_oaxaca','na_mx_puebla'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_hidalgo' => array(
			'name' => 'Hidalgo',
			'borders' => array('na_mx_mexico','na_mx_michoacan','na_mx_puebla','na_mx_queretaro','na_mx_sanluispotosi','na_mx_tlaxcala','na_mx_veracruz'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_jalisco' => array(
			'name' => 'Jalisco',
			'borders' => array('na_mx_aguascalientes','na_mx_colima','na_mx_durango','na_mx_guanajuato','na_mx_michoacan','na_mx_nayarit','na_mx_sanluispotosi','na_mx_zacatecas'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_mexico' => array(
			'name' => 'Mexico',
			'borders' => array('na_mx_guerrero','na_mx_hidalgo','na_mx_mexicocity','na_mx_michoacan','na_mx_morelos','na_mx_puebla','na_mx_queretaro','na_mx_tlaxcala'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_mexicocity' => array(
			'name' => 'Mexico City',
			'borders' => array('na_mx_mexico','na_mx_morelos'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_michoacan' => array(
			'name' => 'Michoacan',
			'borders' => array('na_mx_colima','na_mx_guanajuato','na_mx_guerrero','na_mx_hidalgo','na_mx_jalisco','na_mx_mexico','na_mx_queretaro'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_morelos' => array(
			'name' => 'Morelos',
			'borders' => array('na_mx_guerrero','na_mx_mexico','na_mx_mexicocity','na_mx_puebla'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_nayarit' => array(
			'name' => 'Nayarit',
			'borders' => array('na_mx_durango','na_mx_jalisco','na_mx_sinaloa','na_mx_zacatecas'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_nuevoleon' => array(
			'name' => 'Nuevoleon',
			'borders' => array('na_mx_coahuila','na_mx_sanluispotosi','na_mx_tamaulipas','na_mx_zacatecas','na_usa_texas'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_oaxaca' => array(
			'name' => 'Oaxaca',
			'borders' => array('na_mx_chiapas','na_mx_guerrero','na_mx_puebla','na_mx_tabasco','na_mx_veracruz'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_puebla' => array(
			'name' => 'Puebla',
			'borders' => array('na_mx_guerrero','na_mx_hidalgo','na_mx_mexico','na_mx_morelos','na_mx_oaxaca','na_mx_tlaxcala','na_mx_veracruz'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_queretaro' => array(
			'name' => 'Queretaro',
			'borders' => array('na_mx_guanajuato','na_mx_hidalgo','na_mx_mexico','na_mx_michoacan','na_mx_sanluispotosi'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_quintanaroo' => array(
			'name' => 'Quintana Roo',
			'borders' => array('na_cuba','na_mx_campeche','na_mx_yucatan'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_sanluispotosi' => array(
			'name' => 'San Luis Potosi',
			'borders' => array('na_mx_coahuila','na_mx_guanajuato','na_mx_hidalgo','na_mx_jalisco','na_mx_nuevoleon','na_mx_queretaro','na_mx_tamaulipas','na_mx_veracruz','na_mx_zacatecas'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_sinaloa' => array(
			'name' => 'Sinaloa',
			'borders' => array('na_mx_chihuahua','na_mx_durango','na_mx_nayarit','na_mx_sonora'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_sonora' => array(
			'name' => 'Sonora',
			'borders' => array('na_mx_bajacalifornia','na_mx_bajacaliforniasur','na_mx_chihuahua','na_mx_sinaloa','na_usa_arizona','na_usa_newmexico'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_tabasco' => array(
			'name' => 'Tabasco',
			'borders' => array('na_mx_campeche','na_mx_chiapas','na_mx_oaxaca','na_mx_veracruz'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_tamaulipas' => array(
			'name' => 'Tamaulipas',
			'borders' => array('na_mx_nuevoleon','na_mx_sanluispotosi','na_mx_veracruz','na_usa_texas'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_tlaxcala' => array(
			'name' => 'Tlaxcala',
			'borders' => array('na_mx_hidalgo','na_mx_mexico','na_mx_puebla'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_veracruz' => array(
			'name' => 'Veracruz',
			'borders' => array('na_mx_chiapas','na_mx_hidalgo','na_mx_oaxaca','na_mx_puebla','na_mx_sanluispotosi','na_mx_tabasco','na_mx_tamaulipas'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_yucatan' => array(
			'name' => 'Yucatan',
			'borders' => array('na_mx_campeche','na_mx_quintanaroo'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_mx_zacatecas' => array(
			'name' => 'Zacatecas',
			'borders' => array('na_mx_aguascalientes','na_mx_coahuila','na_mx_durango','na_mx_guanajuato','na_mx_jalisco','na_mx_nayarit','na_mx_nuevoleon','na_mx_sanluispotosi'),
			'continents' => array('na_mexico','na_newspain')
		),
		'na_usa_alabama' => array(
			'name' => 'Alabama',
			'borders' => array('na_usa_florida','na_usa_georgia','na_usa_mississippi','na_usa_tennessee'),
			'continents' => array('na_usa','na_confederacy')
		),
		'na_usa_alaska' => array(
			'name' => 'Alaska',
			'borders' => array('na_ca_britishcolombia','na_ca_yukon'),
			'continents' => array('na_usa')
		),
		'na_usa_arizona' => array(
			'name' => 'Arizona',
			'borders' => array('na_mx_bajacalifornia','na_mx_chihuahua','na_mx_sonora','na_usa_california','na_usa_nevada','na_usa_newmexico','na_usa_utah'),
			'continents' => array('na_usa','na_newspain')
		),
		'na_usa_arkansas' => array(
			'name' => 'Arkansas',
			'borders' => array('na_usa_louisiana','na_usa_mississippi','na_usa_missouri','na_usa_oklahoma','na_usa_tennessee','na_usa_texas'),
			'continents' => array('na_usa','na_confederacy')
		),
		'na_usa_california' => array(
			'name' => 'California',
			'borders' => array('na_mx_bajacalifornia','na_usa_arizona','na_usa_hawaii','na_usa_nevada','na_usa_oregon'),
			'continents' => array('na_usa','na_newspain')
		),
		'na_usa_colorado' => array(
			'name' => 'Colorado',
			'borders' => array('na_usa_kansas','na_usa_nebraska','na_usa_newmexico','na_usa_oklahoma','na_usa_utah','na_usa_wyoming'),
			'continents' => array('na_usa')
		),
		'na_usa_connecticut' => array(
			'name' => 'Connecticut',
			'borders' => array('na_usa_massachusetts','na_usa_newyork','na_usa_rhodeisland'),
			'continents' => array('na_usa','na_colonies')
		),
		'na_usa_delaware' => array(
			'name' => 'Delaware',
			'borders' => array('na_usa_maryland','na_usa_newjersey','na_usa_pennsylvania'),
			'continents' => array('na_usa','na_colonies')
		),
		'na_usa_florida' => array(
			'name' => 'Florida',
			'borders' => array('na_cuba','na_usa_alabama','na_usa_georgia'),
			'continents' => array('na_usa','na_confederacy')
		),
		'na_usa_georgia' => array(
			'name' => 'Georgia',
			'borders' => array('na_usa_alabama','na_usa_florida','na_usa_northcarolina','na_usa_southcarolina','na_usa_tennessee'),
			'continents' => array('na_usa','na_confederacy','na_colonies')
		),
		'na_usa_hawaii' => array(
			'name' => 'Hawaii',
			'borders' => array('na_usa_california','na_usa_oregon'),
			'continents' => array('na_usa')
		),
		'na_usa_idaho' => array(
			'name' => 'Idaho',
			'borders' => array('na_ca_britishcolombia','na_usa_montana','na_usa_nevada','na_usa_oregon','na_usa_utah','na_usa_washington','na_usa_wyoming'),
			'continents' => array('na_usa')
		),
		'na_usa_illinois' => array(
			'name' => 'Illinois',
			'borders' => array('na_usa_indiana','na_usa_iowa','na_usa_kentucky','na_usa_missouri','na_usa_wisconsin'),
			'continents' => array('na_usa','na_iroquois')
		),
		'na_usa_indiana' => array(
			'name' => 'Indiana',
			'borders' => array('na_usa_illinois','na_usa_kentucky','na_usa_michigan','na_usa_ohio'),
			'continents' => array('na_usa','na_iroquois')
		),
		'na_usa_iowa' => array(
			'name' => 'Iowa',
			'borders' => array('na_usa_illinois','na_usa_minnesota','na_usa_missouri','na_usa_nebraska','na_usa_southdakota','na_usa_wisconsin'),
			'continents' => array('na_usa')
		),
		'na_usa_kansas' => array(
			'name' => 'Kansas',
			'borders' => array('na_usa_colorado','na_usa_missouri','na_usa_nebraska','na_usa_oklahoma'),
			'continents' => array('na_usa')
		),
		'na_usa_kentucky' => array(
			'name' => 'Kentucky',
			'borders' => array('na_usa_illinois','na_usa_indiana','na_usa_missouri','na_usa_ohio','na_usa_tennessee','na_usa_virginia','na_usa_westvirginia'),
			'continents' => array('na_usa','na_colonies','na_iroquois')
		),
		'na_usa_louisiana' => array(
			'name' => 'Louisiana',
			'borders' => array('na_usa_arkansas','na_usa_mississippi','na_usa_texas'),
			'continents' => array('na_usa','na_confederacy')
		),
		'na_usa_maine' => array(
			'name' => 'Maine',
			'borders' => array('na_ca_newbrunswick','na_ca_quebec','na_usa_newhampshire'),
			'continents' => array('na_usa','na_colonies')
		),
		'na_usa_maryland' => array(
			'name' => 'Maryland',
			'borders' => array('na_usa_delaware','na_usa_pennsylvania','na_usa_virginia','na_usa_westvirginia'),
			'continents' => array('na_usa','na_colonies')
		),
		'na_usa_massachusetts' => array(
			'name' => 'Massachusetts',
			'borders' => array('na_usa_connecticut','na_usa_newhampshire','na_usa_newyork','na_usa_rhodeisland','na_usa_vermont'),
			'continents' => array('na_usa','na_colonies')
		),
		'na_usa_michigan' => array(
			'name' => 'Michigan',
			'borders' => array('na_ca_ontario','na_usa_indiana','na_usa_ohio','na_usa_wisconsin'),
			'continents' => array('na_usa','na_iroquois')
		),
		'na_usa_minnesota' => array(
			'name' => 'Minnesota',
			'borders' => array('na_ca_manitoba','na_ca_ontario','na_usa_iowa','na_usa_northdakota','na_usa_southdakota','na_usa_wisconsin'),
			'continents' => array('na_usa')
		),
		'na_usa_mississippi' => array(
			'name' => 'Mississippi',
			'borders' => array('na_usa_alabama','na_usa_arkansas','na_usa_louisiana','na_usa_tennessee'),
			'continents' => array('na_usa','na_confederacy')
		),
		'na_usa_missouri' => array(
			'name' => 'Missouri',
			'borders' => array('na_usa_arkansas','na_usa_illinois','na_usa_iowa','na_usa_kansas','na_usa_kentucky','na_usa_nebraska','na_usa_oklahoma','na_usa_tennessee'),
			'continents' => array('na_usa')
		),
		'na_usa_montana' => array(
			'name' => 'Montana',
			'borders' => array('na_ca_alberta','na_ca_britishcolombia','na_ca_saskatchewan','na_usa_idaho','na_usa_northdakota','na_usa_southdakota','na_usa_wyoming'),
			'continents' => array('na_usa')
		),
		'na_usa_nebraska' => array(
			'name' => 'Nebraska',
			'borders' => array('na_usa_colorado','na_usa_iowa','na_usa_kansas','na_usa_missouri','na_usa_southdakota','na_usa_wyoming'),
			'continents' => array('na_usa')
		),
		'na_usa_nevada' => array(
			'name' => 'Nevada',
			'borders' => array('na_usa_arizona','na_usa_california','na_usa_idaho','na_usa_oregon','na_usa_utah'),
			'continents' => array('na_usa','na_newspain')
		),
		'na_usa_newhampshire' => array(
			'name' => 'New Hampshire',
			'borders' => array('na_ca_quebec','na_usa_maine','na_usa_massachusetts','na_usa_vermont'),
			'continents' => array('na_usa','na_colonies')
		),
		'na_usa_newjersey' => array(
			'name' => 'New Jersey',
			'borders' => array('na_usa_delaware','na_usa_newyork','na_usa_pennsylvania'),
			'continents' => array('na_usa','na_colonies')
		),
		'na_usa_newmexico' => array(
			'name' => 'New Mexico',
			'borders' => array('na_mx_chihuahua','na_mx_sonora','na_usa_arizona','na_usa_colorado','na_usa_oklahoma','na_usa_texas','na_usa_utah'),
			'continents' => array('na_usa','na_newspain')
		),
		'na_usa_newyork' => array(
			'name' => 'New York',
			'borders' => array('na_ca_ontario','na_ca_quebec','na_usa_connecticut','na_usa_massachusetts','na_usa_newjersey','na_usa_pennsylvania','na_usa_vermont'),
			'continents' => array('na_usa','na_colonies','na_iroquois')
		),
		'na_usa_northdakota' => array(
			'name' => 'North Dakota',
			'borders' => array('na_ca_manitoba','na_ca_saskatchewan','na_usa_minnesota','na_usa_montana','na_usa_southdakota'),
			'continents' => array('na_usa')
		),
		'na_usa_northcarolina' => array(
			'name' => 'North Carolina',
			'borders' => array('na_usa_georgia','na_usa_southcarolina','na_usa_tennessee','na_usa_virginia'),
			'continents' => array('na_usa','na_confederacy','na_colonies')
		),
		'na_usa_ohio' => array(
			'name' => 'Ohio',
			'borders' => array('na_usa_indiana','na_usa_kentucky','na_usa_michigan','na_usa_pennsylvania','na_usa_westvirginia'),
			'continents' => array('na_usa','na_iroquois')
		),
		'na_usa_oklahoma' => array(
			'name' => 'Oklahoma',
			'borders' => array('na_usa_arkansas','na_usa_colorado','na_usa_kansas','na_usa_missouri','na_usa_newmexico','na_usa_texas'),
			'continents' => array('na_usa')
		),
		'na_usa_oregon' => array(
			'name' => 'Oregon',
			'borders' => array('na_usa_california','na_usa_hawaii','na_usa_idaho','na_usa_nevada','na_usa_washington'),
			'continents' => array('na_usa')
		),
		'na_usa_pennsylvania' => array(
			'name' => 'Pennsylvania',
			'borders' => array('na_usa_delaware','na_usa_maryland','na_usa_newjersey','na_usa_newyork','na_usa_ohio','na_usa_westvirginia'),
			'continents' => array('na_usa','na_colonies','na_iroquois')
		),
		'na_usa_rhodeisland' => array(
			'name' => 'Rhode Island',
			'borders' => array('na_usa_connecticut','na_usa_massachusetts'),
			'continents' => array('na_usa','na_colonies')
		),
		'na_usa_southdakota' => array(
			'name' => 'South Dakota',
			'borders' => array('na_usa_iowa','na_usa_minnesota','na_usa_montana','na_usa_nebraska','na_usa_northdakota','na_usa_wyoming'),
			'continents' => array('na_usa')
		),
		'na_usa_southcarolina' => array(
			'name' => 'South Carolina',
			'borders' => array('na_usa_georgia','na_usa_northcarolina'),
			'continents' => array('na_usa','na_confederacy','na_colonies')
		),
		'na_usa_tennessee' => array(
			'name' => 'Tennessee',
			'borders' => array('na_usa_alabama','na_usa_arkansas','na_usa_georgia','na_usa_kentucky','na_usa_mississippi','na_usa_missouri','na_usa_northcarolina','na_usa_virginia'),
			'continents' => array('na_usa','na_confederacy','na_colonies')
		),
		'na_usa_texas' => array(
			'name' => 'Texas',
			'borders' => array('na_mx_chihuahua','na_mx_coahuila','na_mx_nuevoleon','na_mx_tamaulipas','na_usa_arkansas','na_usa_louisiana','na_usa_newmexico','na_usa_oklahoma'),
			'continents' => array('na_usa','na_confederacy','na_newspain')
		),
		'na_usa_utah' => array(
			'name' => 'Utah',
			'borders' => array('na_usa_arizona','na_usa_colorado','na_usa_idaho','na_usa_nevada','na_usa_newmexico','na_usa_wyoming'),
			'continents' => array('na_usa','na_newspain')
		),
		'na_usa_vermont' => array(
			'name' => 'Vermont',
			'borders' => array('na_ca_quebec','na_usa_massachusetts','na_usa_newhampshire','na_usa_newyork'),
			'continents' => array('na_usa','na_colonies')
		),
		'na_usa_virginia' => array(
			'name' => 'Virginia',
			'borders' => array('na_usa_kentucky','na_usa_maryland','na_usa_northcarolina','na_usa_tennessee','na_usa_westvirginia'),
			'continents' => array('na_usa','na_confederacy','na_colonies')
		),
		'na_usa_washington' => array(
			'name' => 'Washington',
			'borders' => array('na_ca_britishcolombia','na_usa_idaho','na_usa_oregon'),
			'continents' => array('na_usa')
		),
		'na_usa_westvirginia' => array(
			'name' => 'West Virginia',
			'borders' => array('na_usa_kentucky','na_usa_maryland','na_usa_ohio','na_usa_pennsylvania','na_usa_virginia'),
			'continents' => array('na_usa','na_colonies','na_iroquois')
		),
		'na_usa_wisconsin' => array(
			'name' => 'Wisconsin',
			'borders' => array('na_usa_illinois','na_usa_iowa','na_usa_michigan','na_usa_minnesota'),
			'continents' => array('na_usa')
		),
		'na_usa_wyoming' => array(
			'name' => 'Wyoming',
			'borders' => array('na_usa_colorado','na_usa_idaho','na_usa_montana','na_usa_nebraska','na_usa_southdakota','na_usa_utah'),
			'continents' => array('na_usa')
		)
	),
	'continents' => array(
		'na_canada' => array(
			'name' => 'Canada',
			'units' => 3,
			'nations' => array('na_ca_alberta','na_ca_britishcolombia','na_ca_manitoba','na_ca_newbrunswick','na_ca_newfoundland','na_ca_northwestterritories','na_ca_novascotia','na_ca_nunavut','na_ca_ontario','na_ca_princeedwardisland','na_ca_quebec','na_ca_saskatchewan','na_ca_yukon')
		),
		'na_confederacy' => array(
			'name' => 'Confederated States of America',
			'units' => 2,
			'nations' => array('na_usa_alabama','na_usa_arkansas','na_usa_florida','na_usa_georgia','na_usa_louisiana','na_usa_mississippi','na_usa_northcarolina','na_usa_southcarolina','na_usa_tennessee','na_usa_texas','na_usa_virginia')
		),
		'na_colonies' => array(
			'name' => 'Thirteen United States of America',
			'units' => 3,
			'nations' => array('na_usa_connecticut','na_usa_delaware','na_usa_georgia','na_usa_kentucky','na_usa_maine','na_usa_maryland','na_usa_massachusetts','na_usa_newhampshire','na_usa_newjersey','na_usa_newyork','na_usa_northcarolina','na_usa_pennsylvania','na_usa_rhodeisland','na_usa_southcarolina','na_usa_tennessee','na_usa_vermont','na_usa_virginia','na_usa_westvirginia')
		),
		'na_iroquois' => array(
			'name' => 'Iroquois League',
			'units' => 2,
			'nations' => array('na_usa_illinois','na_usa_indiana','na_usa_kentucky','na_usa_michigan','na_usa_newyork','na_usa_ohio','na_usa_pennsylvania','na_usa_westvirginia')
		),
		'na_mexico' => array(
			'name' => 'United Mexican States',
			'units' => 5,
			'nations' => array('na_mx_aguascalientes','na_mx_bajacalifornia','na_mx_bajacaliforniasur','na_mx_campeche','na_mx_chiapas','na_mx_chihuahua','na_mx_coahuila','na_mx_colima','na_mx_durango','na_mx_guanajuato','na_mx_guerrero','na_mx_hidalgo','na_mx_jalisco','na_mx_mexico','na_mx_mexicocity','na_mx_michoacan','na_mx_morelos','na_mx_nayarit','na_mx_nuevoleon','na_mx_oaxaca','na_mx_puebla','na_mx_queretaro','na_mx_quintanaroo','na_mx_sanluispotosi','na_mx_sinaloa','na_mx_sonora','na_mx_tabasco','na_mx_tamaulipas','na_mx_tlaxcala','na_mx_veracruz','na_mx_yucatan','na_mx_zacatecas')
		),
		'na_usa' => array(
			'name' => 'United States of America',
			'units' => 2,
			'nations' => array('na_usa_alabama','na_usa_alaska','na_usa_arizona','na_usa_arkansas','na_usa_california','na_usa_colorado','na_usa_connecticut','na_usa_delaware','na_usa_florida','na_usa_georgia','na_usa_hawaii','na_usa_idaho','na_usa_illinois','na_usa_indiana','na_usa_iowa','na_usa_kansas','na_usa_kentucky','na_usa_louisiana','na_usa_maine','na_usa_maryland','na_usa_massachusetts','na_usa_michigan','na_usa_minnesota','na_usa_mississippi','na_usa_missouri','na_usa_montana','na_usa_nebraska','na_usa_nevada','na_usa_newhampshire','na_usa_newjersey','na_usa_newmexico','na_usa_newyork','na_usa_northdakota','na_usa_northcarolina','na_usa_ohio','na_usa_oklahoma','na_usa_oregon','na_usa_pennsylvania','na_usa_rhodeisland','na_usa_southdakota','na_usa_southcarolina','na_usa_tennessee','na_usa_texas','na_usa_utah','na_usa_vermont','na_usa_virginia','na_usa_washington','na_usa_westvirginia','na_usa_wisconsin','na_usa_wyoming')
		),
		'na_newspain' => array(
			'name' => 'Viceroyalty of New Spain',
			'units' => 2,
			'nations' => array('na_cuba','na_mx_aguascalientes','na_mx_bajacalifornia','na_mx_bajacaliforniasur','na_mx_campeche','na_mx_chiapas','na_mx_chihuahua','na_mx_coahuila','na_mx_colima','na_mx_durango','na_mx_guanajuato','na_mx_guerrero','na_mx_hidalgo','na_mx_jalisco','na_mx_mexico','na_mx_mexicocity','na_mx_michoacan','na_mx_morelos','na_mx_nayarit','na_mx_nuevoleon','na_mx_oaxaca','na_mx_puebla','na_mx_queretaro','na_mx_quintanaroo','na_mx_sanluispotosi','na_mx_sinaloa','na_mx_sonora','na_mx_tabasco','na_mx_tamaulipas','na_mx_tlaxcala','na_mx_veracruz','na_mx_yucatan','na_mx_zacatecas','na_usa_arizona','na_usa_california','na_usa_nevada','na_usa_newmexico','na_usa_texas','na_usa_utah')
		)
	),
	'missions' => array(
		array(
			'name' => 'World Domination',
			'description' => 'To win this game you will have to conquer 50 territories on the map.',
			'conditions' => array('territories' => 50)
		),
		array(
			'name' => 'Rivalry',
			'description' => 'To win this game you will have to conquer the last of <user>\'s territories.',
			'conditions' => array('eliminate' => 1),
			'fallback' => 0
		),
		array(
			'name' => 'Uncle Sam',
			'description' => 'To win this game you have to control the United States of America.',
			'conditions' => array('continent' => array('na_usa'))
		),
		array(
			'name' => 'Old Times',
			'description' => 'To win this game you have to control the Viceroyalty of New Spain and the Confederated States of America.',
			'conditions' => array('continent' => array('na_newspain','na_confederacy'))
		),
		array(
			'name' => 'Native Power',
			'description' => 'To win this game you have to control the United Mexican States and the Iroquois League.',
			'conditions' => array('continent' => array('na_mexico','na_iroquois'))
		),
		array(
			'name' => 'Canadian Expansion',
			'description' => 'To win this game you have to control Canada and 35 other territories (48 total).',
			'conditions' => array('continent' => array('na_canada'), 'territories' => 48)
		)
	),
	'mission_distribution' => array(1,1,1,2,3,4,5)
);
?>