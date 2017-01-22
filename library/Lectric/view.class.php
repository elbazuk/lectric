<?php
namespace Lectric;

/**
* View class, URL parse and view loader
*
* The controller determines whether do and action or vieww something
*
* @package    Lectric Framework
* @author     Elliott Barratt
* @copyright  Elliott Barratt, all rights reserved.
* @license    As license.txt in root
*
*/ 
class view extends SQLQueryPDO {
	
	private $page = null;
	
	private $_URLdirectory = '';
	private $_directory = '';
	private $_pageUrl = '';
	
	private $_iconSet = 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';
	private $_imgLocalDir = '';
	private $_cssLocalDir = '';
	private $_jsLocalDir = '';
	
	private $_cssLibDir = '/library/css';
	private $_jsLibDir = '/library/js';
	
	/**
     * construct to parse URL for your viewing pleasure
     *
     * @param object $DBH DBH handler for SQLQueryPDO
	 *
     */
	function __construct($DBH)
	{
		
		/*
		* seeing as we're using the construct for other stuff, we need to pass the DBH through as normal as in parent
		* 
		*/
			parent::__construct($DBH);
		
		
		 
		/*
		* parse URL into file directories, url directories and page urls
		* 
		*/
		
			$urlNodes = URL_NODES; 	//needed for use of end()
			
			if (!isset($urlNodes[0])){
				
				$this->_URLdirectory = 'root';						//
				$this->_fileDirectory = DEFAULT_DIRECTORY;			//
				$this->_pageUrl = 'index';							//
				
			} else {
				
				if (count($urlNodes) === 1){
					
					/*
					* first find physical actual derectories - they will always be preffered over default directory.
					* e.g. if you have an "admin" interface seperate to front end, mount in a directory called /view/admin/ 
					*/
					if (is_dir(DOC_ROOT.'/view/'.$urlNodes[0])){
						
						$this->_URLdirectory = 'root';				//
						$this->_fileDirectory = $urlNodes[0];		//
						$this->_pageUrl = 'index';					//
						
					} else {
						$this->_URLdirectory = 'root';				//
						$this->_fileDirectory = DEFAULT_DIRECTORY;	//
						$this->_pageUrl = $urlNodes[0];				//
					}
					
				} else {
					
					if (is_dir(DOC_ROOT.'/view/'.$urlNodes[0])){
						
						if (count($urlNodes) === 2){
							$this->_URLdirectory = 'root';			//
							$this->_fileDirectory = $urlNodes[0];	//
							$this->_pageUrl = end($urlNodes);
						} else {
							$this->_URLdirectory = $urlNodes[1]; 	//
							$this->_fileDirectory = $urlNodes[0];	//
							$this->_pageUrl = end($urlNodes);
						}
						
					} else {
					
						$this->_URLdirectory = $urlNodes[0];		//
						$this->_fileDirectory = DEFAULT_DIRECTORY;	//
						$this->_pageUrl = end($urlNodes);		//
					}
					
				}			
				
			}
			
		/*
		* set up some helper properties
		* 
		*/
		$this->_imgLocalDir = '/view/'.$this->_fileDirectory.'/img';
		$this->_cssLocalDir = '/view/'.$this->_fileDirectory.'/css';
		$this->_jsLocalDir = '/view/'.$this->_fileDirectory.'/js';
			
		$this->render();
		
	}
	
    /**
     * main render call
     * 
     * 
     * @return void
     */
    public function render(): void
	{
		
		/*
		* first find physical actual derectories - they will always be preffered over default directory.
		* e.g. if you have an "admin" interface seperate to front end, mount in a directory called /view/admin/ 
		*/
		if (is_dir(DOC_ROOT.'/view/'.$this->_fileDirectory)){

			if (file_exists(DOC_ROOT.'/view/'.$this->_fileDirectory.'/render.php')) {
				require(DOC_ROOT.'/view/'.$this->_fileDirectory.'/render.php');
			} else {
				echo 'render file not in view directory: '.$this->_fileDirectory;
				exit;
			}		
		} 
		
		/*
		* if a physical directory does not exist, attempt to rout to DEFAULT_DIRECTORY instead
		*/
		else {
			
			if (file_exists(DOC_ROOT.'/view/'.DEFAULT_DIRECTORY.'/render.php')) {
				require(DOC_ROOT.'/view/'.DEFAULT_DIRECTORY.'/render.php');
			} else {
				echo 'render file not in view directory: '.DEFAULT_DIRECTORY;
				exit;
			}
			
		}
		
		return;

    }
	
    /**
     * Load up the webpage row from database
     * 
     * @return array
     */
	public function loadPage(): ?array
	{
		
		try{
			
			//strict to catch a directory that doesn't exist.
			$this->setWhereFields(array('name' => $this->_URLdirectory, 'live' => 1));
			$this->setWhereOps('==');
			$result_dir = $this->selStrict($this->_fileDirectory.'_directories','SINGLE', 'STRICT', 'NOT_TABLED');
			
			//get webpage via directory id
			$this->setWhereFields(array('directory' => $result_dir['id'],'url' => $this->_pageUrl, 'live' => 1));
			$this->setWhereOps('===');
			$result = $this->selStrict($this->_fileDirectory.'_views', 'SINGLE', 'STRICT', 'NOT_TABLED');
			
		} catch (SQLException $e){
			//chuck up the 404, watch for headers!
			header("HTTP/1.0 404 Not Found");
			$this->setWhereFields(array('url' => 'error'));
			$this->setWhereOps('=');
			return $result = $this->selStrict($this->_fileDirectory.'_views', 'SINGLE', 'NOT_TABLED');
		}
		
		return $result;
		
	}
	
}
