<?php
	class Device {
		function getAllDevices($params) {
			echo("I should be returning all devices here");
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