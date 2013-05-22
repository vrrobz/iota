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
			//Future - validate to make sure the required stuff is there.
			if(is_null(json_decode($this->dataArray["interface"]))) {
				$this->setError("interface", "Invalid JSON");
				return false;
			}
			
			if(is_null(json_decode($this->dataArray["authentication_template"]))) {
				$this->setError("authentication_template", "Invalid JSON");
				return false;
			}
			
			if(strlen($this->dataArray["custom_variables"]) > 1) {
				if(is_null(json_decode($this->dataArray["custom_variables"]))) {
					$this->setError("custom_variables", "Invalid JSON");
					return false;
				}
			}
			
			return true;
		}
		
		//This really ought to be in a manager class/controller, but I'm coding for speed right now
		function getAllDeviceTypes() {
			$retArray = array();
			$sql = "Select id from DeviceTypes";
			$this->db->executeQuery($sql);
			$res = $this->db->getResultArray();
			foreach($res as $device) {
				array_push($retArray, $device["id"]);
			}
			return $retArray;
		}
			
		
		//Returns an array of device IDs associated with this device type.
		function getDevices() {
			$retArray = array();
			$sql = "select id from Devices where device_type = ".mysql_escape_string($this->itemId);
			$this->db->executeQuery($sql);
			$res = $this->db->getResultsArray();
			foreach($res as $device) {
				array_push($retArray, $device["id"]);
			}
			return $retArray;
		}
		
	}
?>