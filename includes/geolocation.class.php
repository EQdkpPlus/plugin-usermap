<?php

namespace eqdkpplus\geolocation;

/**
 * Geolocation
 *
 * Get latitude/longitude or address using Google Maps API
 *
 */
class geolocation{
	// API URL
	const gmaps_apiURL = 'https://maps.googleapis.com/maps/api/geocode/json';

	/**
	* Do call
	*
	* @return object
	* @param  array  $parameters
	*/
	protected function doCall($parameters = array()){

		// define url
		$url = self::gmaps_apiURL . '?';
		foreach ($parameters as $key => $value) $url .= $key . '=' . urlencode($value) . '&';
		$url .= 'key=' . env('GOOGLE_MAP_API_KEY');

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
	* @param  float        $latitude
	* @param  float        $longitude
	*/
	public function getAddress($latitude, $longitude){
		$addressSuggestions = $this->getAddresses($latitude, $longitude);
		return $addressSuggestions[0];
	}

	/**
	* Get possible addresses using latitude/longitude
	*
	* @return array(label, street, streetNumber, city, cityLocal, zip, country, countryLabel)
	* @param  float        $latitude
	* @param  float        $longitude
	*/
	public function getAddresses($latitude, $longitude){
		// init results
		$addresses = array();

		// define result
		$addressSuggestions = $this->doCall(array(
			'latlng'	=> $latitude . ',' . $longitude,
			'sensor'	=> 'false'
		));

		// loop addresses
		foreach ($addressSuggestions as $key => $addressSuggestion) {
			// init address
			$address = array();

			// define label
			$address['label'] = isset($addressSuggestion->formatted_address) ? $addressSuggestion->formatted_address : null;

			// define address components by looping all address components
			foreach ($addressSuggestion->address_components as $component) {
				$address['components'][] = array(
					'long_name'		=> $component->long_name,
					'short_name'	=> $component->short_name,
					'types'			=> $component->types
				);
			}
			$addresses[$key] = $address;
		}
		return $addresses;
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
			'address'	=> $address,
			'sensor'	=> 'false'
		));

		// return coordinates latitude/longitude
		return array(
			'latitude'	=> array_key_exists(0, $results) ? (float) $results[0]->geometry->location->lat : null,
			'longitude'	=> array_key_exists(0, $results) ? (float) $results[0]->geometry->location->lng : null
		);
	}
}
