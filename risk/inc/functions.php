<?php
	if(!defined('IN_MYBB')) {
		die;
	}
	class EE_risk {
		public static $URL;
		public static $URL_ABOUT;
		public static $URL_GAMES;
		public static $URL_YOURGAMES;
		public static $URL_MAPS;
		public static $URL_MEMBERS;
		public static $URL_NEWGAME;
		public static $URL_GAME;
		public static $URL_GAMESTARTED;
		public static $URL_MAP;

		public static function output_page($page,$title,$head = '') {
			global $mybb,$lang,$templates,$URL;
			$date = date('Y');
			$user = $mybb->user;
			$user['username'] = format_name($user['username'],$user['usergroup'],$user['displaygroup']);
			$user['profilelink'] = build_profile_link($user['username'], $user['uid']);

			eval('$page = "'.$templates->get('EE-risk_page',true,false).'";');
			self::output($page);
		}

		public static function output($page) {
			global $mybb,$lang;
			if($mybb->settings['gzipoutput'] == 1) {
				$page = gzip_encode($page,$mybb->settings['gziplevel']);
			}
			@header("Content-type: text/html; charset={$lang->settings['charset']}");
			echo $page;
		}

		public static function get_url_map($mid,$name) {
			$name = self::url_escape($name);
			return str_replace(array('{$mid}','{$name}'),array($mid,$name),self::$URL_MAP);
		}

		public static function get_url_game($gid,$name,$started = false) {
			$name = self::url_escape($name);
			$url = self::$URL_GAME;
			if($started) {
				$url = self::$URL_GAMESTARTED;
			}
			return str_replace(array('{$gid}','{$name}'),array($gid,$name),$url);
		}

		public static function delete_game($gid) {
			return self::delete_games(array($gid));
		}

		public static function delete_games($gids) {
			global $db;
			$tables = array('chat','combatlog','attacks','nations','gamesjoined','games');
			$gids = implode(',',$gids);
			foreach($tables as $table) {
				$db->delete_query('EE_risk_'.$table,'gid IN('.$gids.')');
			}
		}

		public static function clean($string,$escape = true) {
			global $db;
			$string = str_replace(array(chr(160),chr(173),chr(202)),array(' ','-',''),$string);
			$string = preg_replace('/\s{2,}/',' ',$string);
			$string = trim($string);
			$string = htmlspecialchars($string);
			if($escape) {
				$string = $db->escape_string($string);
			}
			return $string;
		}

		public static function mapSort($a,$b) {
			if($a['players'] == $b['players']) {
				return strnatcmp($a['name'],$b['name']);
			}
			return $a['players'] < $b['players'] ? -1 : 1; 
		}

		public static function getSortedMaps($mapTypes) {
			$out = $mapTypes;
			for($n=0;$n < count($out);$n++) {
				$out[$n]['mid'] = $n;
			}
			uasort($out,array('self','mapSort'));
			return $out;
		}

		private static function url_escape($string) {
			$string = preg_replace(array('/\s/','/[\x00-\x1F\x7F]/','/&(.*?);/'),' ',$string);
			$string = str_replace(array('#','-','?','&','%','@',':','/','\\','[',']','{','}',';','=','+','$',',','`','~','<','>','"','|','^','\'',chr(160),chr(173),chr(202)),' ',$string);
			$string = trim($string);
			$string = preg_replace('/\s{2,}/',' ',$string);
			return str_replace(' ','-',$string);
		}

		public static function game_password_hash($password) {
			return sha1('This string, '.sha1($password).', is the password hash.');
		}
	}
	EE_risk::$URL = $mybb->settings['bburl'].'/'.basename(dirname(dirname(__FILE__)));
	if($mybb->settings['seourls'] == 'yes' || ($mybb->settings['seourls'] == 'auto' && isset($_SERVER['SEO_SUPPORT']) && $_SERVER['SEO_SUPPORT'] == 1)) {
		EE_risk::$URL_ABOUT = EE_risk::$URL.'/about/';
		EE_risk::$URL_GAMES = EE_risk::$URL.'/';
		EE_risk::$URL_MAPS = EE_risk::$URL.'/maps/';
		EE_risk::$URL_MEMBERS = EE_risk::$URL.'/players/';
		EE_risk::$URL_NEWGAME = EE_risk::$URL.'/game/new/';
		EE_risk::$URL_GAME = EE_risk::$URL.'/game/lobby/{$gid}-{$name}/';
		EE_risk::$URL_GAMESTARTED = EE_risk::$URL.'/game/{$gid}-{$name}/';
		EE_risk::$URL_MAP = EE_risk::$URL.'/maps/{$mid}-{$name}/';
		EE_risk::$URL_YOURGAMES = EE_risk::$URL.'/games/joined/';
	} else {
		EE_risk::$URL_ABOUT = EE_risk::$URL.'/about.php';
		EE_risk::$URL_GAMES = EE_risk::$URL.'/';
		EE_risk::$URL_MAPS = EE_risk::$URL.'/maplist.php';
		EE_risk::$URL_MEMBERS = EE_risk::$URL.'/memberlist.php';
		EE_risk::$URL_NEWGAME = EE_risk::$URL.'/newgame.php';
		EE_risk::$URL_GAME = EE_risk::$URL.'/game.php?gid={$gid}';
		EE_risk::$URL_GAMESTARTED = EE_risk::$URL.'/game/?gid={$gid}';
		EE_risk::$URL_MAP = EE_risk::$URL.'/map.php?map={$mid}';
		EE_risk::$URL_YOURGAMES = EE_risk::$URL.'/yourgames.php';
	}
	$URL = array(
		'base' => EE_risk::$URL,
		'ABOUT' => EE_risk::$URL_ABOUT,
		'GAMES' => EE_risk::$URL_GAMES,
		'MAPS' => EE_risk::$URL_MAPS,
		'MEMBERS' => EE_risk::$URL_MEMBERS,
		'NEWGAME' => EE_risk::$URL_NEWGAME,
		'YOURGAMES' => EE_risk::$URL_YOURGAMES
	);
?>