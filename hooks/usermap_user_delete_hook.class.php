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

if (!defined('EQDKP_INC'))
{
  header('HTTP/1.0 404 Not Found');exit;
}


/*+----------------------------------------------------------------------------
  | usermap_usersettings_update_hook
  +--------------------------------------------------------------------------*/
if (!class_exists('usermap_user_delete_hook')){
	class usermap_user_delete_hook extends gen_class{
		/* List of dependencies */

		/**
		* usersettings_update
		* Do the hook 'usersettings_update'
		*
		* @return array
		*/
		public function user_delete($data){
			$userID = $data['user_id'];
			
			$this->db->prepare("DELETE FROM __usermap_geolocation WHERE user_id=?")->execute($userID);
			
			$this->pdh->enqueue_hook('usermap_geolocation_update');
			$this->pdh->process_hook_queue();
			
			return true;
		}
	}
}
?>