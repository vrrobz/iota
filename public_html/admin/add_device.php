<?php
	require_once('../../config/config.php');
	require_once('DBAccess.class.php');
	require_once('DeviceType.class.php');
	require_once('Device.class.php');
	
	$valArray = array();
	
	$pop = '';
	if(isset($_REQUEST["name"])) {
		//Save the form to the database, give them a confirmation
		//$pop = "Did not add device type - not implemented";
		$dt = new Device();
		if(!$dt->init($_REQUEST)) {
			$pop = $pop. "Could not init";
		} else {
			if(!$dt->update()) {
				$errors = $dt->getErrors();
				foreach(array_keys($errors) as $field) {
					$pop = $pop. $field.": ".$errors[$field]."<br />";
				}
				$valArray = $_REQUEST;
			} else {
				$pop = $pop."Successfully added ".$_REQUEST["name"];
			}
		}
	}
	
	$dt = new DeviceType();
	$deviceTypeArray = $dt->getAllDeviceTypes();
?>
<html>
	<head>
		<title>Add Device</title>
	</head>
	<body>

	<div class="message"><?php echo($pop); ?></div>

		<h1>Add a new Device</h1>
		<form name="addDeviceType" method="POST">
			<strong>Device Name:</strong> <input type="text" name="name" value="<?php echo($valArray["name"]); ?>" /><br />
			<strong>Device Type:</strong> <select name="device_type">
				<option value=""> -- SELECT ONE --</option>
<?php
	foreach($deviceTypeArray as $did) {
		$device = new Device();
		$device->init($did);
?>
				<option value="<?php echo($did); ?>"><?php echo($device->getData("name")); ?></option>
<?php
	}
?>


			</select><br />
			<strong>Authentication (JSON):</strong><br />
			<textarea name="interface" rows="5" cols="25"><?php echo($valArray["interface"]); ?></textarea><br />
			<input type="submit" value="Save" />
		</form>
	</body>
</html>