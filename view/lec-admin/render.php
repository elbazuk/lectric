<?php

	//get permissions
		$this->adminUser = new \LecAdmin\adminUser($this->DBH);

	/*
	* load up webpage based on PAGE_URL & other bits
	*/
		$this->page = $this->loadPage();
		$this->lecAdmin = new \LecAdmin\lecAdmin($this->DBH);
		
	//user logged in?
		if (!$this->adminUser->loggedIn() && $this->_pageUrl !== 'login'){
			(new \Lectric\controlAction('view', '/lec-admin/login/', '<span class="fa fa-times fa-fw"></span> Please login to view this page.'))->performAction();
		}
		
	//user on login page?
		if ($this->_pageUrl == 'login'){
			if (!$this->adminUser->loggedIn()){
				define ('PERMISSION',true);
			} else {
				(new \Lectric\controlAction('view', '/lec-admin/'))->performAction();
			}
		} else {
			define ('PERMISSION',$this->adminUser->getAdminPermission($this->page['permission']));
		}
		
	//page count definitions
		define('PER_PAGE_FRONT', 30);
		
		if (isset($_GET['page'])){
			
			if (is_numeric($_GET['page'])){
				$page = $_GET['page'];
			} else {
				$page = 1;
			}
			
		} else {
			$page = 1;
		}
		
		$start_pos = PER_PAGE_FRONT * ($page - 1);
		
		define('PAG_PAGE', $page);
		define('PAG_START', $start_pos);
		
	//editor stylesheets MUST HAVE LEADING COMMA
		if(DEBUG){
			define('EDITOR_STYLESHEETS', '');
		} else {
			define('EDITOR_STYLESHEETS', '');
		}

	/*
	* Include each template part from directory. Add switch here to facilitate other directories.
	*/
		include(DOC_ROOT.'/view/'.$this->_fileDirectory.'/template/common/header.php');
		include(DOC_ROOT.'/view/'.$this->_fileDirectory.'/template/common/content.php');
		include(DOC_ROOT.'/view/'.$this->_fileDirectory.'/template/common/footer.php'); 
