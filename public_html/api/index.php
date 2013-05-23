<?php
	require_once("../../config/config.php");
	require_once("APIInterface.class.php");
	
	if(isset($_SERVER["PATH_INFO"])) {
		$pageURI = $_SERVER["PATH_INFO"];
	} else {
		$pageURI = str_replace('index.php', '', $_SERVER["REQUEST_URI"]);
	}

	//When we implement an authentication system, it needs to go here. 
	if(!isset($pageURI)) {
		header("HTTP/1.0 400 Bad Request");
		//echo("Didn;t find it.");
		exit();
	}
	
	$api = new APIInterface();
	$api->init($_REQUEST);
	$pageURI = str_replace(API_ROOT, '', $pageURI);
	if(($resource = $api->routeByURI($pageURI)) === false) {
		header("HTTP/1.0 404 Not Found");
		echo("Unable to locate the resource for ".$pageURI);
		exit();
	}

	//When we implement an authroization system, it should go here.
	$api->render();
?>