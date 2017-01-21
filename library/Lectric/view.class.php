<?php
namespace Lectric;
/**
* Controller routing class for do/view
*
* The controller determines whether do and action or vieww something
*
* @package    RWS Framework
* @author     Elliott Barratt
* @copyright  Elliott Barratt, all rights reserved.
* @license    As license.txt in root
*
*/ 
class view extends SQLQueryPDO {
	
	private $_pages_table = '`webpages`';
    private $_directory_table = '`directories`';
	
	private $page;
	private $_directory;
	private $_pageUrl;
	private $_iconSet = 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';
	
	/**
     * construct contains the main do or view logic
     *
     * @param array URL_NODES array of URL nodes
	 *
     */
	function __construct($DBH){
		
		parent::__construct($DBH);
		 
		//set members
		if (!isset(URL_NODES[0])){
			
			$this->_directory = 'root';
			$this->_pageUrl = 'index';
			
		} else {
			
			if (count(URL_NODES) === 1){
				$this->_directory = 'root';
				$this->_pageUrl = URL_NODES[0];
			} else {
				$this->_directory = URL_NODES[1];
				$this->_pageUrl = URL_NODES[0];
			}
			
		}
		
		//render!
		$this->render();
		
	}
	
    /**
     * main render call
     * 
     * 
     * @return void
     */
    public function render(): void{
		
		if (is_dir(DOC_ROOT.'/view/'.$this->_directory)){

			if (file_exists(DOC_ROOT.'/view/'.$this->_directory.'/render.php')) {
				require(DOC_ROOT.'/view/'.$this->_directory.'/render.php');
			} else {
				echo 'render file not in view directory: '.$this->_directory;
				exit;
			}
			
		} else {
			
			if (!defined('DEFAULT_DIRECTORY')){
				$default = 'default';
			} else {
				$default = DEFAULT_DIRECTORY;
			}
			
			if (file_exists(DOC_ROOT.'/view/'.$default.'/render.php')) {
				require(DOC_ROOT.'/view/'.$default.'/render.php');
			} else {
				echo 'render file not in view directory: '.$default;
				exit;
			}
			
		}
		
		return;

    }
	
    /**
     * Load up the webpage row from database
     * 
     * @param string $directorySelector 
     * @param string $pageSelector 
     * 
     * @return array
     */
	public function loadPage(string $directorySelector, string $pageSelector): ?array{
		
		try{
			//strict to catch a directory that doesn't exist.
			$this->setWhereFields(array('name' => $directorySelector, 'live' => 1));
			$this->setWhereOps('==');
			$result_dir = $this->selStrict($this->_directory_table,'SINGLE', 'STRICT');
			
			$this->setWhereFields(array('directory' => $directorySelector,'url' => $pageSelector, 'live' => 1));
			$this->setWhereOps('===');
			$result = $this->selectStrict($this->_pages_table, 'SINGLE', 'STRICT');
		} catch (SQLException $e){
			header("HTTP/1.0 404 Not Found");
			$this->setWhereFields(array('url' => 'error'));
			$this->setWhereOps('=');
			return $result = $this->selectStrict($this->_pages_table, 'SINGLE', 'NOT_STRICT');
		}
		
		return $result;
		
	}
	
}
