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

$lang = array(
	'usermap'					=> 'Usermap',
	'usermap_short_desc'		=> 'Landkarte aller Benutzer erstellen',
	'usermap_long_desc'			=> 'Usermap ist ein Plugin um Google Maps Landkarten von Benutzerstandorten zu erstellen.',
	'usermap_not_installed'		=> 'Usermap ist nicht installiert.',

	// Main Menu
	'um_mainmenu_usermap'		=> 'Usermap',

	// Admin area
	'um_breadcrumb_settings'	=> 'Usermap Einstellungen',
	'um_fs_location'			=> 'Geolocation Einstellungen',
	'um_f_street'				=> 'Benutzerprofilfeld Straße',
	'um_f_help_street'			=> 'Falls ein Benutzerprofilfeld für Straßennamen vorhanden ist, kann dies zur genaueren Lokalisierung der Benutzer auf der Landkarte verwendet werden.',
	'um_f_streetnumber'			=> 'Benutzerprofilfeld Hausnummer',
	'um_f_help_streetnumber'	=> 'Falls ein Benutzerprofilfeld für Hausnummer vorhanden ist, kann dies zur genaueren Lokalisierung der Benutzer auf der Landkarte verwendet werden.',
	'um_f_city'					=> 'Benutzerprofilfeld Stadt',
	'um_f_help_city'			=> 'Falls ein Benutzerprofilfeld für Stadt vorhanden ist, kann dies zur genaueren Lokalisierung der Benutzer auf der Landkarte verwendet werden.',
	'um_f_zip'					=> 'Benutzerprofilfeld PLZ',
	'um_f_help_zip'				=> 'Falls ein Benutzerprofilfeld für PLZ vorhanden ist, kann dies zur genaueren Lokalisierung der Benutzer auf der Landkarte verwendet werden.',
	'um_f_country'				=> 'Benutzerprofilfeld Land',
	'um_f_help_country'			=> 'Falls ein Benutzerprofilfeld für Land vorhanden ist, kann dies zur genaueren Lokalisierung der Benutzer auf der Landkarte verwendet werden.',

	// User map page
	'um_title_page'				=> 'Benutzerkarte',

	// credits
	'um_credits'				=> "Usermap %s",
);
?>
