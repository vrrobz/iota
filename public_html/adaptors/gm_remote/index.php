<?php
/**
 * Adaptor script for the GM remote vehicle API.
 *
 * @author Nick Williams
 * @version 1.0.0
 */
require_once('../../../config/config.php');
require_once '../../../lib/Accessor/GM/Remote.php';
require_once("Device.class.php");
require_once("DeviceType.class.php");

// Setup
$result = null;
$apiKey = '8c1949ea7b1931c04a2f33862';
$apiSecret = '5afe1be5c91293f9';


// Initialize GM Remote accessor.
$apiAccessor = new Accessor_GM_Remote($apiKey, $apiSecret);

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

if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
	if($id == '') {
		//header("HTTP/1.0 400 Bad Request");
		//echo("Missing ID");
		//exit();
		$vin = '';
	} else {
		$device = new Device();
		if(!$device->init($id)) {
			header("HTTP/1.0 404 Device not found");
			echo("Could not find device with ID ".$id);
			exit();
		}
	
		$vin = json_decode($device->getData("authentication"));
	}
	
	if(isset($_REQUEST["engine_state"])) {
		$ret = '';
	
			
		if(strtolower($_REQUEST["engine_state"]) == "on") {
			//$query = "action=command&type=on";
			if($vin != '') {
				$apiAccessor->startVehicle($vin);
				$ret = "ON";
			} else {
				$apiAccessor->startAllVehicles();
				$ret = "ALL ON";
			}
			header("HTTP/1.0 200 OK");
			header("Content-type: application/json");
			echo('{"state":"' .$ret. '"}');
			exit();
		} else if(strtolower($_REQUEST["engine_state"]) == "off") {
			if($vin != '') {
				$apiAccessor->stopVehicle($vin);
				$ret = "OFF";
			} else {
				$apiAccessor->stopAllVehicles();
				$ret = "ALL OFF";
			}
			header("HTTP/1.0 200 OK");
			header("Content-type: application/json");
			echo('{"state":"' .$ret. '"}');
			exit();
		} else {
			header("HTTP/1.0 400 Bad Request");
			echo("Do not recognize command");
			exit();
		}
	}
	
	if(isset($_REQUEST["lock_state"])) {
		if(strtolower($_REQUEST["lock_state"]) == 'lock') {
			if($vin != '') {
				$apiAccessor->lockVehicle($vin);
				$ret = "LOCKED";
			} else {
				$apiAccessor->lockAllVehicles();
				$ret = "ALL LOCKED";
			}
			header("HTTP/1.0 200 OK");
			header("Content-type: application/json");
			echo('{"state":"' .$ret. '"}');
			exit();
		} else if(strtolower($_REQUEST["lock_state"]) == 'lock') {
			if($vin != '') {
				$apiAccessor->unlockVehicle($vin);
				$ret = "UNLOCKED";
			} else {
				$apiAccessor->unlockAllVehicles();
				$ret = "ALL UNLOCKED";
			}
			header("HTTP/1.0 200 OK");
			header("Content-type: application/json");
			echo('{"state":"' .$ret. '"}');
			exit();
		} else {
			header("HTTP/1.0 400 Bad Request");
			echo("Do not recognize command");
			exit();
		}
	}
			
		
	
} else if(strtolower($_SERVER['REQUEST_METHOD']) == 'get') {
	header("HTTP/1.0 501 Not Implemented");
	echo("Currently not supported");
	exit();
} else {
	header("HTTP/1.0 501 Not Implemented");
	echo("Not implemented");
	exit();
}




//$apiAccessor->startAllVehicles();

// Handle Incoming Requests
//switch(strtolower($_SERVER['REQUEST_METHOD'])) {
//    default:
//        header("HTTP/1.0 501 Not Implemented");
//        exit();
//
//    case 'post':
//        switch($_REQUEST['']) {
//            default:
//                header("HTTP/1.0 501 Not Implemented");
//                exit();
//
//            case '':
//
//                break;
//        }
//        break;
//
//    case 'get':
//        switch($_REQUEST['']) {
//            default:
//                header("HTTP/1.0 501 Not Implemented");
//                exit();
//
//            case '':
//
//                break;
//        }
//        break;
//}
