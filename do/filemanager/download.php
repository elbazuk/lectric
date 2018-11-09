<?php

$this->adminUser = new \LecAdmin\adminUser($this->DBH);

if ($this->adminUser->loggedIn()){
	
	if (isset($_POST['file'])){
		header('Content-Disposition: attachment; filename="'.$_POST['filename'].'"');
		readfile($_POST['file']);
	}

	if (isset($_GET['file'])){
		$urlsBits = explode('/',$_GET['file']);
		$filename = end($urlsBits);
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		readfile(DOC_ROOT.$_GET['file']);
	}
}
