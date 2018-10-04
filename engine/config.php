<?php

	/* 
	* String function settings 
	*/
		mb_internal_encoding('UTF-8');	// Tell PHP that we're using UTF-8 strings until the end of the script use mb_ for string functions...
		mb_http_output('UTF-8'); 		// Tell PHP that we'll be outputting UTF-8 to the browser 


	/*
	* naive performance metrics
	*/
		define('LEC_RUSTART', getrusage());
		define('LEC_TIME_START',  microtime(true));
		
		
	/*
	* naive performance function
	*/
		function lec_rutime($ru, $rus, $index) {
			return ($ru['ru_'.$index.'.tv_sec']*1000 + intval($ru['ru_'.$index.'.tv_usec']/1000))
			 -  ($rus['ru_'.$index.'.tv_sec']*1000 + intval($rus['ru_'.$index.'.tv_usec']/1000));
		}
		
		
	/* 
	* Check version 
	*/
		if (PHP_MAJOR_VERSION  < 7){
			echo '<p style="text-align:center;">This framework only supports PHP 7.1 ></p>';
		}
		
	
	/*
	* set up directory root
	*/
		define('DOC_ROOT',dirname(dirname(__DIR__)));
	
	
	/**
	* grab the dbatase connection, doc root and definition list
	*/
		if (file_exists(DOC_ROOT.'/engine/app_config.php')) {
			require(DOC_ROOT.'/engine/app_config.php');
		}
		
		
	/*
	* set up display warnings for debug, defaults to true
	* default to error reporting on, unless core_config debug definition overrides in /engine/plugin/core_config
	*/
		if (!defined('DEBUG')){
			define ('DEBUG', true);
		}
		
		if (DEBUG){
			error_reporting(E_ALL);
			ini_set('display_errors', '1');
		}
		
		
	/*
	* Default constants - override these in /engine/plugin/core_config
	*/
		if (!defined('SITE_NAME')){ define('SITE_NAME','Lectric'); }									//for ,eta title
		if (!defined('SITE_LINK')){ define('SITE_LINK',$_SERVER['SERVER_NAME']); } 						//url, defaults to nothing
		if (!defined('SITE_DESCRIPTION')){ define('SITE_DESCRIPTION','Lectric Default Installation'); }	//for meta desc
		if (!defined('DEFAULT_DIRECTORY')){ define('DEFAULT_DIRECTORY','default'); }					//for view directory selection
		if (!defined('SESSION_IGNORES')){ define('SESSION_IGNORES', []); }								//if scripts need to set own headers, they can be ignored for seesion start further down.
		
		
	/*
	* define base URL NODES, URL_REQUEST AND REQUEST_METHOD
	*/
		//base request
		define ('URL_REQUEST', $_SERVER['REQUEST_URI']);
		define('URL_PATH', parse_url(URL_REQUEST, PHP_URL_PATH));
		define ('REQUEST_METHOD', $_SERVER['REQUEST_METHOD']);
		
		//re-register egt
		define('REQUEST_QUERY_STRING', parse_url(URL_REQUEST, PHP_URL_QUERY));
		mb_parse_str(REQUEST_QUERY_STRING, $_GET);

		//set up nodes
		$lecNodes = explode('/', trim(URL_PATH, '/')); //trim important for URL_NODES index numbers
		$lecNodes = array_filter( $lecNodes, function($value) { return $value !== ''; });
		define('URL_NODES', $lecNodes);
	
		
	
	/*
	* define autoloader for lectric classes
	*/
		function lec_autoload($className) {
			
			$classnameBits = explode('\\', $className);
			
			//core first (Lectric namespace)
				if (file_exists(DOC_ROOT.'/library/'. $classnameBits[0] .'/'. $classnameBits[1] .'.class.php')){
					include_once(DOC_ROOT.'/library/'. $classnameBits[0] .'/'. $classnameBits[1] .'.class.php');
				}

		}
		spl_autoload_register('lec_autoload');
		
	/**
	* grab the Composer vendor autloader if it exists
	*/
		if (file_exists(DOC_ROOT.'/vendor/autoload.php')) {
			require(DOC_ROOT.'/vendor/autoload.php');
		}
		
	
	/*
	* get the database connection from core_config, or set to null (if DB not needed)
	*/
		if (defined('DB_HOST') && defined('DB_NAME') && defined('DB_USER') && defined('DB_PASSWORD')){
			try { 
				$lecDBH = new \PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4', DB_USER, DB_PASSWORD);
				$lecDBH->setAttribute(\PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$lecDBH->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, FALSE);
				$lecDBH->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, false);
				//return database data type instead of all strings
				$lecDBH->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
			}  
			catch(PDOException $e) {  
				echo $e->getMessage(); exit;
			} 
		} else {
			$lecDBH = null;
		}
		
		
	/* 
	* SESSION 
	*/
		if (( !in_array(trim(URL_REQUEST,'/'), SESSION_IGNORES) )){
			if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}
		}
		
