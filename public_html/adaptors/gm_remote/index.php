<?php
/**
 * Adaptor script for the GM remote vehicle API.
 *
 * @author Nick Williams
 * @version 1.0.0
 */

require_once '../../../lib/Accessor/GM/Remote.php';

// Setup
$result = null;
$apiKey = '8c1949ea7b1931c04a2f33862';
$apiSecret = '5afe1be5c91293f9';


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








// Initialize GM Remote accessor.
$apiAccessor = new Accessor_GM_Remote($apiKey, $apiSecret);

$apiAccessor->startAllVehicles();

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
