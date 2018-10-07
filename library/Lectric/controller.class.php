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
				define('VIEW', 
					(URL_PATH === '/') ?
						true : 
						((URL_NODES[0] !== 'do') ? 
							true : 
							((!isset(URL_NODES[1])) ?
								true :
								( (URL_NODES[1] !== 'response' && URL_NODES[1] !== 'action') ?
									true:
									false
								)
							)
						) 
				);
			
			if (VIEW === true){
				$lecView = new view($DBH);
			} else {
					
				try{
					switch(URL_NODES[1]){
						case 'response':
							/*
							* Response type do
							*/
							$lecDo = new doResponse($DBH);
						break;
						case 'action':
							/*
							* Action type do (no response, may generate another view or do something else...?)
							*/
							$lecDo = new doAction($DBH);
						break;
					}
				} catch (\Exception $e){
					
					if(DEBUG){
						\Lectric\controller::setSessionMessage($e->getMessage());
					}
					//graceful request error handling
					$lecView = new view($DBH);
					
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
