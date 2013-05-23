<?php
	/*
	Handles all of the communication between the public API and the device API for the lightswitch
	*/
	
	if(isset($_SERVER["PATH_INFO"])) {
		$pageURI = $_SERVER["PATH_INFO"];
	} else {
		$pageURI = str_replace('index.php', '', $_SERVER["REQUEST_URI"]);
	}
	
	
	$pathArray = explode('/', $pageURI);
	//Remove trailing slash
	if($pathArray[count($pathArray) - 1] == '') {
		array_pop($pathArray);
	}

	$idCount = 2;

	$id = '';

	//FIXME: NOT DRY!!!
	//Extract the numeric IDs, then generalise them for a search
	//FIXME: This means we can only accept numeric IDs. I don't like this. HACK HACK HACK
	for($n = 0; $n < count($pathArray); $n++) {
		if(is_numeric($pathArray[$n])) {
			$id = $pathArray[$n];
		}
	}
	
	$lightswitchEndpoint = "http://209.114.35.97:22902/test_devices/lightswitch";
	//$lightswitchEndpoint = "http://iota.uberwork.local:8080/test_devices/lightswitch";
	
	//TODO: Create an API to allow adaptors to register themselves so everyone can use the same JSON and be happy.
	
	
	if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
		if(isset($_REQUEST["dim_light"])) {
			header("HTTP/1.0 501 Not Implemented");
			exit();
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
			} else {
				$ret = "error";
			}
				
			$c = curl_init();
			curl_setopt($c, CURLOPT_HTTPGET, true);
			curl_setopt($c, CURLOPT_URL, $lightswitchEndpoint.$url);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
			$res = curl_exec($c);
			curl_close($c);
			if($res == "true") {
				header("HTTP/1.0 200 OK");
				echo $ret;
			} else if($res === false) {
				header("HTTP/1.0 500 Server error");
				echo("Could not execute code on endpoint");
				exit();
			} else {
				header("HTTP/1.0 500 Server error");
				echo("Service returned false");
			}
		}
	} else if(strtolower($_SERVER['REQUEST_METHOD']) == 'get') {
		$c = curl_init();
		$url = "/status/get";
		curl_setopt($c, CURLOPT_HTTPGET, true);
		curl_setopt($c, CURLOPT_URL, $lightswitchEndpoint.$url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		$res = curl_exec($c);
		header("HTTP/1.0 200 OK");
		echo($res);
	} else {
		header("HTTP/1.0 501 Not Implemented");
		echo("Not implemented");
		exit();
	}
?>