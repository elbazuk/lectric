<?php

	/**
	* set up the system parameter via config
	*/
		if (file_exists(__DIR__.'/config.php')) {
			require(__DIR__.'/config.php');
		} else {
			die('<p>Config file not found at <doc_root>/engine/config.php</p>');
		}

	/*
	* controller instantiate
	*/
		$lecController = new \Lectric\controller($lecDBH); 
