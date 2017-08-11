<?php

	if (PERMISSION){

	/*
	* Include the webpage file if exists 
	*/
		if (file_exists(DOC_ROOT.'/view/'.$this->_fileDirectory.'/template/views/'.$this->page['url'].'.php')) {
			include(DOC_ROOT.'/view/'.$this->_fileDirectory.'/template/views/'.$this->page['url'].'.php');
		} 
		
		
	} else {
		?><p class="tools-alert tools-alert-yellow">You do not have permission to view this page</p><?php
	}
