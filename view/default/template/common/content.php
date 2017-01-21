<?php

	/*
	* Include the webpage file if exists
	*/
		if (file_exists(DOC_ROOT.'/view/default/template/views/'.$this->page['webpages']['url'].'.php')) {
			include(DOC_ROOT.'/view/default/template/views/'.$this->page['webpages']['url'].'.php');
		} 