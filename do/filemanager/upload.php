<?php

$this->adminUser = new \LecAdmin\adminUser($this->DBH);
$this->filemanager = new \LecAdmin\filemanager('/uploads/');

if ( isset($_POST['directory']) && $this->adminUser->loggedIn()){
	$this->filemanager->uploadFile();
}
