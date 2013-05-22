<?php
	require_once('../config/config.php');
	require_once('DBAccess.class.php');
	//This will likely be the thing that handles the routing
	
	
	
	$db = new DBAccess();
	$db->getConnection();
	$sql = "Select * from Devices";
	$db->executeQuery($sql);
	$resArray = $db->getResultArray();
	
	$f = fopen(API_SCHEMA, 'r');
	
	$routes = json_decode(fread($f, filesize(API_SCHEMA)));
	echo("<pre>\n");
	var_dump($routes);
	echo("\n</pre>");
	
	$uri = "/api/devices/12345/actions/";
	$pathArray = explode('/', $uri);
	//Do stuff
	echo("'".$pathArray[count($pathArray) - 1]."'<br />");
	
	$modURI = implode('/', $pathArray);
	echo($modURI);
	
?>