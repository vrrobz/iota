<?php
	//require_once($CODE_PATH."ChangeLog.class.php");
	require_once("DBAccess.class.php");

	/**
		class BaseClass

		BaseClass is the (guess!) base class for all of the DB-accessing classes in the system. It basically handles all of the functions common to each of these - populating the dataArrays, pulling data from the system, logging, etc. This allows the higher-level classes to focus on their purpose in life and little else.
	*/
	class BaseClass {
		var $dataArray;
		var $itemId;
		var $db;
		var $tableName;
		var $idField;
		var $validate;
		var $errorArray;

		function BaseClass() {
			//Set up the DB conn
			$this->db = new DBAccess;
			$this->errorArray = array();
		}

		function writeChange($userId, $communityId, $comments) {
			//Log this change in the ChangeLog
		}


		/**
			init($input)

			Initializes the dataArrays and fleshes out the object. Returns true if successful, otherwise returns false.

			$input: Can be either a numeric ID or a data array containing the same keys as in $this->dataArray.
		*/
		function init($input) {
			if(is_array($input)) {
				return $this->setDataArray($input);
			} else if(is_numeric($input)) {
				$this->itemId = intval($input);
				return $this->populateDataArray($this->tableName, $this->idField);
			} else {
				return false;
			}
		}

		/**
			populateDataArray($tableName, $idField)

			Generic function that pulls data from the data source for this child object and populates the dataArray. Returns true if successful, otherwise returns false.

			$tableName: The name of the table where this should pull from

			$idField: The name of the field that holds the ID.

		*/
		function populateDataArray($tableName, $idField) {
			if(intval($this->itemId) <= 0) {
				return false;
			}

			$sql = "Select * from ".mysql_escape_string($tableName)." where ".mysql_escape_string($idField)." = ".intval($this->itemId);

			if($this->db->executeQuery($sql)) {
				//Sanity check - this should never be true
				if($this->db->getNumRows() > 1) {
					trigger_error("::DATABASE INTEGRITY ERROR:: - ID ".$this->itemId." for table ".$tableName." is NOT unique!", E_USER_ERROR);
					return false;
				}

				if($this->db->getNumRows() == 0) {
					trigger_error("Could not find ID ".$this->itemId." for table ".$tableName.".", E_USER_NOTICE);
					return false;
				}

				//Populate the data array using the init() function.
				$tmpArray = $this->db->getResultArray();
				return $this->init($tmpArray[0]);
			} else {
				trigger_error("::DATABASE ERROR:: - Unable to execute query (".$sql.")", E_USER_ERROR);
				return false;
			}
		}

		/**
			loadData()

			Helper function that interfaces with populateDataArray. Because I'm roo lazy to retrain my habits but not too lazy to create extra functions.
		*/
		function loadData() {
			if(!isset($this->tableName) || !isset($this->idField)) {
				trigger_error("Table name and id field names not defined in child class.", E_USER_ERROR);
				return false;
			}
			return populateDataArray($this->tableName, $this->idField);
		}

		/**
			setData($key, $value)

			Generic function that sets a specific key in the DataArray. Returns true if key is found and value is successfully set, otherwise returns false.

			$key: String identifying the name of the key.

			$value: Value to assign to the key.
		*/
		function setData($key, $value) {
			if(in_array($key, $this->dataArray)) {
				$this->dataArray[$key] = $value;
				return true;
			} else {
				return false;
			}
		}

		/**
			getData($key)

			Generic function that gets the value from the dataArray identified by the given key. Returns the given value if found. If not found, returns false. Note that this could be confusing if the value you're seeking is a boolean value. Since the dataArray represents data typically stored in a database, you should avoid using boolean values in the data.

			$key: String identifying the name of the key.
		*/
		function getData($key) {
			if(isset($this->dataArray[$key])) {
				return $this->dataArray[$key];
			} else {
				return false;
			}
		}

		/**
			getDataArray()

			Generic getter that returns the Data Array as an array

		*/
		function getDataArray() {
			return $this->dataArray;
		}

		/**
			setDataArray($data)

			Set the data array with the provided data.

			$data: array of data matching that specified in the data array
		*/
		function setDataArray($data) {
			foreach(array_keys($this->dataArray) as $key) {
				if(isset($data[$key])) {
					$this->dataArray[$key] = $data[$key];
				}
			}
			return true;
		}

		/**
			getItemId()

			Returns this Item's ID.
		*/
		function getItemId() {
			return $this->itemId;
		}

		/**
			prepareUpdate($skipArray)

			Turns the dataArray into a string suitable for insertion or updation in a SQL statement. You heard me, I said updation.

			$skipArray: And array of strings matching keys in the dataArray that will be skipped if found in this array.
		*/
		function prepareUpdate($skipArray = null) {
			$retSql = '';
			$counter = 0;
			$insVars = '';
			$insVals = '';
			if(isset($this->itemId) && (intval($this->itemId) > 0)) {
				//This is an update
				$counter = 0;
				foreach($this->dataArray as $key=>$value) {
					if(!is_null($skipArray)) {
						if(in_array($key, $skipArray)) {
							continue;
						}
					}
					if($counter > 0) {
						$retSql .= ", ".$key." = '".mysql_escape_string($value)."'";
					} else {
						$retSql .= $key." = '".mysql_escape_string($value)."'";
					}
					$counter++;
				}
			} else {
				//This is an insert
				$counter = 0;
				foreach($this->dataArray as $key=>$value) {
					if(!is_null($skipArray)) {
						if(in_array($key, $skipArray)) {
							continue;
						}
					}
					if($counter > 0) {
						$insVars .= ", ".$key;
						$insVals .= ", '".mysql_escape_string($value)."'";
					} else {
						$insVars .= $key;
						$insVals .= "'".mysql_escape_string($value)."'";
					}
					$counter++;
				}
				$retSql = "(".$insVars.") Values (".$insVals.")";
			}
			return $retSql;
		}

		/**
			update()

			There's probably a psychological classification for the type of OCD that requires this degree of abstraction, and I apparently have it. If the child class in question does not have the tableName and idFields for their analog in the DB (or whatever datasource we're using) then this sucker simply won;t work and will return false.

			Who knows, someday INI files may become de regeur instead of SQL DBs. I'm being prepared.
		*/

		function update() {
			//Update the release data in the database.
			if(!isset($this->tableName) || !isset($this->idField)) {
				trigger_error("Table and id field names not defined in child class.", E_USER_ERROR);
				return false;
			}

			if(!($this->validateData() === true)) {
				return false;
			}

			$valString = $this->prepareUpdate();

			if(intval($this->itemId) > 0) {
				$sql = "Update ".mysql_escape_string($this->tableName)." set ".$valString." where ".mysql_escape_string($this->idField)."=".intval($this->itemId);
			} else {
				$sql = "Insert into ".$this->tableName." ".$valString;
			}
			if(!$this->db->executeQuery($sql)) {
				return false;
			}
			if($this->itemId <= 0) {
				$this->itemId = $this->db->getInsertId();
			}
			return true;
		}

		/**
			validateData()

			Validates the data in the array according to whatever the class defines. This is sort of a future thing, so right now it just returns true. Should return true if all data is OK, otherwise it should return an array of error strings.
		*/
		function validateData() {
			trigger_error("Calling the validateData() function from BaseClass", E_USER_NOTICE);
			return true;
		}
		
		/**
			setError(field, message)
			
			Sets an error for the given field, adds it to the error array. 
		*/
		function setError($field, $message) {
			if(isset($this->errorArray[$field])) {
				$this->errorArray[$field] += $message."\n";
			} else {
				$this->errorArray[$field] = $message."\n";
			}
		}
		
		/** 
			getErrors()
			
			Returns an associative arry containing the errors.
		*/
		function getErrors() {
			return $this->errorArray;
		}

		/**
			delete()

			Wipes the data represented by this object off the map by removing it from the DB.
		*/
		function delete() {
			$sql = "Delete from ".mysql_escape_string($this->tableName)." where ".mysql_escape_string($this->idField)." = ".intval($this->getItemId());
			return $this->executeQuery($sql);
		}
	}
?>