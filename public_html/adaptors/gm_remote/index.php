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
$tokenUrl = 'https://developer.gm.com/api/v1/oauth/access_token';
$vehicleListUrl = 'https://developer.gm.com/api/v1/account/vehicles?offset=0&size=5';
$vehicleStartUrl = 'https://developer.gm.com/api/v1/account/vehicles/{VIN}/commands/start';

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