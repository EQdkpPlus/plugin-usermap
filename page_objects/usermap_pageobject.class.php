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
		$shortcuts = array('geoloc' => 'geolocation');
		return array_merge(parent::$shortcuts, $shortcuts);
	}

	private $data = array();

	public function __construct(){
		if (!$this->pm->check('usermap', PLUGIN_INSTALLED))
			message_die($this->user->lang('usermap_not_installed'));

		$handler = array(
			#'save' => array('process' => 'save', 'csrf' => true, 'check' => 'u_guildbank_view'),
		);
		parent::__construct('u_usermap_view', $handler);
		$this->process();
	}

	public function display(){
		// fill the Cache
		$this->pdh->put('usermap_geolocation', 'fetchUserLocations');
		$this->pdh->process_hook_queue();
		$saved_locationdata = $this->pdh->get('usermap_geolocation', 'list');

		$arrMarkers = array();
		if(is_array($saved_locationdata) && count($saved_locationdata) > 0){
			foreach($saved_locationdata as $userid=>$locdata){
				$arrMarkers[$userid] = array(
					'title'		=> $this->pdh->get('user', 'name', array($userid)),
					'tooltip'	=> "<div class='usermap_username'>".$this->pdh->geth('user', 'name', array($userid, '', '', true)).'</div>',
					'lat'		=> $locdata['lat'],
					'lng'		=> $locdata['long'],
				);
			}
		}

		$this->tpl->assign_vars(array(
			'MAP'			=> $this->jquery->geomaps('usermap', $arrMarkers),
			'CREDITS'		=> sprintf($this->user->lang('um_credits'), $this->pm->get_data('usermap', 'version')),
		));

		$this->core->set_vars(array(
			'page_title'		=> $this->user->lang('um_title_page'),
			'template_path'		=> $this->pm->get_data('usermap', 'template_path'),
			'template_file'		=> 'usermap.html',
			'page_path'			=> array(array('title' => $this->user->lang('um_title_page'), 'url' => ' ')),
			'display'			=> true,
			)
		);
	}
}
?>
