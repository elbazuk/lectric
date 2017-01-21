<?php

	/*
	* load up webpage based on PAGE_URL
	*/
		$this->page = $this->loadPage($this->_directory, $this->_pageUrl);

	/*
	* Include each template part from directory. Add switch here to facilitate other directories. 
	*/
		include(DOC_ROOT.'/view/default/template/common/header.php');
		include(DOC_ROOT.'/view/default/template/common/content.php');
		include(DOC_ROOT.'/view/default/template/common/footer.php'); 
