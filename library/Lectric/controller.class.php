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
class controller {
	
    /**
     * construct contains the main do or view logic
     *
     * @param array URL_NODES array of URL nodes 
	 *
     */
		function __construct($DBH)
		{
			
			if (VIEW){
				//VIEW!
				$lecView = new view($DBH);
			} else {
				//DO!
				
				if (count(URL_NODES) !== 4){
					if (DEBUG){
						echo 'wrong do node count for response.';
					}
					exit;
				}
				
				/*
				* Response type do
				*/
				if (URL_NODES[1] === 'response'){
					
					
					
					if (file_exists(DOC_ROOT.'/do/'.URL_NODES[2].'/'.URL_NODES[3].'.php')) {
						require(DOC_ROOT.'/do/'.URL_NODES[2].'/'.URL_NODES[3].'.php');
					} else {
						if (DEBUG){
							echo 'do file not in do directory: '.URL_NODES[2];
						}
						exit;
					}
					
				} 
				
				/*
				* Action type do (no response, may generate another view)
				*/
				else {
					
					try{
						if (class_exists('\\'.URL_NODES[1].'\\'.URL_NODES[2])){
							
							$doClass = '\\'.URL_NODES[1].'\\'.URL_NODES[2];
							$doer = new $doClass($DBH);
							
							if (method_exists($doer, 'do_'.URL_NODES[3])){
							
								$action = $doer->{'do_'.URL_NODES[3]}($_POST, $_GET);
								$action->performAction();
							
							} else {
								throw new \Exception('Class '.htmlentities('\\'.URL_NODES[1].'\\'.URL_NODES[2]).' doesn\'t contain method '.'do_'.URL_NODES[3].' in do action.');
							}
							
						} else {
							throw new \Exception('Class '.htmlentities('\\'.URL_NODES[1].'\\'.URL_NODES[2]).' doesn\'t exist for do action.');
						}
						
					} catch (\Exception $e){
						if (DEBUG){
							echo $e->getMessage();
							exit;
						}
					}
					
				}
				
			}
			
		}
	
    /**
     * Set session messages
     * 
     * @param string $msg 
     * 
     * @return void
     */
		public static function setSessionMessage(string $msg): void
		{
			
			if (!isset($_SESSION['lec_msg'])){
				$_SESSION['lec_msg'] = [];
			}
			
			if (trim($msg) !== ''){
				$_SESSION['lec_msg'][] = $msg;
			}
			
			return;
			
		}
	
    /**
     * Get session messages
     * 
     * @param string $msg 
     * 
     * @return void
     */
		public static function getSessionMessages(): ?array
		{
			
			if (isset($_SESSION['lec_msg'])){
				if (empty($_SESSION['lec_msg'])){
					return null;
				} else {
					return $_SESSION['lec_msg'];
				}
			} else {
				return null;
			}
			
		}
	
    /**
     * Unset Session messages if present
     * @return <type>
     */
		public static function clearSessionMessages(): void
		{
			
			if (isset($_SESSION['lec_msg'])){
				unset($_SESSION['lec_msg']);
			}
			return;
		}
	
}
