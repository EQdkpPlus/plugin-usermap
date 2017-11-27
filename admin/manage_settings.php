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

// EQdkp required files/vars
define('EQDKP_INC', true);
define('IN_ADMIN', true);
define('PLUGIN', 'usermap');

$eqdkp_root_path = './../../../';
include_once('./../includes/common.php');

class usermapSettings extends page_generic {

	/**
	* Constructor
	*/
	public function __construct(){
		// plugin installed?
		if (!$this->pm->check('usermap', PLUGIN_INSTALLED))
			message_die($this->user->lang('usermap_not_installed'));

		$handler = array(
			'save' => array('process' => 'save', 'csrf' => true, 'check' => 'a_usermap_settings'),
		);
		parent::__construct('a_usermap_settings', $handler);

		$this->process();
	}

	private $arrData = false;

	public function save(){
		$objForm				= register('form', array('um_settings'));
		$objForm->langPrefix	= 'um_';
		$objForm->validate		= true;
		$objForm->add_fieldsets($this->fields());
		$arrValues				= $objForm->return_values();

		if($objForm->error){
			$this->arrData		= $arrValues;
		}else{
			// update configuration
			$this->config->set($arrValues, '', 'usermap');
			// Success message
			$messages[]			= $this->user->lang('um_saved');
			$this->display($messages);
		}
	}

	private function fields(){
		$none = array(0 => '-- '.$this->user->lang('none').' --');
		$arrFields = array(
			'location' => array(
				'street' => array(
					'type'		=> 'dropdown',
					'options'	=> $none + $this->pdh->aget('user_profilefields', 'html_name', 0, array($this->pdh->get('user_profilefields', 'id_list'))),
					'value'		=> $this->config->get('street',	'usermap'),

				),
				'streetnumber' => array(
					'type'		=> 'dropdown',
					'options'	=> $none + $this->pdh->aget('user_profilefields', 'html_name', 0, array($this->pdh->get('user_profilefields', 'id_list'))),
					'value'		=> $this->config->get('streetnumber',	'usermap'),
				),
				'city' => array(
					'type'		=> 'dropdown',
					'options'	=> $none + $this->pdh->aget('user_profilefields', 'html_name', 0, array($this->pdh->get('user_profilefields', 'id_list'))),
					'value'		=> ($this->config->get('city',	'usermap')) ? $this->config->get('city',	'usermap') : $this->pdh->get('user_profilefields', 'field_by_name', array('location')),
				),
				'zip' => array(
					'type'		=> 'dropdown',
					'options'	=> $none + $this->pdh->aget('user_profilefields', 'html_name', 0, array($this->pdh->get('user_profilefields', 'id_list'))),
					'value'		=> $this->config->get('zip',	'usermap'),
				),
				'country' => array(
					'type'		=> 'dropdown',
					'options'	=> $none + $this->pdh->aget('user_profilefields', 'html_name', 0, array($this->pdh->get('user_profilefields', 'id_list'))),
					'value'		=> ($this->config->get('country',	'usermap')) ? $this->config->get('country',	'usermap') : $this->pdh->get('user_profilefields', 'field_by_name', array('country')),
				),
			),
		);
		return $arrFields;
	}

	public function display($messages=array()){
		// -- Messages ------------------------------------------------------------
		if ($messages){
			foreach($messages as $name)
				$this->core->message($name, $this->user->lang('usermap'), 'green');
		}

		// get the saved data
		$arrValues		= $this->config->get_config('usermap');
		if ($this->arrData !== false) $arrValues = $this->arrData;

		// -- Template ------------------------------------------------------------
		// initialize form class
		$objForm				= register('form', array('um_settings'));
		$objForm->reset_fields();
		$objForm->lang_prefix	= 'um_';
		$objForm->validate		= true;
		$objForm->use_fieldsets	= true;
		$objForm->add_fieldsets($this->fields());

		// Output the form, pass values in
		$objForm->output($arrValues);

		$this->core->set_vars(array(
			'page_title'	=> $this->user->lang('usermap').' '.$this->user->lang('settings'),
			'template_path'	=> $this->pm->get_data('usermap', 'template_path'),
			'template_file'	=> 'admin/manage_settings.html',
			'page_path'			=> [
					['title'=>$this->user->lang('menu_admin_panel'), 'url'=>$this->root_path.'admin/'.$this->SID],
					['title'=>$this->user->lang('um_breadcrumb_settings'), 'url'=>' '],
			],
			'display'		=> true
	  ));
	}

}
registry::register('usermapSettings');
?>