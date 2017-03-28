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

$usermapSQL = array(
	'uninstall' => array(
		1	=> 'DROP TABLE IF EXISTS `__usermap_geolocation`',
	),

	'install'   => array(
		1 => "CREATE TABLE IF NOT EXISTS __usermap_geolocation (
				user_id mediumint(8) unsigned NOT NULL,
				latitude float default 0,
				longitude float default 0,
				last_update int(11) default NULL,
				PRIMARY KEY  (user_id)
			) DEFAULT CHARSET=utf8 COLLATE=utf8_bin;",
	)
);

?>
