<?php
namespace Lectric;

/**
* Deals with actions from do functions
*
* The control action holds the action, if any, of the do function
*
* @package    RWS Framework
* @author     Elliott Barratt
* @copyright  Elliott Barratt, all rights reserved.
* @license    As license.txt in root
*
*/ 
class controlAction 
{
	
	private $_type = '';
	private $_location = '';
	private $_message = '';
	
    /**
     *
     * Set member variables for use in performAction()
     */
		function __construct(string $type = 'none', string $location = '', string $msg = '')
		{
			
			switch ($type){
				
				case 'view':
					$this->_type = $type;
					$this->_location = $location;
					$this->_message = $msg;
				break;
				case 'none':
					$this->_message = $msg;
				break;
				default:
					//do nothing
				break;
				
				}
				
			return;
			
		}
	
    /**
     * perform the action set up by construct
     * 
     * @return void
     */
		public function performAction(): void
		{
			
			switch ($this->_type){
				
				case 'view':
					controller::setSessionMessage($this->_message);
					header('Location: '.$this->_location);
					exit;
				break;
				case 'none':
					if (trim($this->_message) !== ''){
						echo $this->_message;
					}
					exit;
				break;
				default:
					exit;
				break;
				
			}
			
		}
	
}