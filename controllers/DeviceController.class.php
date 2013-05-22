<?php
	require_once("Device.class.php");
	require_once("DeviceType.class.php");
	
	class DeviceController {
		function getAllDevices($params) {
			$d = new Device();
			$deviceArray = $d->getAllDevices();
			$retArray = array();
			for($n = 0; $n < count($deviceArray); $n++) {
				$device = new Device();
				$device->init($deviceArray[$n]);
				$tmpArray = array();
				$tmpArray["name"] = $device->getData("name");
				$tmpArray["uri"] = 'http://'.$_SERVER['HTTP_HOST'].API_ROOT.'/device/'.$deviceArray[$n];
				//FIXME: I think this is going to break
				array_push($retArray, $tmpArray);
			}
			//TODO: abstract this out to only return the array, let the APIInterface manage the HTTP stuff
			header("HTTP/1.0 200 OK");
			echo(json_encode($retArray));
		}
		
		function getDevice($params) {
			echo("I should be getting you the device specifics here for device ID ".$params["id"]);
		}
		
		function getDeviceActions($params) {
			echo("I should be getting you the actions available for this device here for device ID ".$params["id"]);
		}
		
		function doDeviceAction($params) {
			echo("I should be performing some post action here");
		}
	}
?>