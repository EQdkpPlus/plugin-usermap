<?php

/**
 * Geolocation
 *
 * Get latitude/longitude or address using OSM Nominatim API
 * https://wiki.openstreetmap.org/wiki/Nominatim
 *
 */
 if(!defined('EQDKP_INC'))
 {
 	header('HTTP/1.0 Not Found');
 	exit;
 }

if(!class_exists('geolocation')) {
	class geolocation extends gen_class {
		// API URL
		const nominatim_apiURL = 'https://nominatim.openstreetmap.org/';

		/**
		* Do call
		*
		* @return object
		* @param  array  $parameters
		*/
		protected function doCall($parameters = array(), $type="normal"){
			// define url
			$url = self::nominatim_apiURL.(($type == "reverse") ? 'reverse?format=json&' : 'search?format=json&');
			foreach ($parameters as $key => $value) $url .= $key . '=' . urlencode($value) . '&';

			// fetch the data
			$response = register('urlfetcher')->fetch($url);
			if($response){
				$response = json_decode($response);
				return $response->results;
			}
			return false;
		}

		/**
		* Get address using latitude/longitude
		*
		* @return array(label, components)
		* @param  float			$latitude
		* @param  float			$longitude
		*/
		public function getAddress($latitude, $longitude){
			$addressSuggestions = $this->getAddresse_helper($latitude, $longitude);
			return $addressSuggestions[0];
		}

		/**
		* Get possible addresses using latitude/longitude
		*
		* @return array(label, street, streetNumber, city, cityLocal, zip, country, countryLabel)
		* @param  float			$latitude
		* @param  float			$longitude
		*/
		public function getAddresse_helper($latitude, $longitude){
			// init results
			$addresses = array();

			// define result
			$addressSuggestions = $this->doCall(array(
				'lat'				=> $latitude,
				'lon'				=> $longitude,
				'addressdetails'	=> 1
			), 'reverse');

			// init address
			$address = array();

			// define address components by looping all address components
			foreach ($addressSuggestion->address_components as $component) {
				$address['components'][] = array(
					'long_name'		=> $component->long_name,
					'short_name'	=> $component->short_name,
					'types'			=> $component->types
				);
			}
		}

		/**
		* Get coordinates latitude/longitude
		*
		* @return array  The latitude/longitude coordinates
		* @param  string $street[optional]
		* @param  string $streetNumber[optional]
		* @param  string $city[optional]
		* @param  string $zip[optional]
		* @param  string $country[optional]
		*/
		public function getCoordinates($street = null, $streetNumber = null, $city = null, $zip = null, $country = null) {
			$item = array();

			if (!empty($street))		$item[] = $street;
			if (!empty($streetNumber))	$item[] = $streetNumber;
			if (!empty($city))			$item[] = $city;
			if (!empty($zip))			$item[] = $zip;
			if (!empty($country))		$item[] = $country;

			$address = implode(' ', $item);

			$results = $this->doCall(array(
				'q'	=> $address,
			));

			// return coordinates latitude/longitude
			return array(
				'latitude'	=> array_key_exists(0, $results) ? (float) $results[0]->->lat : null,
				'longitude'	=> array_key_exists(0, $results) ? (float) $results[0]->->lon : null
			);
		}
	}
}
