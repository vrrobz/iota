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
		
		function getDeviceActions($params) {
			//echo("I should be getting you the actions available for this device here for device ID ".$params["id"]);
			$d = new Device();
			$d->init($params["id"]);
			$dt = new DeviceType();
			$dt->init($d->getData("device_type"));
			//TODO: See above
			
			//Want to replace URIs with full URIs. This would usually be done by the adaptor, but... 
			$actArray = json_decode($dt->getData("interface"), true);
			foreach(array_keys($actArray["actions"]) as $action) {
				$actArray["actions"][$action]["uri"] = "http://".$_SERVER["HTTP_HOST"].$dt->getData("endpoint").$actArray["actions"][$action]["uri"];
			}
			
			$newJSON = json_encode($actArray);
			
			header("HTTP/1.0 200 OK");
			echo($newJSON);
		}
	}
?>