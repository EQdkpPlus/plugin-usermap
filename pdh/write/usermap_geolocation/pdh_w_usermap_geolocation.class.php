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

		public static function __shortcuts(){
			$shortcuts = array('geolocation' => 'geolocation');
			return array_merge(parent::$shortcuts, $shortcuts);
		}

		public function add($intID, $floatLatitude, $floarLongitude){
			$resQuery = $this->db->prepare("INSERT INTO __usermap_geolocation :p")->set(array(
				'user_id'			=> $intID,
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
			$resQuery = $this->db->prepare("UPDATE __usermap_geolocation :p WHERE user_id=?")->set(array(
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
				$this->fetchUserLocation($userid);
			}
			$this->pdh->enqueue_hook('usermap_geolocation_update');
		}

		public function fetchUserLocation($user_id, $settingsdata = array()){
			
			// load location data
		    $street			    = $this->getConfig($user_id, $settingsdata, 'street', false);
		    $streetNumber	    = $this->getConfig($user_id, $settingsdata, 'streetnumber', false);
		    $city				= $this->getConfig($user_id, $settingsdata, 'city', 1);
		    $zip				= $this->getConfig($user_id, $settingsdata, 'zip', false);
		    $country			= $this->getConfig($user_id, $settingsdata, 'country', 17);
		    
		    

			// fetch latitude & longitude if at least city and country are available
			$this->pdl->log('usermaps', 'Street: '.$street.' '.$streetNumber.', City: '.$city.', ZIP: '.$zip.', country: '.$country);
			
			if(!empty($country) && !empty($city)){
				$this->pdl->log('usermaps', 'City and country available, start fetching the lat/lon coordinates');
				$result = $this->geoloc->getCoordinates($street, $streetNumber, $city, $zip, $country);
				$this->pdl->log('usermaps', 'Fetched Lat/Lon coordinates');
				if($user_id > 0 && strlen($result['longitude']) && strlen($result['latitude'])){
					$lastupdate = $this->pdh->get('usermap_geolocation', 'lastupdate', array($user_id));
					if($lastupdate > 0){
						if((($lastupdate+(86400*7)) < $this->time->time) || count($settingsdata)){
							$this->update($user_id, $result['latitude'], $result['longitude']);
						}
					}else{
						$this->add($user_id, $result['latitude'], $result['longitude']);
					}

				}
			}
			$this->pdh->enqueue_hook('usermap_geolocation_update');
		}

		// custom function to load either the saved data used in config or a defined fallback value
		private function getConfig($userid, $settingsdata, $fieldname, $defaultfield){
		    $strFieldname = ($this->config->get($fieldname,	'usermap')) ? 'userprofile_'.$this->config->get($fieldname,	'usermap') : 'userprofile_'.$defaultfield;

		    if(count($settingsdata)){
		        $strValue = (isset($settingsdata[$strFieldname])) ? $settingsdata[$strFieldname] : '';
		    } else {
		        $strValue = $this->pdh->get('user', 'custom_fields', array($userid, $strFieldname));
		    }

			if(!is_string($strValue)) return '';

			return $strValue;
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
