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

class usermap_pageobject extends pageobject {

	public static function __shortcuts(){
		$shortcuts = array('money' => 'gb_money');
		return array_merge(parent::$shortcuts, $shortcuts);
	}

	private $data = array();

	public function __construct(){
		if (!$this->pm->check('usermap', PLUGIN_INSTALLED))
			message_die($this->user->lang('usermap_not_installed'));

		// load the google maps jQuery plugin files
		$this->jquery->init_gmaps();

		$handler = array(
			#'save' => array('process' => 'save', 'csrf' => true, 'check' => 'u_guildbank_view'),
		);
		parent::__construct('u_usermap_view', $handler);
		$this->process();
	}

	public function display(){

		$this->tpl->assign_vars(array(
			'MAP'				=> $this->jquery->googlemaps('usermap'),
			'CREDITS'		=> sprintf($this->user->lang('um_credits'), $this->pm->get_data('usermap', 'version')),
		));

		$this->core->set_vars(array(
			'page_title'		=> $this->user->lang('um_title_page'),
			'template_path'		=> $this->pm->get_data('usermap', 'template_path'),
			'template_file'		=> 'usermap.html',
			'display'			=> true,
			)
		);
	}
}
?>