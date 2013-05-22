<?php
	require_once('../../config/config.php');
	require_once('DBAccess.class.php');
	require_once('DeviceType.class.php');
	
	$valArray = array();
	
	$pop = '';
	if(isset($_REQUEST["name"])) {
		//Save the form to the database, give them a confirmation
		//$pop = "Did not add device type - not implemented";
		$dt = new DeviceType();
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
?>
<html>
	<head>
		<title>Add Device Type RZ</title>
	</head>
	<body>

	<div class="message"><?php echo($pop); ?></div>

		<h1>Add a new Device Type (Adaptor)</h1>
		<form name="addDeviceType" method="POST">
			<strong>Device Type Name:</strong> <input type="text" name="name" value="<?php echo($valArray["name"]); ?>" /><br />
			<strong>Endpoint:</strong> <input type="text" name="endpoint" value="<?php echo($valArray["endpoint"]); ?>" /><br />
			<strong>API Interface (JSON):</strong><br />
			<textarea name="interface" rows="5" cols="25"><?php echo($valArray["interface"]); ?></textarea><br />
			<strong>Authentication Template (JSON):</strong><br />
			<textarea name="authentication_template" rows="5" cols="25"><?php echo($valArray["authentication_template"]); ?></textarea><br />
			<strong>Custom Variables (JSON):</strong><br />
			<textarea name="custom_variables" rows="5" cols="25"><?php echo($valArray["custom_variables"]); ?></textarea><br />
			<input type="submit" value="Save" />
		</form>
	</body>
</html>