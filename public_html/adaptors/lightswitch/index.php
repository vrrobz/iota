<?php
	/*
	Handles all of the communication between the public API and the device API for the lightswitch
	*/
	
	var $lightswitchEndpoint = "http://iota.uberwork.local:8080/test_devices/lightswitch";
	
	//TODO: Create an API to allow adaptors to register themselves so everyone can use the same JSON and be happy.
	
	
	if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
		if(isset($_REQUEST["dim_light"])) {
			header("HTTP/1.0 501 Not Implemented");
			return false;
		}
		if(isset($_REQUEST["toggle_state"])) {
			$ret = '';
			if(strtolower($_REQUEST["toggle_state"]) == "on") {
				//$query = "action=command&type=on";
				$url = "/command/on";
				$ret = "ON";
			} else if(strtolower($_REQUEST["toggle_state"]) == "off") {
				//$query = "action=command&type=off";
				$url = "/command/off";
				$ret = "OFF";
			}
				
			$c = curl_init();
			curl_setopt($c, CURLOPT_HTTPGET, true);
			curl_setopt($c, CURLOPT_URL, $lightswitchEndpoint.$url);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
			$res = curl_exec($c);
			if($res == "true") {
				return $ret;
			}
		}
	}
?>