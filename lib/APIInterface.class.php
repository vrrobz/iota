<?php
/*
	For this first rev, this class will read in a routing table defined in JSON (because I apparently *LOVE* JSON, even though I kind of hate it) and use that to determine how to route and respond to API Calls.
	
	
Ideally, this should be instantiated as a singleton. Again, being lazy for the sake of expedience - this is, after all, a hackathon. HACK HACK HACK!
*/

	class APIInterface {
		var $routes;
		var $request;
		var $controller;
		var $action;
		var $idArray;
		
		function APIInterface() {
			//Load the schema
			$this->routes = $this->getAPIStructure();
			$this->controller = '';
			$this->action = '';
			$this->idArray = array();
		}
		
		function init($request) {
			$this->request = $request;
		}
		
		function routeByURI($uri) {
			//Let's first generalize the given uri, extracting IDs, etc.
			str_replace(API_ROOT, '', $uri);
			
			$pathArray = explode('/', $uri);
			//Remove trailing slash
			if($pathArray[count($pathArray) - 1] == '') {
				array_pop($pathArray);
			}

			$idCount = 2;

			//Extract the numeric IDs, then generalise them for a search
			//FIXME: This means we can only accept numeric IDs. I don;t like this. HACK HACK HACK
			for($n = 0; $n < count($pathArray); $n++) {
				if(is_numeric($pathArray[$n])) {
					if($idCount == 2) {
						$this->request["id"] = $pathArray[$n];
					} else {
						$this->request["id".$idCount] = $pathArray[$n];
					}
					$idCount++;
					
					$pathArray[$n] = ':id';
				}
			}
					
			
			$modURI = implode('/', $pathArray);
			
			//This is wildly inefficient, but HACK HACK HACK
			foreach($this->routes["endpoints"] as $endpoint) {
				if(($endpoint["uri"] == $modURI) && (strtolower($_SERVER['REQUEST_METHOD']) == strtolower($endpoint["method"]))) {
					//Then pass this on to the right controller
					$this->controller = $endpoint["controller"];
					$this->action = $endpoint["action"];
					return true;
				}
				
			}
			return false;
		}


		function getAPIStructure() {
			if(!file_exists(API_SCHEMA)) {
				trigger_error("Could not load schema JSON file ".API_SCHEMA, E_USER_ERROR);
				return false;
			}
			
			$f = fopen(API_SCHEMA, 'r');
			
			$routes = json_decode(fread($f, filesize(API_SCHEMA)), true);
			if(is_null($routes)) {
				trigger_error("JSON parse error with ".API_SCHEMA, E_USER_ERROR);
				return false;
			}
			return $routes;
		}
		
		function render() {
			//Handle the logic of loading the controller and calling the action with the request parameters.
			//echo("I would be calling the ".$this->controller." controller and the ".$this->action." action with ".count($this->idArray)." IDs");
			if(!file_exists(APP_ROOT.'/controllers/'.$this->controller.'.class.php')) {
				header("HTTP/1.0 500 Server Error");
				echo("Bad controller");
				return false;
			}
			//echo("I would be calling the ".$this->controller." controller and the ".$this->action." action with ".count($this->idArray)." IDs");
			require_once(APP_ROOT.'/controllers/'.$this->controller.'.class.php');
			$controller = new $this->controller;
			$controller->{$this->action}($this->request);
			
		}
	}
?>