<?php
	require_once("BaseClass.class.php");
	
	class DeviceType extends BaseClass {
		function DeviceType() {
			parent::BaseClass();
			
			$this->dataArray = array(
				'name'	=>	'',
				'endpoint'	=>	'',
				'custom_variables'	=>	'',
				'interface'	=> '',
				'authentication_template' => ''
			);

			$this->tableName = 'DeviceTypes';
			$this->idField = 'id';
		}
		
		function validateData() {
			//primarily concerned with valid JSON at this point, so I'm just validating for that
			if(is_null(json_decode($this->dataArray["interface"]))) {
				$this->setError("interface", "Invalid JSON");
				return false;
			}
			return true;
		}
	}
?>