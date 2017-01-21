<?php

	/* 
	* Check version 
	*/
		if (phpversion() != '7.1.0'){
			echo 'This framework only supports PHP 7.1';
			exit;
		}
		
	
	/*
	* naive performance metrics
	*/
		define('LEC_RUSTART', getrusage());
		define('LEC_TIME_START',  microtime(true));
		
		
	/*
	* naive performance function
	*/
		function lec_rutime($ru, $rus, $index) {
			return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
			 -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
		}
		
	
	/*
	* set up directory root
	*/
		define('DOC_ROOT',dirname(dirname(__DIR__)));
	
	
	/**
	* grab the dbatase connection, doc root and definition list
	*/
		if (file_exists(DOC_ROOT.'/engine/plugin/core_config.php')) {
			require(DOC_ROOT.'/engine/plugin/core_config.php');
		} else {
			echo 'core config file not found';
			exit;
		}
		
		
	/*
	* set up display warnings for debug
	*/
		if (!defined('DEBUG')){
			define ('DEBUG', true);
		}
		
		if (DEBUG){
			error_reporting(E_ALL);
			ini_set('display_errors', '1');
		}
	
		
	/*
	* define base URL constants and PATHS
	*/
		if (isset($_SERVER['REQUEST_URI'])){
			define ('URL_REQUEST', $_SERVER['REQUEST_URI']);
		} else {
			define ('URL_REQUEST', '/');
		}

		$lecNodes = explode('/', trim(URL_REQUEST, '/'));
		$lecNodes = array_filter( $lecNodes, function($value) { return $value !== ''; });
		define('URL_NODES', $lecNodes);
		unset($lecNodes);
		
		
	/*
	* define view
	*/
		if (!isset(URL_NODES[0])){
			define('VIEW', true);
		} else {
			define('VIEW', (URL_NODES[0] !== 'do') ? true : false);
		}
	
	/*
	* define autoloader for lectric classes
	*/
		function lec_autoload($className) {
			
			$classnameBits = explode('\\', $className);
			
			//core first
			if (file_exists(DOC_ROOT.'/library/'. $classnameBits[0] .'/'. $classnameBits[1] .'.class.php')){
				include_once(DOC_ROOT.'/library/'. $classnameBits[0] .'/'. $classnameBits[1] .'.class.php');
			}
			
			//or project specific
			
			elseif (file_exists(DOC_ROOT.'/library/plugin/'. $classnameBits[0] .'/'. $classnameBits[1] .'.class.php')){
				include_once(DOC_ROOT.'/library/plugin/'. $classnameBits[0] .'/'. $classnameBits[1] .'.class.php');
			}
			
			//not found
			else {
				if (DEBUG){
					echo DOC_ROOT.'/library/'. $classnameBits[0] .'/'. $classnameBits[1] .'.class.php'.PHP_EOL;
					var_dump($classnameBits).PHP_EOL;;
					echo $className;
				}
			}

		}
		spl_autoload_register('lec_autoload');
		
	
	/*
	* get the database connection from core_config
	*/
		try { 
			$lecDBH = new \PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.'', DB_USER, DB_PASSWORD); 
			$lecDBH->setAttribute(\PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$lecDBH->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, FALSE);
			$lecDBH->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, false);
			//return database data type instead of all strings
			$lecDBH->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		}  
		catch(PDOException $e) {  
			echo $e->getMessage(); exit;
		}  
		
	/* 
	* SESSION 
	*/
		if (!defined('SESSION_IGNORES')){
			define('SESSION_IGNORES', []);
		}
	
		if ((VIEW === true) || ( !in_array(trim(URL_REQUEST,'/'), SESSION_IGNORES) )){
			if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}
		}
		
	
	/* 
	* String function settings 
	*/
		mb_internal_encoding('UTF-8');// Tell PHP that we're using UTF-8 strings until the end of the script use mb_ for string functions...
		mb_http_output('UTF-8'); // Tell PHP that we'll be outputting UTF-8 to the browser 
		
		
