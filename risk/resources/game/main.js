var $GAME = (function($) {
	var $PLAYERS,$NATIONS,$USER,$GAME,$CONTINENTS,$ATTACKS,
	$PAGE = {
		tab: ['territories']
	},
	$userIsPlayer = false,
	$loading = true,
	$cardNames = {
		'c_art': 'Artillery',
		'c_inf': 'Infantry',
		'c_cav': 'Cavalry',
		'c_jok': 'Wildcard'
	},
	$basePath = '/risk/',
	$ajaxPath = $basePath+'ajax.php',
	$numChats = 0;

	if(Number.parseInt === undefined) {
		Number.parseInt = parseInt;
	}

	function makeSVG($elem,$attr) {
		var $k,
		$out = document.createElementNS('http://www.w3.org/2000/svg',$elem);
		if($attr !== undefined) {
			for($k in $attr) {
				$out.setAttribute($k,$attr[$k]);
			}
		}
		return $out;
	}

	function init($gid,$uid,$path) {
		$basePath = $path;
		$ajaxPath = $basePath+'ajax.php';
		$(document).ready(function() {
			var $h1;
			if($loading) {
				$('body').html('<h1>Loading...</h1>');
				$h1 = $('body h1');
				window.setTimeout(function loadAnim() {
					if($loading) {
						if($h1.text() == 'Loading...') {
							$h1.text('Loading');
						} else if($h1.text() == 'Loading') {
							$h1.text('Loading.');
						} else if($h1.text() == 'Loading.') {
							$h1.text('Loading..');
						} else {
							$h1.text('Loading...');
						}
						window.setTimeout(loadAnim,500);
					}
				},500);
			}
		});
		$.ajax({
			type: 'POST',
			url: $ajaxPath,
			data: {
				mode: 'game',
				action: 'update',
				gid: $gid,
				time: 0,
				update: 'all'
			}
		}).done(function($responseText) {
			var $json,$n,$length,$id;

			try {
				$json = JSON.parse($responseText);
			} catch($e) {
				return errorMessage($responseText);
			}

			$PLAYERS = $json.PLAYERS;
			$NATIONS = {};
			for($n=0,$length=$json.NATIONS.length;$n < $length;$n++) {
				$id = $json.NATIONS[$n].nation;
				$NATIONS[$id] = $json.MAPDATA.NATIONS[$id];
				$NATIONS[$id].uid = $json.NATIONS[$n].uid;
				$NATIONS[$id].units = $json.NATIONS[$n].units;
			}
			$CONTINENTS = $json.MAPDATA.CONTINENTS;
			if($json.USER !== undefined) {
				$USER = $json.USER;
				$USER.cards = $json.CARDS;
				$USER.rolls = [];
				$userIsPlayer = true;
			} else {
				$USER = {};
			}
			$USER.uid = $uid;
			$ATTACKS = $json.ATTACKS;
			$GAME = $json.GAME;

			createInterface($json.combatlog);
		}).fail(function() {
			errorMessage('Failed to load game data.');
		});
		this.init = function() {
			return 'Interface already initialised.';
		};
	}

	function errorMessage($msg) {
		alert($msg);
		location.reload();
	}

	function parseHash() {
		var $page = window.location.hash.replace('#','');
		if($page !== '') {
			if($page == 'players' || $page == 'regions' || $page == 'territories' || ($userIsPlayer && ($page == 'cards' || $page == 'chat' || $page == 'settings' || $page == 'log'))) {
				$PAGE.tab = [$page];
			} else if($page.indexOf('territory-') === 0) {
				$page = $page.split('-');
				if($NATIONS[$page[1]] !== undefined) {
					$PAGE.tab = $page;
				}
			}
		} else {
			$PAGE.tab = ['territories'];
		}
	}

	function createInterface($combatLog) {
		$.ajax({
			type: 'GET',
			url: $ajaxPath,
			data: {
				mode: 'game',
				action: 'getgui'
			}
		}).fail(function() {
			errorMessage('Failed to load GUI.');
		}).done(function($responseText) {
			var $player,$n,$i,$elem,$c,$length,$elem2,$elem3,$defs;

			$loading = false;
			$('body').html($responseText);
			$.ajax({
				type: 'GET',
				url: $basePath+'resources/game/patterns.svg',
				dataType: 'xml'
			}).fail(function() {
				errorMessage('Failed to load unit graphics.');
			}).done(function($svg) {
				var $map = $('#mapWrapper svg');
				$defs = $svg.documentElement.getElementsByTagName('defs')[0];
				if($map.length > 0) {
					$map.append($defs);
				}
			});
			$.ajax({
				type: 'GET',
				url: $basePath+'game/maps/'+$GAME.map+'/inc/map.svg',
				dataType: 'xml'
			}).fail(function() {
				errorMessage('Failed to load map.');
			}).done(function($svg) {
				var $nav;
				if($defs !== undefined) {
					$svg.documentElement.appendChild($defs);
				}
				$('#mapWrapper').append($svg.documentElement);
				addMapUnitBoxes();
				setEventListeners();
				if($userIsPlayer) {
					$CHAT.init($GAME.uid == $USER.uid,$GAME.gid,$PLAYERS,false,$ajaxPath,{input: $('#chat .input input'),chatDiv: $('#chat .window'),scrollInput: $('#tabSettings input').eq(1)});
					$CHAT.addHook = addChatNumber;
					window.setTimeout(requestUpdate,2000);
					$('#tabPlayers').append('<h3>Mission: '+$USER.mission.name+'</h3><p>'+getMissionDescription()+'</p>');
					checkRolls();
				} else {
					$nav = $('nav li');
					$nav.find('a[href="#cards"]').parent().hide();
					$nav.find('a[href="#chat"]').parent().hide();
					$nav.find('a[href="#log"]').parent().hide();
					$nav.find('a[href="#settings"]').parent().hide();
				}
				if($combatLog !== undefined) {
					addCombatLogs($combatLog,true);
				}
				changeMapScale();
				update();
			});

			parseHash();

			$('h1:first').text($GAME.name);
			$elem = $('#tabPlayers tbody');
			for($player in $PLAYERS) {
				$elem.append($('<tr data-player="'+$player+'"><td><a href="'+$PLAYERS[$player].url+'" style="color: #'+$PLAYERS[$player].color+';">'+$PLAYERS[$player].displayname+'</a></td><td></td><td></td><td></td></tr>'));
			}
			$elem3 = $('#tabTerritories tbody');
			for($n in $NATIONS) {
				$elem = $('<tr data-nation="'+$n+'"><td><img alt="" src="'+$basePath+'resources/flags/'+$n.replace(/_/g,'/')+'.png" /></td><td><a href="#territory-'+$n+'">'+$NATIONS[$n].name+'</a></td><td></td><td></td><td></td></tr>');
				$elem2 = $elem.find('td:last');
				for($i=0;$i < $NATIONS[$n].continents.length;$i++) {
					$elem2.append('<img alt="'+$CONTINENTS[$NATIONS[$n].continents[$i]].name+' " src="'+$basePath+'resources/flags/'+$NATIONS[$n].continents[$i].replace(/_/g,'/')+'.png" />');
				}
				$elem3.append($elem);
			}
			$elem2 = $('#tabRegions ul:first');
			for($c in $CONTINENTS) {
				$length = $CONTINENTS[$c].nations.length;
				$elem = $('<li data-region="'+$c+'"><h2><img src="'+$basePath+'resources/flags/'+$c.replace(/_/g,'/')+'.png" alt="" /> '+$CONTINENTS[$c].name+' <button class="highlightRegion" data-region="'+$c+'">Highlight</button></h2><span>This region provides '+$CONTINENTS[$c].units+' units per turn to the owner.</span><div class="ownerPercent"></div><div class="spoiler"><h3>Territories ('+$length+') <button>Show</button></h3><ul style="display: none;" class="territories"></ul></div></li>');
				$elem3 = $elem.find('ul');
				for($n=0;$n < $length;$n++) {
					$elem3.append('<li style="background-image: url(\''+$basePath+'resources/flags/'+$CONTINENTS[$c].nations[$n].replace(/_/g,'/')+'.png\');"><a href="#territory-'+$CONTINENTS[$c].nations[$n]+'">'+$NATIONS[$CONTINENTS[$c].nations[$n]].name+'</a></li>');
				}
				$elem2.append($elem);
			}
			$('#tabNation').append('<h2>Nation</h2>Player: <span></span>. <span></span> units.<div></div><h3>Borders</h3><ul class="territories"></ul><h3>Regions</h3><ul class="territories"></ul>');
		});
	}

	function addMapUnitBoxes() {
		var $n,$bounds,$dims,
		$g = makeSVG('g',{'class': 'units'}),
		$margin = 25;
		for($n in $NATIONS) {
			$bounds = document.getElementById($n).getBBox();
			$dims = {
				x: $bounds.x+$bounds.width/2,
				y: $bounds.y+$bounds.height/2,
				r: ($bounds.width+$bounds.height)/200*(100-$margin*2)
			};
			$g.appendChild(makeSVG('rect',{x: $dims.x-$dims.r/2, y: $dims.y-$dims.r/2, height: $dims.r, width: $dims.r, id: 'unitBox-'+$n}));
		}
		$('#mapWrapper svg').append($g);
		if($EEstore.getItem('unitsOnMap') == 'off') {
			$($g).hide();
		}
	}

	function getUnitPattern($number) {
		var $text,
		$pattern = document.getElementById('pattern-units-numbers-'+$number),
		$width = Math.floor(Math.log($number)/Math.LN10)*8+8;
		if($pattern === null) {
			$pattern = makeSVG('pattern',{width: 1, height: 1, id: 'pattern-units-numbers-'+$number, viewBox: '0 0 '+$width+' 16'});
			$text = makeSVG('text',{x: 0, y: 14});
			$text.appendChild(document.createTextNode($number));
			$pattern.appendChild($text);
			$('#mapWrapper svg defs').append($pattern);
		}
		return '#pattern-units-numbers-'+$number;
	}

	function updateMapUnitBoxes() {
		var $n,$units,
		$unitsOnMap = $EEstore.getItem('unitsOnMap','icon');
		if($unitsOnMap == 'number') {
			for($n in $NATIONS) {
				$('#unitBox-'+$n).attr('fill','url('+getUnitPattern($NATIONS[$n].units)+') none');
			}
		} else {
			for($n in $NATIONS) {
				$units = $NATIONS[$n].units;
				if($units >= 50) {
					$units = 50;
				} else if($units >= 40) {
					$units = 40;
				} else if($units >= 30) {
					$units = 30;
				} else if($units > 20) {
					$units = 20;
				}
				$('#unitBox-'+$n).attr('fill','url(#pattern-units-'+$units+') none');
			}
		}
	}

	function getMissionDescription() {
		var $desc = $USER.mission.description;
		if($USER.m_uid !== 0) {
			$desc = $desc.replace(/<user>/gi,'<span style="color: #'+$PLAYERS[$USER.m_uid].color+';">'+$PLAYERS[$USER.m_uid].displayname+'</span>');
		}
		return $desc;
	}

	function requestUpdate() {
		if($USER.rolls.length === 0) {
			$.ajax({
				type: 'POST',
				url: $ajaxPath,
				data: {
					mode: 'game',
					gid: $GAME.gid,
					action: 'update',
					time: $GAME.time,
					logs: $('#tabLog p').length
				}
			}).fail(function() {
				console.error('Failed to get game data.');
				window.setTimeout(requestUpdate,500);
			}).done(function($responseText) {
				var $json,$n,$length;
				window.setTimeout(requestUpdate,2010);
				if($responseText === '') {
					return;
				}
				try {
					$json = JSON.parse($responseText);
				} catch($er) {
					return errorMessage($responseText);
				}
				for($n in $json.GAME) {
					$GAME[$n] = $json.GAME[$n];
				}
				if($USER.mission.name != $json.USER.mission.name) {
					$USER.mission.name = $json.USER.mission.name;
					$USER.mission.description = $json.USER.mission.description;
					$USER.m_uid = $json.USER.m_uid;
					$('#tabPlayers h3:first').text('Mission: '+$USER.mission.name);
					$('#tabPlayers p:first').html(getMissionDescription());
				}
				$ATTACKS = $json.ATTACKS;
				$USER.cards = $json.CARDS;
				for($n=0,$length=$json.NATIONS.length;$n < $length;$n++) {
					$NATIONS[$json.NATIONS[$n].nation].units = $json.NATIONS[$n].units;
					$NATIONS[$json.NATIONS[$n].nation].uid = $json.NATIONS[$n].uid;
				}
				addCombatLogs($json.combatlog);
				update();
				checkRolls();
			});
		} else {
			window.setTimeout(requestUpdate,1000);
		}
	}

	function addCombatLogs($logs,$init) {
		var $n,$length,$p,$time,
		$window = $('#tabLog > div:first');
		for($n=0,$length=$logs.length;$n < $length;$n++) {
			$p = $('<p>');
			$time = new Date($logs[$n].time);
			$p.append('<time title="'+$time.toLocaleTimeString()+'" datetime="'+$logs[$n].time+'">'+$time.toLocaleTimeString()+'</time> <span>'+$logs[$n].message+'</span>');
			$window.append($p);
		}
		if(!$init && $PAGE.tab[0] != 'log') {
			addNumber($('nav a[href="#log"] .number'),$length);
		}
	}

	function addNumber($elem,$num) {
		var $val = Number.parseInt($elem.text());
		if(isNaN($val)) {
			$val = 0;
		}
		$val += $num;
		if($val < 1) {
			$val = '';
		}
		$elem.text($val);
	}

	function addChatNumber($msg,$sync) {
		var $added = $msg.length;
		if($sync) {
			$added = $msg.length-$numChats;
		}
		if($PAGE.tab[0] != 'chat' && $added > 0 && $numChats > 0) {
			addNumber($('nav a[href="#chat"] .number'),$added);
		}
		$numChats += $added;
	}

	function update() {
		var $n,$elem;
		updateFooter();
		if($PAGE.tab[0] == 'players') {
			tabPlayers();
			$elem = $('nav a[href="#players"]');
		} else if($PAGE.tab[0] == 'territories') {
			tabTerritories();
			$elem = $('nav a[href="#territories"]');
		} else if($PAGE.tab[0] == 'regions') {
			tabRegions();
			$elem = $('nav a[href="#regions"]');
		} else if($PAGE.tab[0] == 'cards') {
			tabCards();
			$elem = $('nav a[href="#cards"]');
		} else if($PAGE.tab[0] == 'chat') {
			tabChat();
			$elem = $('nav a[href="#chat"]');
			$elem.find('.number').empty();
		} else if($PAGE.tab[0] == 'settings') {
			tabSettings();
			$elem = $('nav a[href="#settings"]');
		} else if($PAGE.tab[0] == 'territory') {
			tabNation($PAGE.tab[1]);
		} else if($PAGE.tab[0] == 'log') {
			tabLog();
			$elem = $('nav a[href="#log"]');
			$elem.find('.number').empty();
		}
		if($elem !== undefined) {
			$elem.addClass('active');
		}
		for($n in $NATIONS) {
			$('#'+$n).css('fill','#'+$PLAYERS[$NATIONS[$n].uid].color);
		}
		updateMapUnitBoxes();
	}

	function checkRolls() {
		var $n,$length,
		$rolls = [];
		for($n=0,$length=$ATTACKS.length;$n < $length;$n++) {
			if($ATTACKS[$n].d_uid == $USER.uid) {
				$rolls.push($ATTACKS[$n]);
			}
		}
		$length = $USER.rolls.length;
		$USER.rolls = $rolls;
		if($length === 0) {
			defendRoll();
		}
	}

	function defendRoll() {
		var $roll,$msg,$overlay,$elem,$n,$select,$form;
		if($USER.rolls.length > 0) {
			$overlay = $('#overlay');
			if($overlay.css('display') == 'block') {
				window.setTimeout(defendRoll,1000);
			} else {
				$roll = $USER.rolls.pop();
				$roll.a_roll += '';
				$msg = $('#overlay_message');
				$msg.html('<h2><span style="color: #'+$PLAYERS[$roll.a_uid].color+';">'+$NATIONS[$roll.a_nation].name+'</span> ('+$NATIONS[$roll.a_nation].units+') vs <span style="color: #'+$PLAYERS[$USER.uid].color+';">'+$NATIONS[$roll.d_nation].name+'</span> ('+$NATIONS[$roll.d_nation].units+')</h2><h3>'+$PLAYERS[$roll.a_uid].displayname+' Rolled</h3>');
				$elem = $('<p></p>');
				for($n=0;$n < $roll.a_roll.length;$n++) {
					$elem.append('<div class="d'+$roll.a_roll[$n]+' die_attack"></div>');
				}
				$msg.append($elem);
				$msg.append('<p>How many units do you wish to defend with?</p>');
				$form = $('<form method="post"></form>');
				$select = $('<select><option value="1">1</option></select>');
				if($NATIONS[$roll.d_nation].units > 1) {
					$select.append('<option value="2" selected>2</option>');
				}
				$form.append($select);
				$elem = $('<input type="submit" value="Defend" />');
				$form.append($elem);
				$msg.append($form);
				$form.submit(function($e) {
					$e.preventDefault();
					defendRollReq($select.val(),$roll);
				});
				$overlay.show();
				$select.focus();
			}
		}
	}

	function defendRollReq($units,$roll) {
		if($units < 3 && $units <= $NATIONS[$roll.d_nation].units && $NATIONS[$roll.d_nation].uid == $USER.uid) {
			loadScreen();
			$.ajax({
				type: 'POST',
				url: $ajaxPath,
				data: {
					mode: 'game',
					gid: $GAME.gid,
					action: 'defend',
					a_nat: $roll.a_nation,
					d_nat: $roll.d_nation,
					attacker: $roll.a_uid,
					units: $units
				}
			}).fail(function() {
				console.error('Failed to connect.');
				window.setTimeout(function() {
					defendRollReq($units,$roll);
				},500);
			}).done(function($responseText) {
				var $json,$msg,$elem,$n;
				try {
					$json = JSON.parse($responseText);
				} catch($er) {
					return errorMessage($responseText);
				}
				$('#overlay').removeClass();
				$msg = $('#overlay_message');
				$msg.html('<h2><span style="color: #'+$PLAYERS[$roll.a_uid].color+';">'+$NATIONS[$roll.a_nation].name+'</span> ('+$NATIONS[$roll.a_nation].units+') vs <span style="color: #'+$PLAYERS[$USER.uid].color+';">'+$NATIONS[$roll.d_nation].name+'</span> ('+$NATIONS[$roll.d_nation].units+')</h2><h3>'+$PLAYERS[$roll.a_uid].displayname+' Rolled</h3>');
				$elem = $('<p></p>');
				for($n=0;$n < $roll.a_roll.length;$n++) {
					$elem.append('<div class="d'+$roll.a_roll[$n]+' die_attack"></div>');
				}
				$msg.append($elem);
				$msg.append('<h3>You Rolled</h3>');
				$elem = $('<p></p>');
				for($n=0;$n < $json.roll.length;$n++) {
					$elem.append('<div class="d'+$json.roll[$n]+' die_defend"></div>');
				}
				$msg.append($elem);
				$elem = $('<button>Close</button>');
				$elem.click(function() {
					closeMessage();
					defendRoll();
				});
				$msg.append($elem);
				$NATIONS[$roll.a_nation].units -= $json.attackLosses;
				$NATIONS[$roll.d_nation].units -= $json.defendLosses;
				if($NATIONS[$roll.d_nation].units === 0) {
					$NATIONS[$roll.d_nation].units = $json.transfer;
					$NATIONS[$roll.a_nation].units -= $json.transfer;
					$NATIONS[$roll.d_nation].uid = $roll.a_uid;
				}
				$GAME.time = $json.time;
				update();
				$elem.focus();
			});
		} else {
			closeMessage();
		}
	}

	function continentOwner($continent) {
		var $n,$length,
		$nations = $CONTINENTS[$continent].nations,
		$owner = $NATIONS[$nations[0]].uid;
		for($n=1,$length=$nations.length;$n < $length;$n++) {
			if($NATIONS[$nations[$n]].uid != $owner) {
				return false;
			}
		}
		return $owner;
	}

	function hideTabs() {
		$('.active').removeAttr('class','');
		$('.highlightRegion').text('Highlight');
	}

	function tabPlayers($e) {
		var $players,$p,$c,$uid,$u,$n,$elem,
		$unitCount = 0,
		$nationCount = 0;
		hideTabs();

		$players = {};
		for($p in $PLAYERS) {
			$players[$p] = {
				nations: 0,
				units: 0,
				upert: 0
			};
		}
		for($n in $NATIONS) {
			$nationCount++;
			$players[$NATIONS[$n].uid].nations++;
			$players[$NATIONS[$n].uid].units += $NATIONS[$n].units;
			$unitCount += $NATIONS[$n].units;
		}
		for($c in $CONTINENTS) {
			$uid = continentOwner($c);
			if($uid !== false) {
				$players[$uid].upert += $CONTINENTS[$c].units;
			}
		}
		for($p in $players) {
			$u = Math.floor($players[$p].nations/3);
			if($u < 3) {
				$u = 3;
			}
			$elem = $('#tabPlayers tr[data-player="'+$p+'"] td');
			$elem.eq(1).text($players[$p].nations+' ('+(Math.round($players[$p].nations/$nationCount*1000)/10)+'%)');
			$elem.eq(2).text($players[$p].units+' ('+(Math.round($players[$p].units/$unitCount*1000)/10)+'%)');
			$elem.eq(3).text(($u+$players[$p].upert)+' ('+$u+' + '+$players[$p].upert+')');
		}
		$PAGE.tab = ['players'];
		$('#tabPlayers').addClass('active');
		if($e !== undefined) {
			$(this).addClass('active');
			$e.preventDefault();
		}
	}

	function tabTerritories($e) {
		var $n,$elem;
		hideTabs();

		for($n in $NATIONS) {
			$elem = $('#tabTerritories tr[data-nation="'+$n+'"] td');
			$elem.eq(2).html('<span style="color: #'+$PLAYERS[$NATIONS[$n].uid].color+';">'+$PLAYERS[$NATIONS[$n].uid].displayname+'</span>');
			$elem.eq(3).text($NATIONS[$n].units);
		}
		$PAGE.tab = ['territories'];
		$('#tabTerritories').addClass('active');
		if($e !== undefined) {
			$(this).addClass('active');
			$e.preventDefault();
		}
	}

	function tabRegions($e) {
		var $bar,$owners,$p,$n,$c;
		hideTabs();

		for($c in $CONTINENTS) {
			$bar = $('#tabRegions li[data-region="'+$c+'"] .ownerPercent');
			$owners = {};
			for($p in $PLAYERS) {
				$owners[$p] = 0;
			}
			for($n in $CONTINENTS[$c].nations) {
				$owners[$NATIONS[$CONTINENTS[$c].nations[$n]].uid]++;
			}
			$bar.empty();
			for($p in $owners) {
				$owners[$p] = Math.round($owners[$p]*1000/$CONTINENTS[$c].nations.length)/10;
				$bar.append('<div style="background-color: #'+$PLAYERS[$p].color+'; width: '+$owners[$p]+'%" title="'+$owners[$p]+'%"></div>');
			}
		}
		$PAGE.tab = ['regions'];
		$('#tabRegions').addClass('active');
		if($e !== undefined) {
			$(this).addClass('active');
			$e.preventDefault();
		}
	}


	function tabNation($nation) {
		var $ul,$n,$length,$nat,$reg,$elem;
		hideTabs();

		$('#tabNation h2:first').html('<img src="'+$basePath+'resources/flags/'+$nation.replace(/_/g,'/')+'.png" alt="" />'+$NATIONS[$nation].name);
		$('#tabNation div:first').empty();
		$elem = $('#tabNation span');
		$elem.eq(0).text($PLAYERS[$NATIONS[$nation].uid].displayname);
		$elem.eq(0).css('color','#'+$PLAYERS[$NATIONS[$nation].uid].color);
		$elem.eq(1).text($NATIONS[$nation].units);
		$ul = $('#tabNation ul');
		$ul.empty();
		$elem = $ul.eq(0);
		for($n=0,$length=$NATIONS[$nation].borders.length;$n < $length;$n++) {
			$nat = $NATIONS[$nation].borders[$n];
			$elem.append('<li style="background-image: url(\''+$basePath+'resources/flags/'+$nat.replace(/_/g,'/')+'.png\');" data-nation="'+$nat+'"><a href="#territory-'+$nat+'" style="color: #'+$PLAYERS[$NATIONS[$nat].uid].color+';">'+$NATIONS[$nat].name+'</a> ('+$NATIONS[$nat].units+')</li>');
			$('#'+$nat).attr('class','active border');
		}
		$elem = $ul.eq(1);
		for($n=0,$length=$NATIONS[$nation].continents.length;$n < $length;$n++) {
			$reg = $NATIONS[$nation].continents[$n];
			$elem.append('<li style="background-image: url(\''+$basePath+'resources/flags/'+$reg.replace(/_/g,'/')+'.png\');">'+$CONTINENTS[$reg].name+'</li>');
		}
		$PAGE.tab = ['territory',$nation];
		$('#tabNation').addClass('active');
		$('#'+$nation).attr('class','active');
		if($userIsPlayer) {
			tabNationButtons();
		}
	}

	function tabChat($e) {
		hideTabs();
		$PAGE.tab = ['chat'];
		$('#chat').addClass('active');
		if($e !== undefined) {
			$(this).addClass('active');
			$e.preventDefault();
		}
		$('#chat .input input').focus();
		$CHAT.updateScroll();
	}

	function tabCards($e) {
		var $c,$n,$combinations,$amount,$button,$elem;
		hideTabs();
		$('#tabCards').html('<div></div>');
		$elem = $('#tabCards div');
		for($c in $USER.cards) {
			for($n=0;$n < $USER.cards[$c];$n++) {
				$elem.append('<img src="'+$basePath+'resources/game/'+$c+'.png" alt="'+$cardNames[$c]+'" class="card" />');
			}
		}
		$elem = $('#tabCards');
		if($GAME.turn == $USER.uid && $GAME.state < 2) {
			$combinations = getCardCombinations();
			$amount = [4,6,8,10];
			for($n=0;$n < $combinations.length;$n++) {
				$button = $('<button data-units="'+$amount[$combinations[$n]]+'">Draft '+$amount[$combinations[$n]]+' units</button>');
				$button.click(useCardCombination);
				$elem.append($button);
			}
		}
		$PAGE.tab = ['cards'];
		$elem.addClass('active');
		if($e !== undefined) {
			$(this).addClass('active');
			$e.preventDefault();
		}
	}

	function tabSettings($e) {
		var $input = $('#tabSettings input'),
		$unitsOnMap = $EEstore.getItem('unitsOnMap','icon');
		if($unitsOnMap != 'off' && $unitsOnMap != 'number') {
			$unitsOnMap = 'icon';
		}
		hideTabs();
		$input[0].checked = $PLAYERS[$USER.uid].autoroll;
		$input[1].checked = $CHAT.getScroll();
		$input[2].checked = $EEstore.getBoolean('logScrolling',true);
		$('#tabSettings input[name="unitgraphics"][value="'+$unitsOnMap+'"]').prop('checked',true);
		$PAGE.tab = ['settings'];
		$('#tabSettings').addClass('active');
		if($e !== undefined) {
			$(this).addClass('active');
			$e.preventDefault();
		}
	}

	function tabLog($e) {
		var $log;
		hideTabs();
		$PAGE.tab = ['log'];
		$('#tabLog').addClass('active');
		if($EEstore.getBoolean('logScrolling',true)) {
			$log = $('#tabLog div');
			$log.scrollTop($log[0].scrollHeight);
		}
		if($e !== undefined) {
			$(this).addClass('active');
			$e.preventDefault();
		}
	}

	function getCardCombinations() {
		var $combinations = [];
		if($USER.cards.c_art+$USER.cards.c_jok > 2) {
			$combinations.push(0);
		}
		if($USER.cards.c_inf+$USER.cards.c_jok > 2) {
			$combinations.push(1);
		}
		if($USER.cards.c_cav+$USER.cards.c_jok > 2) {
			$combinations.push(2);
		}
		if(	($USER.cards.c_jok == 2 && $USER.cards.c_art+$USER.cards.c_inf+$USER.cards.c_cav > 0) || ($USER.cards.c_art > 0 && $USER.cards.c_inf > 0 && $USER.cards.c_cav > 0) || ($USER.cards.c_jok === 1 && (($USER.cards.c_art > 0 && $USER.cards.c_inf > 0) || ($USER.cards.c_art > 0 && $USER.cards.c_cav > 0) || ($USER.cards.c_inf > 0 && $USER.cards.c_cav > 0)))) {
			$combinations.push(3);
		}
		return $combinations;
	}

	function mapClickNation() {
		window.location = '#territory-'+this.id;
	}

	function setEventListeners() {
		var $input;
		$(window).on('hashchange',function() {
			parseHash();
			update();
		});
		$('#mapScaleSlider').change(changeMapScale);
		if($userIsPlayer) {
			$input = $('#tabSettings input');
			$input.eq(0).change(autoRoll);
			$input.eq(2).change(function() {
				$EEstore.setItem('logScrolling',this.checked);
			});
			$('#tabSettings input[name="unitgraphics"]').change(function() {
				var $value = $(this).filter(':checked').val();
				$EEstore.setItem('unitsOnMap',$value);
				if($value != 'off') {
					updateMapUnitBoxes();
					$('#mapWrapper .units').show();
				} else {
					$('#mapWrapper .units').hide();
				}
			});
		}
		$('#mapWrapper g').each(function() {
			if(this.id !== '') {
				$(this).click(mapClickNation);
			}
		});
		$('.spoiler').each(function() {
			$(this).find('button:first').click(spoiler);
		});
		$('.highlightRegion').click(highlightRegionButton);
	}

	function autoRoll() {
		$.ajax({
			type: 'POST',
			url: $ajaxPath,
			data: {
				gid: $GAME.gid,
				mode: 'game',
				action: 'autoroll'
			}
		}).fail(function() {
			console.error('Failed to connect.');
			window.setTimeout(autoRoll,500);
		}).done(function($responseText) {
			var $json;
			try {
				$json = JSON.parse($responseText);
			} catch($er) {
				return errorMessage($responseText);
			}
			$PLAYERS[$USER.uid].autoroll = $json;
			update();
		});
	}

	function changeMapScale() {
		var $scale = Number.parseInt($('#mapScaleSlider').val(),10);
		if($scale < 250) {
			$scale = 250;
		} else if($scale > 1750) {
			$scale = 1750;
		} else if(isNaN($scale)) {
			$scale = 1000;
		}
		$('#mapWrapper').css({
			height: $scale+'px',
			width: $scale+'px'
		});
	}

	function spoiler() {
		var $this = $(this),
		$elem = $this.parent().next();
		if($elem.css('display') != 'none') {
			$this.text('Show');
			$elem.hide();
		} else {
			$this.text('Hide');
			$elem.show();
		}
	}

	function highlightRegionButton() {
		var $nid,$c,$n,$length,
		$h = false,
		$this = $(this);
		if($this.text() == 'Highlight') {
			$h = true;
		}
		hideTabs();
		$('nav a[href="#regions"]').addClass('active');
		$('#tabRegions').addClass('active');
		if($h) {
			for($nid in $NATIONS) {
				$('#'+$nid).attr('class','active border');
			}
			$c = $CONTINENTS[$this.attr('data-region')];
			for($n=0,$length=$c.nations.length;$n < $length;$n++) {
				$('#'+$c.nations[$n]).attr('class','active');
			}
			$this.text('Normal');
		}
	}

	function tabNationButtons() {
		var $button,$n,$length,
		$b = false,
		$nat = $PAGE.tab[1],
		$nation = $NATIONS[$nat],
		$div = $('#tabNation div:first');
		function getAttackClick($def) {
			return function() {
				attackScreen($PAGE.tab[1],$def);
			};
		}
		function getMoveClick($nat) {
			return function() {
				moveScreen($nat,$PAGE.tab[1]);
			};
		}
		if($GAME.turn != $USER.uid) {
			return;
		}
		if($GAME.state == 2 && inCombat($nat)) {
			return;
		}
		if($GAME.state < 2 && $GAME.units > 0 && $nation.uid == $USER.uid) {
			$button = $('<button>Place Units</button>');
			$button.click(placeUnits);
			$div.append($button);
		}
		if($GAME.state !== 1 && $GAME.state != 3) {
			if($nation.units > 1 && $nation.uid == $USER.uid) {
				for($n=0,$length=$nation.borders.length;$n < $length;$n++) {
					$nat = $nation.borders[$n];
					if($NATIONS[$nat].uid != $USER.uid && !inCombat($nat)) {
						$button = $('<button>Attack</button>');
						$button.click(getAttackClick($nat));
						$('#tabNation ul li[data-nation="'+$nat+'"]').append($button);
					}
				}
			} else if($nation.uid != $USER.uid) {
				for($n=0,$length=$nation.borders.length;$n < $length;$n++) {
					$nat = $nation.borders[$n];
					if($NATIONS[$nat].uid == $USER.uid && $NATIONS[$nat].units > 1 && !inCombat($nat)) {
						$b = true;
						break;
					}
				}
				if($b) {
					$button = $('<button>Attack</button>');
					$button.click(attackNation);
					$div.append($button);
				}
			}
		} else if($GAME.state == 3 && $GAME.units > 0 && $nation.uid == $USER.uid) {
			for($n=0,$length=$nation.borders.length;$n < $length;$n++) {
				$nat = $nation.borders[$n];
				if($NATIONS[$nat].uid == $USER.uid) {
					if($nation.units > 1) {
						$button = $('<button>Transfer</button>');
						$button.click(getMoveClick($nat));
						$('#tabNation ul li[data-nation="'+$nat+'"]').append($button);
					}
					if(!$b && $NATIONS[$nat].units > 1) {
						$b = true;
					}
				}
			}
			if($b) {
				$button = $('<button>Transfer</button>');
				$button.click(moveUnitsTo);
				$div.append($button);
			}
		}
	}

	function updateFooter() {
		var $node,
		$footer = $('#footer');
		if($GAME.turn != $USER.uid) {
			$footer.html('<li><a href="'+$PLAYERS[$GAME.turn].url+'"><span style="color: #'+$PLAYERS[$GAME.turn].color+';">'+$PLAYERS[$GAME.turn].displayname+'</span>\'s turn.</a></li>');
		} else {
			$footer.empty();
			if($GAME.units > 0 && $GAME.state < 2) {
				$node = $('<li><a href="" title="Units left to place.">'+$GAME.units+'</a></li>');
			} else if($GAME.state == 2) {
				$node = $('<li><a href="" title="Ongoing battles.">'+$ATTACKS.length+'</a></li>');
			} else if($GAME.units > 0 && $GAME.state == 3) {
				$node = $('<li><a href="" title="Moves left.">'+$GAME.units+'</a></li>');
			}
			if($node !== undefined) {
				$footer.append($node);
				$node.click(function($e) { $e.preventDefault(); });
			}
			if($GAME.state === 0) {
				$node = $('<li><a href="" title="Train new units without attacking.">Fortify ('+getUnitsPerTurnNat()+')</a></li>');
				$footer.append($node);
				$node.click(stackUnits);
			} else if($GAME.state == 2 && $ATTACKS.length === 0) {
				$node = $('<li><a href="" title="Start moving units.">Move</a></li>');
				$footer.append($node);
				$node.click(moveUnits);
			}
			$node = $('<li><a href="">End Turn</a></li>');
			$footer.append($node);
			$node.click(endTurn);
		}
		if($userIsPlayer && $ATTACKS.length === 0 && $USER.inGame && !hasAllTerritories($USER.uid)) {
			$node = $('<li style="float: right;"><a href="" title="Forfeit the game.">Forfeit</a></li>');
			$footer.append($node);
			$node.click(forfeit);
		}
	}

	function hasAllTerritories($uid) {
		for(var $nation in $NATIONS) {
			if($nation.uid !== $uid) {
				return false;
			}
		}
		return true;
	}

	function forfeit($e) {
		$e.preventDefault();
		if($ATTACKS.length !== 0) {
			window.alert('Please finish combat first.');
			return;
		}
		if(window.confirm('Are you sure you want to forfeit the game? This cannot be undone.')) {
			loadScreen();
			forfeitWithoutPrompt();
		}
	}

	function forfeitWithoutPrompt() {
		$.ajax({
			type: 'POST',
			url: $ajaxPath,
			async: false,
			data: {
				mode: 'game',
				gid: $GAME.gid,
				action: 'forfeit'
			}
		}).fail(window.setTimeout(forfeitWithoutPrompt,500)).done(function() {
			$USER.inGame = false;
			updateFooter();
			closeMessage();
		});
	}

	function useCardCombination() {
		var $units;
		if($GAME.state > 1) {
			return;
		}
		$units = $(this).attr('data-units');
		if($units != 4 && $units != 6 && $units != 8 && $units != 10) {
			return errorMessage('Illegal unit amount.');
		} else if(window.confirm('Are you sure you want to exchange these cards for '+$units+' units?')) {
			useCardCombinationReq($units);
		}
	}

	function useCardCombinationReq($units) {
		$.ajax({
			type: 'POST',
			url: $ajaxPath,
			async: false,
			data: {
				mode: 'game',
				action: 'playcards',
				gid: $GAME.gid,
				combination: $units
			}
		}).fail(function() {
			console.error('Failed to connect.');
			window.setTimeout(function() {
				useCardCombinationReq($units);
			},500);
		}).done(function($responseText) {
			var $json;
			try {
				$json = JSON.parse($responseText);
			} catch($e) {
				return errorMessage($responseText);
			}
			$GAME.units = $json.units;
			$USER.cards = $json.cards;
			update();
		});
	}

	function inCombat($nation) {
		for(var $n=0,$length=$ATTACKS.length;$n < $length;$n++) {
			if($ATTACKS[$n].a_nation == $nation || $ATTACKS[$n].d_nation == $nation) {
				return true;
			}
		}
		return false;
	}

	function placeUnits() {
		var $input,
		$msg = $('#overlay_message'),
		$nation = $NATIONS[$PAGE.tab[1]];
		$msg.html('<h2>'+$nation.name+'</h2>');
		$msg.append('<form method="post"><p>How many units would you like to place?</p><p><input type="number" min="1" max="'+$GAME.units+'" value="1" /> <input type="button" value="Max" /> <input type="submit" value="Place" /> <input type="button" value="Cancel" /></p></form>');
		$input = $msg.find('input');
		$input.eq(1).click(function($e) {
			$e.preventDefault();
			$msg.find('input:first').val($GAME.units);
		});
		$input.eq(3).click(closeMessage);
		$msg.find('form').submit(placeUnitsReq);
		$('#overlay').show();
		$input.eq(0).focus();
	}

	function placeUnitsReq($e) {
		var $nation,
		$units = Number.parseInt($('#overlay_message input:first').val(),10);
		$e.preventDefault();
		if($units > 0 && !isNaN($units)) {
			$nation = $PAGE.tab[1];
			if($GAME.turn == $USER.uid && $GAME.units > 0 && $GAME.state < 2 && $NATIONS[$nation].uid == $USER.uid && window.confirm('Are you sure you want to place '+$units+' units in '+$NATIONS[$nation].name+'?')) {
				loadScreen();
				silentPlaceUnitReq($nation,$units);
			}
		} else {
			closeMessage();
		}
	}

	function silentPlaceUnitReq($nation,$units) {
		$.ajax({
			type: 'POST',
			url: $ajaxPath,
			data: {
				mode: 'game',
				action: 'place_units',
				gid: $GAME.gid,
				nation: $nation,
				units: $units
			}
		}).fail(function() {
			console.error('Failed to connect.');
			window.setTimeout(function() {
				silentPlaceUnitReq($nation,$units);
			},500);
		}).done(function($responseText) {
			var $json;
			try {
				$json = JSON.parse($responseText);
			} catch($er) {
				return errorMessage($er);
			}
			$GAME.units = $json.units;
			$GAME.turn = $json.turn;
			$GAME.state = $json.state;
			$GAME.time = $json.time;
			$NATIONS[$nation].units = $json.nat_units;
			update();
			closeMessage();
		});
	}

	function loadScreen() {
		$('#overlay').addClass('loading');
		$('#overlay_message').html('<h2>Please wait...</h2>');
	}

	function closeMessage($e) {
		var $overlay = $('#overlay');
		$overlay.hide();
		$overlay.removeClass();
		if($e !== undefined && typeof $e.preventDefault === 'function') {
			$e.preventDefault();
		}
	}

	function attackNation() {
		var $n,$i,$id,$length,$nat,$msg,$button,
		$nid = $PAGE.tab[1],
		$defender = $NATIONS[$nid],
		$select = $('<select></select>');
		for($n=0,$i=0,$id='',$length=$defender.borders.length;$n < $length;$n++) {
			$nat = $NATIONS[$defender.borders[$n]];
			if($nat.uid == $USER.uid && $nat.units > 1 && !inCombat($defender.borders[$n])) {
				$id = $defender.borders[$n];
				$select.append('<option value="'+$id+'">'+$nat.name+'</option>');
				$i++;
			}
		}
		if($i === 1) {
			attackScreen($id,$nid);
			return;
		}
		$('#overlay_message').html('<h2>'+$defender.name+'</h2><form method="post"><p>From which territory do you wish to attack?</p></form>');
		$msg = $('#overlay_message form');
		$msg.append($select);
		$button = $('<input type="submit" value="Attack" />');
		$msg.append($button);
		$msg.submit(function($e) {
			$e.preventDefault();
			attackScreen($select.val(),$nid);
		});
		$button = $('<input type="button" value="Cancel" />');
		$button.click(closeMessage);
		$msg.append($button);
		$('#overlay').show();
		$select.focus();
	}

	function attackScreen($attacker,$defender,$prevUnits) {
		var $form,$maxUnits,$label,$button,
		$att = $NATIONS[$attacker],
		$def = $NATIONS[$defender],
		$msg = $('#overlay_message');
		$msg.html('<h2><span style="color: #'+$PLAYERS[$USER.uid].color+';">'+$att.name+'</span> ('+$att.units+') vs <span style="color: #'+$PLAYERS[$def.uid].color+';">'+$def.name+'</span> ('+$def.units+')</h2>');
		if($GAME.units > 0 && $GAME.state < 2) {
			$msg.append('<p>You can still place units. Attacking now will make it impossible to place more units.</p>');
		}
		if($att.units > 2) {
			$msg.append('<p>How many units do you wish to attack with?</p>');
		}
		$form = $('<form method="post"></form>');
		$maxUnits = Math.min(3,$att.units-1);
		$label = $('<label>Number of attackers: <input type="number" min="1" max="'+$maxUnits+'" value="'+$maxUnits+'" /></label>');
		if($att.units < 3) {
			$label.hide();
		}
		$form.append($label);
		$maxUnits = $att.units-1;
		if($prevUnits === undefined) {
			$prevUnits = $maxUnits;
		}
		$label = $('<label>Transferring units: <input type="number" min="1" max="'+$maxUnits+'" value="'+Math.min($maxUnits,$prevUnits)+'" /></label>');
		if($att.units <= $def.units || $def.units > 2 || $att.units < 3) {
			$label.hide();
		}
		$form.append($label);
		$form.append('<input type="submit" value="Attack" />');
		$button = $('<input type="button" value="Cancel" />');
		$button.click(closeMessage);
		$form.append($button);
		$msg.append($form);
		$form.submit(function($e) {
			$e.preventDefault();
			attackReq($attacker,$defender);
		});
		$('#overlay').show();
		if($att.units < 3) {
			$form.find('input[type="submit"]').focus();
		} else {
			$form.find('input:first').focus();
		}
	}

	function getUnitsPerTurn() {
		var $c,
		$upert = getUnitsPerTurnNat();
		for($c in $CONTINENTS) {
			if(continentOwner($c) == $USER.uid) {
				$upert += $CONTINENTS[$c].units;
			}
		}
		return $upert;
	}

	function getUnitsPerTurnNat() {
		var $n,
		$upert = 0,
		$nations = 0;
		for($n in $NATIONS) {
			if($NATIONS[$n].uid == $USER.uid) {
				$nations++;
			}
		}
		$upert += Math.floor($nations/3);
		if($upert < 3) {
			$upert = 3;
		}
		return $upert;
	}

	function attackReq($attacker,$defender) {
		var $input = $('#overlay_message input'),
		$units = Number.parseInt($input.eq(0).val(),10),
		$tUnits = Number.parseInt($input.eq(1).val(),10);
		if(isNaN($units) || isNaN($tUnits)) {
			return;
		}
		if($GAME.state !== 1 && $GAME.state != 3 && $NATIONS[$attacker].uid == $USER.uid && $NATIONS[$defender] != $USER.uid && $NATIONS[$attacker].units > 1 && $units < 4 && $tUnits < $NATIONS[$attacker].units) {
			loadScreen();
			silentAttackReq($attacker,$defender,$units,$tUnits);
		} else {
			closeMessage();
		}
	}

	function silentAttackReq($attacker,$defender,$units,$tUnits) {
		$.ajax({
			type: 'POST',
			url: $ajaxPath,
			data: {
				mode: 'game',
				gid: $GAME.gid,
				action: 'attack',
				attacker: $attacker,
				defender: $defender,
				units: $units,
				transfer_units: $tUnits
			}
		}).fail(function() {
			console.error('Failed to connect.');
			window.setTimeout(function() {
				silentAttackReq($attacker,$defender,$units,$tUnits);
			},500);
		}).done(function($responseText) {
			var $json,$roll,$n,$msg,$button;
			try {
				$json = JSON.parse($responseText);
			} catch($e) {
				return errorMessage($responseText);
			}
			$GAME.state = $json.state;
			$GAME.time = $json.time;
			$('#overlay').removeClass();
			$msg = $('#overlay_message');
			$msg.html('<h2><span style="color: #'+$PLAYERS[$USER.uid].color+';">'+$NATIONS[$attacker].name+'</span> ('+$NATIONS[$attacker].units+') vs <span style="color: #'+$PLAYERS[$NATIONS[$defender].uid].color+';">'+$NATIONS[$defender].name+'</span> ('+$NATIONS[$defender].units+')</h2><h3>You Rolled</h3>');
			$roll = $('<p></p>');
			for($n=0;$n < $json.attackroll.length;$n++) {
				$roll.append('<div class="d'+$json.attackroll[$n]+' die_attack"></div>');
			}
			$msg.append($roll);
			if($json.defendroll[0] !== undefined) {
				$msg.append('<h3>'+$PLAYERS[$NATIONS[$defender].uid].displayname+' Rolled</h3>');
				$roll = $('<p></p>');
				for($n=0;$n < $json.defendroll.length;$n++) {
					$roll.append('<div class="d'+$json.defendroll[$n]+' die_defend"></div>');
				}
				$msg.append($roll);
				$NATIONS[$attacker].units -= $json.attackLosses;
				$NATIONS[$defender].units -= $json.defendLosses;
				if($NATIONS[$defender].units === 0) {
					$NATIONS[$defender].units = $json.transfer;
					$NATIONS[$attacker].units -= $json.transfer;
					$NATIONS[$defender].uid = $USER.uid;
					$msg.append('<h3>Congratulations!</h3>');
					$GAME.conquered = true;
				} else if($NATIONS[$attacker].units > 1) {
					$button = $('<button>Attack again</button>');
					$button.click(function() { attackScreen($attacker,$defender,$tUnits); });
					$msg.append($button);
					$button.focus();
				}
			} else {
				$ATTACKS.push({gid: $GAME.gid, a_nation: $attacker, d_nation: $defender, a_uid: $USER.uid, d_uid: $NATIONS[$defender].uid, a_roll: $json.attackroll.join(''), transfer: $json.transfer});
				$msg.append('<p>'+$PLAYERS[$NATIONS[$defender].uid].displayname+' has AutoRoll disabled.</p>');
			}
			$button = $('<button>Close</button>');
			$button.click(closeMessage);
			$msg.append($button);
			if($json.defendroll[0] === undefined || $NATIONS[$attacker].units <= 1 || $NATIONS[$defender].uid == $USER.uid) {
				$button.focus();
			}
			update();
		});
	}

	function stackUnits($e) {
		$e.preventDefault();
		if($USER.uid == $GAME.turn && $GAME.state === 0 && window.confirm('You will not be able to attack this turn if you do this. Are you sure you want to continue?')) {
			$('#overlay').show();
			loadScreen();
			stackUnitsReq();
		}
	}

	function stackUnitsReq() {
		$.ajax({
			type: 'POST',
			url: $ajaxPath,
			data: {
				mode: 'game',
				gid: $GAME.gid,
				action: 'stack'
			}
		}).fail(function() {
			console.error('Failed to connect.');
			window.setTimeout(stackUnitsReq,500);
		}).done(function($responseText) {
			var $json;
			try {
				$json = JSON.parse($responseText);
			} catch($er) {
				return errorMessage($responseText);
			}
			$GAME.units = $json.units;
			$GAME.state = $json.state;
			update();
			closeMessage();
		});
	}

	function moveUnits($e) {
		$e.preventDefault();
		if($GAME.state == 2 && $ATTACKS.length === 0 && window.confirm('You will not be able to attack anymore this turn if you do this. Are you sure you want to continue?')) {
			$('#overlay').show();
			loadScreen();
			moveUnitsReq();
		}
	}

	function moveUnitsReq() {
		$.ajax({
			type: 'POST',
			url: $ajaxPath,
			data: {
				mode: 'game',
				gid: $GAME.gid,
				action: 'start_move'
			}
		}).fail(function() {
			console.error('Failed to connect.');
			window.setTimeout(moveUnitsReq,500);
		}).done(function($responseText) {
			var $json;
			try {
				$json = JSON.parse($responseText);
			} catch($er) {
				return errorMessage($responseText);
			}
			$GAME.units = $json.units;
			$GAME.state = $json.state;
			update();
			closeMessage();
		});
	}

	function moveUnitsTo() {
		var $n,$i,$from,$length,$nat,$msg,$button,$form,
		$to = $PAGE.tab[1],
		$nation = $NATIONS[$to],
		$select = $('<select></select>');
		for($n=0,$i=0,$from='',$length=$nation.borders.length;$n < $length;$n++) {
			$nat = $NATIONS[$nation.borders[$n]];
			if($nat.uid == $USER.uid && $nat.units > 1) {
				$select.append('<option value="'+$nation.borders[$n]+'">'+$nat.name+'</option>');
				$i++;
				if($i === 1) {
					$from = $nation.borders[$n];
				}
			}
		}
		if($i === 1) {
			moveScreen($to,$from);
			return;
		}
		$msg = $('#overlay_message');
		$msg.html('<h2>Transfer to '+$nation.name+'</h2><p>From which territory do you wish to transfer units?</p>');
		$form = $('<form method="post"></form>');
		$form.append($select);
		$button = $('<input type="submit" value="Transfer" />');
		$form.append($button);
		$button = $('<input type="button" value="Cancel" />');
		$button.click(closeMessage);
		$form.append($button);
		$form.submit(function($e) {
			$e.preventDefault();
			moveScreen($to,$select.val());
		});
		$msg.append($form);
		$('#overlay').show();
		$select.focus();
	}

	function moveScreen($to,$from) {
		var $form,$maxUnits,$button,$input,
		$msg = $('#overlay_message');
		$msg.html('<h2>'+$NATIONS[$from].name+' ('+$NATIONS[$from].units+') to '+$NATIONS[$to].name+' ('+$NATIONS[$to].units+')</h2>');
		$maxUnits = Math.min($NATIONS[$from].units-1,$GAME.units);
		$form = $('<form method="post"></form>');
		$input = $('<input type="number" min="1" max="'+$maxUnits+'" value="'+$maxUnits+'" />');
		$form.append($input);
		if($NATIONS[$from].units == 2 || $GAME.units === 1) {
			$input.hide();
		}
		$form.append('<input type="submit" value="Transfer" />');
		$form.submit(function($e) {
			var $units = Number.parseInt($input.val(),10);
			$e.preventDefault();
			if($NATIONS[$to].uid == $USER.uid && $NATIONS[$from].uid == $USER.uid && $units <= $GAME.units) {
				loadScreen();
				moveScreenReq($to,$from,$units);
			} else {
				closeMessage();
			}
		});
		$button = $('<input type="button" value="Cancel" />');
		$button.click(closeMessage);
		$form.append($button);
		$msg.append($form);
		$('#overlay').show();
		if($NATIONS[$from].units == 2 || $GAME.units === 1) {
			$form.find('input[type="submit"]').focus();
		} else {
			$input.focus();
		}
	}

	function moveScreenReq($to,$from,$units) {
		$.ajax({
			type: 'POST',
			url: $ajaxPath,
			data: {
				mode: 'game',
				gid: $GAME.gid,
				action: 'move_units',
				to: $to,
				from: $from,
				units: $units
			}
		}).fail(function() {
			console.error('Failed to connect.');
			window.setTimeout(function() {
				moveScreenReq($to,$from,$units);
			},500);
		}).done(function($responseText) {
			var $json;
			try {
				$json = JSON.parse($responseText);
			} catch($er) {
				return errorMessage($responseText);
			}
			$NATIONS[$json.to].units = $json.to_units;
			$NATIONS[$json.from].units = $json.from_units;
			$GAME.units = $json.units;
			$GAME.time = $json.time;
			update();
			closeMessage();
		});
	}

	function endTurn($e) {
		var $confirm = true;
		$e.preventDefault();
		if($GAME.turn == $USER.uid && $ATTACKS.length === 0) {
			if($GAME.state < 2 && $GAME.units > 0) {
				$confirm = window.confirm('You can still place units. Are you sure you want to end your turn?');
			} else if($GAME.state == 3 && $GAME.units > 0) {
				$confirm = window.confirm('You can still transfer units. Are you sure you want to end your turn?');
			} else if($GAME.state === 0 || $GAME.state == 2) {
				$confirm = window.confirm('Are you sure you want to end your turn?');
			}
			if($confirm) {
				$('#overlay').show();
				if($GAME.conquered && $USER.cards.c_art+$USER.cards.c_inf+$USER.cards.c_cav+$USER.cards.c_jok > 4) {
					discardCard();
				} else {
					endTurnReq(-1);
				}
			}
		}
	}

	function discardCard() {
		var $div,$c,$n,$select,$button,$form,
		$msg = $('#overlay_message');
		$msg.html('<h2>End Turn</h2><p>You have conquered a territory this turn, but you already have 5 cards.</p><div></div><p>Which card would you like to discard?</p>');
		$div = $msg.find('div');
		$form = $('<form method="post"></form>');
		$select = $('<select><option value="-1">None</option></select>');
		for($c in $USER.cards) {
			for($n=0;$n < $USER.cards[$c];$n++) {
				$div.append('<img src="'+$basePath+'resources/game/'+$c+'.png" alt="'+$cardNames[$c]+'" style="height: 50px;" class="card" />');
			}
			if($USER.cards[$c] > 0) {
				$select.append('<option value="'+$c+'">'+$cardNames[$c]+'</option>');
			}
		}
		$form.append($select);
		$button = $('<input type="submit" value="Discard" />');
		$form.submit(function($e) {
			$e.preventDefault();
			endTurnReq($select.val());
		});
		$form.append($button);
		$msg.append($form);
		$button = $('<input type="button" value="Cancel" />');
		$button.click(closeMessage);
		$form.append($button);
		$select.focus();
	}

	function endTurnReq($card) {
		loadScreen();
		$.ajax({
			type: 'POST',
			url: $ajaxPath,
			data: {
				mode: 'game',
				gid: $GAME.gid,
				action: 'endturn',
				card: $card
			}
		}).fail(function() {
			window.setTimeout(function() {
				endTurnReq($card);
			},500);
		}).done(function($responseText) {
			var $json,$button;
			try {
				$json = JSON.parse($responseText);
			} catch($er) {
				return errorMessage($responseText);
			}
			$GAME.time = $json.time;
			$GAME.turn = $json.turn;
			$GAME.state = 0;
			$GAME.units = 0;
			if($GAME.conquered) {
				$USER.cards = $json.cards;
				if($json.newcard != -1) {
					$('#overlay').removeClass();
					$('#overlay_message').html('<h2>Your New Card</h2><div><img src="'+$basePath+'resources/game/'+$json.newcard+'.png" alt="'+$cardNames[$json.newcard]+'" class="card" /></div><button>Close</button>');
					$button = $('#overlay_message button');
					$button.click(closeMessage);
					$button.focus();
				} else {
					closeMessage();
				}
			} else {
				closeMessage();
			}
			$GAME.conquered = false;
			update();
		});
	}

	return {init: init};
})(jQuery);