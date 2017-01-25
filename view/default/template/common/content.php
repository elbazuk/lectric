<?php

	/*
	* Include the webpage file if exists 
	*/
		if (file_exists(DOC_ROOT.'/view/'.$this->_fileDirectory.'/template/views/'.$this->page['url'].'.php')) {
			include(DOC_ROOT.'/view/'.$this->_fileDirectory.'/template/views/'.$this->page['url'].'.php');
		} 
