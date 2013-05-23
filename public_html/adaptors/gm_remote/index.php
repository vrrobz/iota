<?php

$apiKey = '8c1949ea7b1931c04a2f33862';
$apiSecret = '5afe1be5c91293f9';
$tokenUrl = 'https://developer.gm.com/api/v1/oauth/access_token';
$vehicleListUrl = 'https://developer.gm.com/api/v1/account/vehicles?offset=0&size=5';
$vehicleStartUrl = 'https://developer.gm.com/api/v1/account/vehicles/{VIN}/commands/start';

// Retrieve Access Token
$curl = curl_init($tokenUrl);

curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
    CURLOPT_USERPWD => $apiKey . ':' . $apiSecret,
    CURLOPT_HTTPGET => true,
    CURLOPT_HTTPHEADER => array('Accept: application/json')
));

$response = json_decode(curl_exec($curl));
$accessToken = $response->access_token;

curl_close($curl);

// Retrieve Valid VIN
$curl = curl_init($vehicleListUrl);

curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
    CURLOPT_USERPWD => $apiKey . ':' . $apiSecret,
    CURLOPT_HTTPGET => true,
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . $accessToken,
        'Accept: application/json'
    )
));

$response = json_decode(curl_exec($curl));
$vehicleVin = $response->vehicles->vehicle[5]->vin;

curl_close($curl);

// Start Vehicle
$url = str_replace('{VIN}', $vehicleVin, $vehicleStartUrl);
$curl = curl_init($url);

curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => '',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . $accessToken,
        'Accept: application/json',
        'Accept-Language: en-us',
        'Accept-Encoding: gzip, deflate'
    )
));

$response = (curl_exec($curl));

curl_close($curl);

echo '<pre>';
var_dump($response);
echo '</pre>';