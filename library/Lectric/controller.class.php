<?php
namespace Lectric;

/**
* Controller routing class for do/view
*
* The controller determines whether do and action or vieww something
*
* @package    Lectric Framework
* @author     Elliott Barratt
* @copyright  Elliott Barratt, all rights reserved.
*
*/ 
class controller {
	
    /**
     * construct contains the main do or view logic
     *
     * @param array URL_NODES array of URL nodes 
	 *
     */
		function __construct(&$DBH = null)
		{
			
			/*
			* define if view or not 
			*/
				define('VIEW', (URL_PATH === '/') ? true : ((URL_NODES[0] !== 'do') ? true : false) );		//do or view?
			
			if (VIEW){
				$lecView = new view($DBH);
			} else {
				
				try{
					
					/*
					* Response type do
					*/
						if (URL_NODES[1] === 'response'){
							$lecDo = new doResponse(URL_NODES, $DBH);
						} 
					
					/*
					* Action type do (no response, may generate another view or do something else...?)
					*/
						elseif(URL_NODES[1] === 'action'){
							$lecDo = new doAction(URL_NODES, $DBH);
						} else {
							throw new \Exception('neither response or action keywords defined in url after /do/');
						}
				
				} catch (\Exception $e){
					if (DEBUG){
						echo $e->getMessage();
					}
					//all errors above cause an exit;
					exit;
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
		public static function setSessionMessage(string $msg, string $color = ''): void
		{
			
			if (!isset($_SESSION['lec_msg'])){
				$_SESSION['lec_msg'] = [];
			}
			
			if ($color === ''){
				
				if (trim($msg) !== ''){
					$_SESSION['lec_msg'][] = $msg;
				}
				
			} else {
				
				if (trim($msg) !== ''){
					$_SESSION['lec_msg'][] = [
						'msg' =>$msg,
						'color'=>$color
					];
				}
				
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
     * @return void
     */
		public static function clearSessionMessages(): void
		{
			
			if (isset($_SESSION['lec_msg'])){
				unset($_SESSION['lec_msg']);
			}
			return;
		}
	
}
