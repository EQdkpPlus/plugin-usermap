<?php
/*	Project:	EQdkp-Plus
 *	Package:	Usermap Plugin
 *	Link:		http://eqdkp-plus.eu
 *
 *	Copyright (C) 2006-2017 EQdkp-Plus Developer Team
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU Affero General Public License as published
 *	by the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU Affero General Public License for more details.
 *
 *	You should have received a copy of the GNU Affero General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if (!defined('EQDKP_INC')){
	header('HTTP/1.0 404 Not Found');exit;
}

class usermap extends plugin_generic {
	public $vstatus		= 'Stable';
	public $version		= '1.0.1';
	public $copyright 	= 'EQdkpPlus Dev Team';

	protected static $apiLevel = 23;

	public function __construct(){
		parent::__construct();

		$this->add_data(array (
			'name'				=> 'UserMap',
			'code'				=> 'usermap',
			'path'				=> 'usermap',
			'template_path'		=> 'plugins/usermap/templates/',
			'icon'				=> 'fa-map',
			'version'			=> $this->version,
			'author'			=> $this->copyright,
			'description'		=> $this->user->lang('usermap_short_desc'),
			'long_description'	=> $this->user->lang('usermap_long_desc'),
			'homepage'			=> EQDKP_PROJECT_URL,
			'manuallink'		=> false,
			'plus_version'		=> '2.3'
		));

		// -- Register our permissions ------------------------
		// permissions: 'a'=admins, 'u'=user
		// ('a'/'u', Permission-Name, Enable? 'Y'/'N', Language string, array of user-group-ids that should have this permission)
		// Groups: 1 = Guests, 2 = Super-Admin, 3 = Admin, 4 = Member
		$this->add_permission('u', 'view',		'Y', $this->user->lang('view'),					array(2,3,4));
		$this->add_permission('a', 'settings',	'N', $this->user->lang('settings'),				array(2,3));

		// -- PDH Modules -------------------------------------
		$this->add_pdh_read_module('usermap_geolocation');
		$this->add_pdh_write_module('usermap_geolocation');

		// -- Hooks -------------------------------------------
		$this->add_hook('usersettings_update',	'usermap_usersettings_update_hook',	'usersettings_update');
		$this->add_hook('user_delete',	'usermap_user_delete_hook',	'user_delete');
		$this->add_hook('user_export_gdpr',	'usermap_user_export_gdpr_hook',	'user_export_gdpr');

		// -- Routing -------------------------------------------
		$this->routing->addRoute('Usermap', 'usermap', 'plugins/usermap/page_objects');

		// -- Menu --------------------------------------------
		$this->add_menu('admin', $this->gen_admin_menu());
		$this->add_menu('main', $this->gen_main_menu());

		// -- Log Type ----------------------------------------
		$this->pdl->register_type('usermaps');
	}

	/**
	* Define Installation
	*/
	public function pre_install(){
		// include SQL and default configuration data for installation
		include($this->root_path.'plugins/usermap/includes/sql.php');

		// define installation
		for ($i = 1; $i <= count($usermapSQL['install']); $i++){
			$this->add_sql(SQL_INSTALL, $usermapSQL['install'][$i]);
		}

		// set the default config
		if (is_array($config_vars)){
			$this->config->set($this->default_config(), '', 'usermap');
		}

		// load user locations
		$this->pdh->get('usermap_geolocation', 'fetchUserLocations');
		$this->pdh->process_hook_queue();
	}

	/**
	* Define the default config
	*/
	private function default_config(){
		return array();
	}

	/**
	* Define uninstallation
	*/
	public function pre_uninstall(){
		// include SQL data for uninstallation
		include($this->root_path.'plugins/usermap/includes/sql.php');

		for ($i = 1; $i <= count($usermapSQL['uninstall']); $i++)
			$this->add_sql(SQL_UNINSTALL, $usermapSQL['uninstall'][$i]);
	}

	/**
	* Generate the Admin Menu
	*/
	private function gen_admin_menu(){
		$admin_menu = array (array(
			'name' => $this->user->lang('usermap'),
			'icon' => 'fa-map',
			1 => array (
				'link'	=> 'plugins/usermap/admin/manage_settings.php'.$this->SID,
				'text'	=> $this->user->lang('settings'),
				'check'	=> 'a_usermap_settings',
				'icon'	=> 'fa-wrench'
			)
		));
		return $admin_menu;
	}

	/**
	* gen_admin_menu
	* Generate the Admin Menu
	*/
	private function gen_main_menu(){
		$main_menu = array(
			1 => array (
				'link'		=> $this->routing->build('Usermap', false, false, true, true),
				'text'		=> $this->user->lang('um_mainmenu_usermap'),
				'check'		=> 'u_usermap_view',
			),
		);
		return $main_menu;
	}
}
?>