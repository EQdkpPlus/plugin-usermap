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
	die('Do not access this file directly.');
}

if (!class_exists('pdh_r_usermap_geolocation')){
	class pdh_r_usermap_geolocation extends pdh_r_generic{
		private $data;

		public $hooks = array(
			'usermap_geolocation_update'
		);

		public $presets = array(
		);

		public function reset(){
			$this->pdc->del('pdh_usermap_geolocation_table');
			unset($this->data);
		}

		public function init(){
			// try to get from cache first
			$this->data = $this->pdc->get('pdh_usermap_geolocation_table');
			if($this->data !== NULL){
				return true;
			}

			// empty array as default
			$this->data = array();

			// read all guildbank_fields entries from db
			$sql = 'SELECT * FROM `__usermap_geolocation` ORDER BY user_id ASC;';
			$result = $this->db->query($sql);
			if ($result){
				// add row by row to local copy
				while (($row = $result->fetchAssoc())){
					$this->data[(int)$row['user_id']] = array(
						'id'			=> (int)$row['user_id'],
						'lat'			=> str_replace(',', '.', (float)$row['latitude']),
						'long'			=> str_replace(',', '.', (float)$row['longitude']),
						'lastupdate'	=> (int)$row['last_update'],
					);
				}
			}

			// add data to cache
			$this->pdc->put('pdh_usermap_geolocation_table', $this->data, null);
			return true;
		}

		public function get_id_list(){
			if (is_array($this->data)){
				return array_keys($this->data);
			}
			return array();
		}

		public function get_list(){
			if (is_array($this->data)){
				return $this->data;
			}
			return array();
		}

		public function get_latitude($id){
			return (isset($this->data[$id]) && isset($this->data[$id]['lat']) && $this->data[$id]['lat']) ? $this->data[$id]['latitude'] : 0;
		}

		public function get_longitude($id){
			return (isset($this->data[$id]) && isset($this->data[$id]['lng']) && $this->data[$id]['lng']) ? $this->data[$id]['lng'] : 0;
		}

		public function get_lastupdate($id){
			return (isset($this->data[$id]) && $this->data[$id]['lastupdate']) ? (int)$this->data[$id]['lastupdate'] : 0;
		}
	} //end class
} //end if class not exists
?>
