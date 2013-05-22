<?php
	require_once('../config/config.php');
	require_once('DBAccess.class.php');
	//This will likely be the thing that handles the routing
	
	
	
	$db = new DBAccess();
	$db->getConnection();
	$sql = "Select * from Devices";
	$db->executeQuery($sql);
	$resArray = $db->getResultArray();
	
	echo("Hello<br />");
	
	var_dump($resArray);
?>