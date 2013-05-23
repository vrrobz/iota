<?php
/**
 * Provides access to the GM Remote vehicle API.
 *
 * @author Nick Williams
 * @version 1.0.0
 */
class Accessor_GM_Remote {
    const URL_ACCESS_TOKEN = 'https://developer.gm.com/api/v1/oauth/access_token';
    const URL_VEHICLE_LIST = 'https://developer.gm.com/api/v1/account/vehicles?offset=0&size=5';
    const URL_VEHICLE_COMMANDS = 'https://developer.gm.com/api/v1/account/vehicles/{VIN}/commands/';

    protected static $_debug = true;

    protected $_apiKey;
    protected $_apiSecret;
    protected $_accessToken;
    protected $_vehicles = array();

    /**
     * Initializes a new connection to the GM remote vehicle API.
     *
     * @param string $apiKey a valid GM developer API key
     * @param string $apiSecret a valid GM developer API secret
     */
    public function __construct($apiKey, $apiSecret) {
        $this->_apiKey = $apiKey;
        $this->_apiSecret = $apiSecret;
        $this->_accessToken = $this->_requestAccessToken();
        $this->_vehicles = $this->_requestVehicleList();
    }

    /**
     * Outputs the specified text in preformatted HTML tags.
     *
     * @param string $text the text to be displayed
     */
    protected static function _pre($text = '') {
        echo '<pre>';
        echo $text;
        echo '</pre>';
    }

    /**
     * Sends a cURL request, expecting a JSON-encoded response.
     *
     * @param string $url the url to which the request will be made
     * @param array $curlOptions additional cURL options to be applied
     *
     * @return mixed a JSON-decoded object representing the response
     */
    protected static function _requestJson($url, $curlOptions) {
        // Setup
        $result = null;

        // Build cURL Request
        $curl = curl_init($url);

        curl_setopt_array($curl, $curlOptions);

        $result = curl_exec($curl);

        curl_close($curl);

        if(self::$_debug) {
            self::_pre($result);
        }

        return json_decode($result);
    }

    /**
     * Performs an API request to receive a temporary access token.
     *
     * @return string the requested temporary access token
     */
    protected function _requestAccessToken() {
        $response = $this->_requestJson(
            self::URL_ACCESS_TOKEN,
            array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_USERPWD => $this->_apiKey . ':' . $this->_apiSecret,
                CURLOPT_HTTPGET => true,
                CURLOPT_HTTPHEADER => array('Accept: application/json')
            )
        );

        return $response->access_token;
    }

    /**
     * Retrieves a list of available vehicles.
     *
     * @return mixed a JSON-decoded object representing the list of vehicles
     */
    protected function _requestVehicleList() {
        $response = $this->_requestJson(
            self::URL_VEHICLE_LIST,
            array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_USERPWD => $this->_apiKey . ':' . $this->_apiSecret,
                CURLOPT_HTTPGET => true,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $this->_accessToken,
                    'Accept: application/json'
                )
            )
        );

        return $response->vehicles->vehicle;
    }

    /**
     * Sends the start command to the specified vehicle.
     *
     * @param string $vin the VIN associated with the target vehicle
     *
     * @return bool whether or not the request was successful
     */
    public function startVehicle($vin = null) {
        if(is_null($vin)) {
            $vin = $this->_vehicles[5]->vin;
        }

        $url = str_replace('{VIN}', $vin, self::URL_VEHICLE_COMMANDS . '/start');
        $response = $this->_requestJson(
            $url,
            array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => '',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $this->_accessToken,
                    'Accept: application/json',
                    'Accept-Language: en-us',
                    'Accept-Encoding: gzip, deflate'
                )
            )
        );

        return true;
    }

    /**
     * Sends the stop command to the specified vehicle.
     *
     * @param string $vin the VIN associated with the target vehicle
     *
     * @return bool whether or not the request was successful
     */
    public function stopVehicle($vin = null) {
        if(is_null($vin)) {
            $vin = $this->_vehicles[5]->vin;
        }

        $url = str_replace('{VIN}', $vin, self::URL_VEHICLE_COMMANDS . '/stop');
        $response = $this->_requestJson(
            $url,
            array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => '',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $this->_accessToken,
                    'Accept: application/json',
                    'Accept-Language: en-us',
                    'Accept-Encoding: gzip, deflate'
                )
            )
        );

        return true;
    }

    /**
     * Sends the start command to all available vehicles.
     *
     * @return bool whether or not all requests were successful
     */
    public function startAllVehicles() {
        foreach($this->_vehicles as $vehicle) {
            $this->startVehicle($vehicle->vin);
            sleep(1);
        }

        return true;
    }

    /**
     * Sends the stop command to all available vehicles.
     *
     * @return bool whether or not all requests were successful
     */
    public function stopAllVehicles() {
        foreach($this->_vehicles as $vehicle) {
            $this->stopVehicle($vehicle->vin);
            sleep(1);
        }

        return true;
    }

    /**
     * Requests that the specified vehicle be locked.
     *
     * @param string $vin the VIN associated with the target vehicle
     *
     * @return bool whether or not the request was successful
     */
    public function lockVehicle($vin = null) {
        if(is_null($vin)) {
            $vin = $this->_vehicles[5]->vin;
        }

        $url = str_replace('{VIN}', $vin, self::URL_VEHICLE_COMMANDS . '/lockDoor');
        $response = $this->_requestJson(
            $url,
            array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => '',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $this->_accessToken,
                    'Accept: application/json',
                    'Accept-Language: en-us',
                    'Accept-Encoding: gzip, deflate'
                )
            )
        );

        return true;
    }

    /**
     * Requests that the specified vehicle be unlocked.
     *
     * @param string $vin the VIN associated with the target vehicle
     *
     * @return bool whether or not the request was successful
     */
    public function unlockVehicle($vin = null) {
        if(is_null($vin)) {
            $vin = $this->_vehicles[5]->vin;
        }

        $url = str_replace('{VIN}', $vin, self::URL_VEHICLE_COMMANDS . '/unlockDoor');
        $response = $this->_requestJson(
            $url,
            array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => '',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $this->_accessToken,
                    'Accept: application/json',
                    'Accept-Language: en-us',
                    'Accept-Encoding: gzip, deflate'
                )
            )
        );

        return true;
    }

    /**
     * Locks all available vehicles.
     *
     * @return bool whether or not all requests were successful
     */
    public function lockAllVehicles() {
        foreach($this->_vehicles as $vehicle) {
            $this->lockVehicle($vehicle->vin);
            sleep(1);
        }

        return true;
    }

    /**
     * Unlocks all available vehicles.
     *
     * @return bool whether or not all requests were successful
     */
    public function unlockAllVehicles() {
        foreach($this->_vehicles as $vehicle) {
            $this->unlockVehicle($vehicle->vin);
            sleep(1);
        }

        return true;
    }
}