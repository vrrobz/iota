<?php
	//Config variables here
	define("APP_ROOT", "/var/www/gluecon/iota");
	set_include_path(get_include_path() . PATH_SEPARATOR . APP_ROOT.'/lib');
	
	define("DB_HOST", "localhost");
	define("DB_NAME", "iota");
	define("DB_USER", "iota");
	define("DB_PASS", "iota123");
	
	//The public-facing root for API endpoints.
	define("API_ROOT", '/iota/api');
	define("API_SCHEMA", APP_ROOT.'/data/api-schema.json');
?>