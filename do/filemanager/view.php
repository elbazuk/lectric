<?php

$this->adminUser = new \LecAdmin\adminUser($this->DBH);

if($this->adminUser->loggedIn()){

	try{

		$filemanager = new \LecAdmin\filemanager('/uploads/');		
		$filemanager->launchFileManagerHTML();
		
	} catch (\Exception $e){
		if (DEBUG){
			echo 'Failed to load filemanager: '.$e->getMessage();
		}
	}
	
}
