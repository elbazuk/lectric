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

	/*
	* controller instantiate
	*/
		$lecController = new \Lectric\controller($lecDBH, URL_NODES); 
