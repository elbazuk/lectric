<?php
	//for adding code in tinymce
	if(!defined('ALLOW_CODE_IN_EDITOR')){
		define('ALLOW_CODE_IN_EDITOR', false);
	}
	
	if(ALLOW_CODE_IN_EDITOR === true){
		header('X-XSS-Protection:0');
	}

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
		if(!defined('PER_PAGE_FRONT_ADMIN')){
			define('PER_PAGE_FRONT_ADMIN', 30);
		}
		
		if (isset($_GET['page'])){
			
			if (is_numeric($_GET['page'])){
				$page = $_GET['page'];
			} else {
				$page = 1;
			}
			
		} else {
			$page = 1;
		}
		
		$start_pos = PER_PAGE_FRONT_ADMIN * ($page - 1);
		
		define('PAG_PAGE_ADMIN', $page);
		define('PAG_START_ADMIN', $start_pos);
		
	//editor stylesheets MUST HAVE LEADING COMMA
		if(!defined('EDITOR_STYLESHEETS')){
			define('EDITOR_STYLESHEETS', '');
		}


	/*
	* Include each template part from directory. Add switch here to facilitate other directories.
	*/
		include(DOC_ROOT.'/view/'.$this->_fileDirectory.'/template/common/header.php');
		include(DOC_ROOT.'/view/'.$this->_fileDirectory.'/template/common/content.php');
		include(DOC_ROOT.'/view/'.$this->_fileDirectory.'/template/common/footer.php'); 
