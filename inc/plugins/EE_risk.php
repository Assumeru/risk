<?php
	if(!defined('IN_MYBB')) {
		die('Direct initialization of this file is not allowed.
		Please make sure IN_MYBB is defined.');
	}

	$plugins->add_hook('admin_user_users_merge_commit','EE_risk_mergeusers');
	$plugins->add_hook('admin_user_users_delete_commit','EE_risk_deleteuser');

	function EE_risk_info() {
		return array(
			'name' => 'World Domination',
			'author' => 'Evil Eye',
			'version' => '0.5',
			'compatibility' => '16*'
		);
	}

	function EE_risk_install() {
		global $db;

		EE_risk_uninstall();

		$collation = $db->build_create_table_collation();

		$db->query('CREATE TABLE '.TABLE_PREFIX.'EE_risk_users (
			uid				INT					NOT NULL	AUTO_INCREMENT,
			wins			INT					NOT NULL	DEFAULT "0",
			losses			INT					NOT NULL	DEFAULT "0",
			score			INT					NOT NULL	DEFAULT "0",
			PRIMARY KEY(uid)
		) '.$collation.';');
		$db->query('CREATE TABLE '.TABLE_PREFIX.'EE_risk_games (
			gid				INT					NOT NULL	AUTO_INCREMENT,
			map				SMALLINT			NOT NULL,
			name			VARCHAR(255)		NOT NULL,
			uid				INT					NOT NULL,
			turn			INT					NOT NULL	DEFAULT "0",
			time			INT					NOT NULL,
			state			SMALLINT			NOT NULL	DEFAULT "0",
			units			INT					NOT NULL	DEFAULT "0",
			conquered		SMALLINT			NOT NULL	DEFAULT "0",
			password		CHAR(40),
			PRIMARY KEY(gid)
		) '.$collation.';');
		$db->query('CREATE TABLE '.TABLE_PREFIX.'EE_risk_gamesjoined (
			uid				INT					NOT NULL,
			gid				INT					NOT NULL,
			color			CHAR(6)				NOT NULL,
			autoroll		SMALLINT			NOT NULL	DEFAULT "1",
			mission			INT					NOT	NULL	DEFAULT "0",
			m_uid			INT					NOT NULL	DEFAULT "0",
			state			INT					NOT NULL	DEFAULT "0",
			c_art			SMALLINT			NOT NULL	DEFAULT "0",
			c_cav			SMALLINT			NOT NULL	DEFAULT "0",
			c_inf			SMALLINT			NOT NULL	DEFAULT "0",
			c_jok			SMALLINT			NOT NULL	DEFAULT "0",
			PRIMARY KEY(uid,gid)
		) '.$collation.';');
		$db->query('CREATE TABLE '.TABLE_PREFIX.'EE_risk_nations (
			gid				INT					NOT NULL,
			nation			VARCHAR(150)		NOT NULL,
			uid				INT					NOT NULL,
			units			SMALLINT			NOT NULL,
			PRIMARY KEY(gid,nation)
		) '.$collation.';');
		$db->query('CREATE TABLE '.TABLE_PREFIX.'EE_risk_attacks (
			gid				INT					NOT NULL,
			a_nation		VARCHAR(150)		NOT NULL,
			d_nation		VARCHAR(150)		NOT NULL,
			a_uid			INT					NOT NULL,
			d_uid			INT					NOT NULL,
			a_roll			CHAR(3)				NOT NULL,
			transfer		INT					NOT NULL,
			PRIMARY KEY(gid,a_nation,d_nation)
		) '.$collation.';');
		$db->query('CREATE TABLE '.TABLE_PREFIX.'EE_risk_chat (
			gid				INT					NOT NULL,
			uid				INT					NOT NULL,
			time			INT					NOT NULL,
			message			VARCHAR(512),
			PRIMARY KEY(gid,uid,time)
		) '.$collation.';');
		$db->query('CREATE TABLE '.TABLE_PREFIX.'EE_risk_combatlog (
			gid				INT					NOT NULL,
			time			INT					NOT NULL,
			message			VARCHAR(512)
		) '.$collation.';');

		$db->insert_query('templategroups',array(
			'prefix' => 'EE-risk',
			'title' => $db->escape_string('EE\'s World Domination')
		));
		$templates = array();
		$paths = glob(MYBB_ROOT.'inc/plugins/EE_risk/templates/*.html');
		foreach($paths as $path) {
			$templates[] = array(
					'title' => 'EE-risk_'.basename($path,'.html'),
					'template' => $db->escape_string(file_get_contents($path)),
					'sid' => '-1',
					'version' => '',
					'dateline' => TIME_NOW
			);
		}
		$db->insert_query_multiple('templates',$templates);
		$db->insert_query('tasks',array(
			'title' => 'EE_risk Cleanup',
			'description' => 'Deletes old chat messages from the global chat as well as deleting finished games after 24 hours, and deleting inactive games after two weeks.',
			'file' => 'EE_risk',
			'minute' => '0',
			'hour' => '0',
			'day' => '*',
			'month' => '*',
			'weekday' => '*',
			'logging' => '1'
		));
	}

	function EE_risk_uninstall() {
		global $db;

		$db->drop_table('EE_risk_users');
		$db->drop_table('EE_risk_games');
		$db->drop_table('EE_risk_gamesjoined');
		$db->drop_table('EE_risk_nations');
		$db->drop_table('EE_risk_attacks');
		$db->drop_table('EE_risk_chat');
		$db->drop_table('EE_risk_combatlog');
		$db->delete_query('templates','title LIKE "EE-risk_%"');
		$db->delete_query('templategroups','prefix = "EE-risk"');
		$db->delete_query('tasks','file = "EE_risk"');
	}

	function EE_risk_is_installed() {
		global $db;
		return $db->table_exists('EE_risk_games');
	}

	function EE_risk_activate() {
		global $db;
		$db->update_query('tasks',array('enabled' => 1),'file = "EE_risk"');
	}

	function EE_risk_deactivate() {
		global $db;
		$db->update_query('tasks',array('enabled' => 0),'file = "EE_risk"');
	}

	function EE_risk_deleteuser() {
		global $user,$db;

		$gidsToDelete = array();
		$db->simple_select('EE_risk_gamesjoined','gid','uid = '.$user['uid']);
		while($game = $db->fetch_array($query)) {
			$gidsToDelete[] = $game['uid'];
		}
		if(!empty($gidsToDelete)) {
			EE_risk_deletegames($gidsToDelete);
		}
	}

	function EE_risk_mergeusers() {
		global $destination_user,$source_user,$db;

		$gidsToDelete = array();
		$query = $db->query('SELECT gid FROM '.TABLE_PREFIX.'EE_risk_gamesjoined WHERE uid = '.$destination_user['uid'].' OR uid = '.$source_user['uid'].' GROUP BY gid HAVING COUNT(uid) > 1');
		while($user = $db->fetch_array($query)) {
			$gidsToDelete[] = $user['uid'];
		}
		if(!empty($gidsToDelete)) {
			EE_risk_deletegames($gidsToDelete);
		}
		$db->update_query('EE_risk_games',array('uid' => $destination_user['uid']),'uid = '.$source_user['uid']);
		$db->update_query('EE_risk_gamesjoined',array('uid' => $destination_user['uid']),'uid = '.$source_user['uid']);
		$db->update_query('EE_risk_nations',array('uid' => $destination_user['uid']),'uid = '.$source_user['uid']);
		$db->update_query('EE_risk_chat',array('uid' => $destination_user['uid']),'uid = '.$source_user['uid']);
		$db->update_query('EE_risk_attacks',array('a_uid' => $destination_user['uid']),'uid = '.$source_user['uid']);
		$db->update_query('EE_risk_attacks',array('d_uid' => $destination_user['uid']),'uid = '.$source_user['uid']);
		$query = $db->simple_select('EE_risk_users','*','uid = '.$source_user['uid']);
		if($user = $db->fetch_array($query)) {
			$query = $db->simple_select('EE_risk_users','1','uid = '.$destination_user['uid']);
			if($db->fetch_array($query)) {
				$db->update_query('EE_risk_users',array(
					'wins' => 'wins+'.$user['wins'],
					'losses' => 'losses+'.$user['losses'],
					'score' => 'score+'.$user['score']
				),'uid = '.$destination_user['uid'],1,true);
				$db->delete_query('EE_risk_users','uid = '.$source_user['uid']);
			} else {
				$db->update_query('EE_risk_users',array('uid' => $destination_user['uid']),'uid = '.$source_user['uid']);
			}
		}
	}

	function EE_risk_deletegames($gids) {
		if(!class_exists('EE_risk')) {
			if(file_exists(MYBB_ROOT.'risk/inc/functions.php')) {
				require_once MYBB_ROOT.'risk/inc/functions.php';
			} else {
				$files = glob(MYBB_ROOT.'*/ajax/game/autoroll.php');
				require_once dirname(dirname(dirname($files[0]))).'/inc/functions.php';
			}
		}
		EE_risk::delete_games($gids);
	}
?>