<?php
	require_once("BaseClass.class.php");
	require_once("DeviceType.class.php");
	
	class Device extends BaseClass {
		function Device() {
			parent::BaseClass();
			
/*
	id		int		NOT NULL PRIMARY KEY AUTO_INCREMENT,
name	varchar(255)	NOT NULL,
device_type	int	NOT NULL,
authentication	text NOT NULL
*/
			
			$this->dataArray = array(
				'name'	=>	'',
				'device_type'	=>	'',
				'authentication'	=>	''
			);

			$this->tableName = 'Devices';
			$this->idField = 'id';
		}
		
		function validateData() {
			//primarily concerned with valid JSON at this point, so I'm just validating for that
			//Future - validate to make sure the required stuff is there.
			if(is_null(json_decode($this->dataArray["authentication"]))) {
				$this->setError("interface", "Invalid JSON");
				return false;
			}
			
			
			return true;
		}
	}
?>