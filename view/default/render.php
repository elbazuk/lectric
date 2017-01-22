<?php

	/*
	* load up webpage based on PAGE_URL 
	*/
		$this->page = $this->loadPage();
		
	/*
	* Limit view to only index <-REMOVE THIS CALL IF COPYING TO DEVELOP WITH
	*/
		if ($this->_pageUrl !== 'index' || $this->_URLdirectory !== 'root'){
			(new \Lectric\controlAction('view', '/'))->performAction();
		}

	/*
	* Include each template part from directory. Add switch here to facilitate other directories. 
	*/
		include(DOC_ROOT.'/view/'.$this->_fileDirectory.'/template/common/header.php');
		include(DOC_ROOT.'/view/'.$this->_fileDirectory.'/template/common/content.php');
		include(DOC_ROOT.'/view/'.$this->_fileDirectory.'/template/common/footer.php'); 
