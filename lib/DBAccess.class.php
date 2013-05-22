<?php
	class DBAccess {
		var $conn;
		var $errorArray;
		var $resArray;
		var $numRows;

		function DBAccess() {
			$this->conn = null;
			$this->errorArray = array();
			$thi->numRows = 0;
			if(!$this->getConnection()) {
				trigger_error("Unable to connect to database", E_USER_WARNING);
				$this->errorArray["DBAccess"] = "Unable to connect to database";
			}
		}

		//Returns a connection to the default database.
		function getConnection() {
			if($this->conn = mysql_connect(DB_HOST, DB_USER, DB_PASS)) {
				mysql_select_db(DB_NAME);
				return true;
			}
			trigger_error("Unable to connect to database: ".mysql_error(), E_USER_WARNING);
			return false;
		}

		function executeQuery($sql) {
			//First, reset the array.
			$this->resArray = array();

			//If we have no valid DB connection, we can't continue
			if(!is_null($this->conn)) {
				if($res = mysql_query($sql)) {
					//If this is an insert operation, let's get the last insert ID
					if(stristr($sql, 'insert')) {
						$this->lastId = mysql_insert_id();
					} else {
						$this->lastId = 0;
					}

					if(mysql_num_rows($res) > 0) {
						$this->numRows = mysql_num_rows($res);
						while($row = mysql_fetch_array($res)) {
							array_push($this->resArray, $row);
						}
					} else {
						$this->numRows = 0;
					}
					return true;
				} else {
					trigger_error("Query failed: ".mysql_error()."; SQL: ".$sql, E_USER_WARNING);
					$this->errorArray["executeQuery"] = "Query Execution Failed!";
				}
			}
			return false;
		}

		function getResultArray() {
			return $this->resArray;
		}

		function getErrorArray() {
			return $this->errorArray;
		}

		function getNumRows() {
			return $this->numRows;
		}

		function getInsertId() {
			return $this->lastId;
		}
	}
?>