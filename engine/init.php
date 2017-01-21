<?php

	/**
	* set up the system parameter via config
	*/
		if (file_exists(__DIR__.'/config/config.php')) {
			require(__DIR__.'/config/config.php');
		} else {
			echo 'config file not found';
			exit;  
		}
	
	/**
	* main lectric engine file
	*/
		if (file_exists(DOC_ROOT.'/engine/lectric.php')) {
			require(DOC_ROOT.'/engine/lectric.php');
		} else { 
			echo 'lectric engine file not found';
			exit;  
		}
