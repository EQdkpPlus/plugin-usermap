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

if (!class_exists('pdh_w_usermap_geolocation')){
	class pdh_w_usermap_geolocation extends pdh_w_generic {

		public function add($intID, $floatLatitude, $floarLongitude){
			$resQuery = $this->db->prepare("INSERT INTO __guildbank_items :p")->set(array(
				'longitude'			=> $floarLongitude,
				'latitude'			=> $floatLatitude,
				'last_update'		=> $this->time->time,
			))->execute();
			$id = $resQuery->insertId;
			$this->pdh->enqueue_hook('usermap_geolocation_update');
			if ($resQuery) return $id;
			return false;
		}

		public function update($intID, $floatLatitude, $floarLongitude){
			$resQuery = $this->db->prepare("UPDATE __guildbank_items :p WHERE item_id=?")->set(array(
				'longitude'			=> $floarLongitude,
				'latitude'			=> $floatLatitude,
				'last_update'		=> $this->time->time,
			))->execute($intID);
			$this->pdh->enqueue_hook('usermap_geolocation_update');
			if ($resQuery) return $intID;
			return false;
		}

		public function fetchUserLocations(){
			$userlist 	= $this->pdh->get('user', 'id_list');
			foreach($userlist as $userid){
				$street			= '';
				$streetNumber	= '';
				$city			= $this->pdh->get('user', 'custom_fields', array($userid, 'userprofile_1');
				$zip			= '';
				$country		= $this->pdh->get('user', 'custom_fields', array($userid, 'userprofile_17');;
				$result = geolocation::getCoordinates($street, $streetNumber, $city, $zip, $country);
				if($userid > 0 && $result['longitude'] > 0 && $result['latitude'] > 0){
					$this->add($userid, $result['latitude'], $result['longitude']);
				}
			}
		}

		public function delete($intID){
			$this->db->prepare("DELETE FROM __usermap_geolocation WHERE user_id=?")->execute($intID);
			$this->pdh->enqueue_hook('usermap_geolocation_update');
			return true;
		}

		public function truncate(){
			$this->db->query("TRUNCATE __usermap_geolocation");
			$this->pdh->enqueue_hook('usermap_geolocation_update');
			return true;
		}
	} //end class
} //end if class not exists
?>
