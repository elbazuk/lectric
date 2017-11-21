<?php
namespace Lectric;

/**
* Deals with actions from do functions
*
* The control action holds the action, if any, of the do function
*
* @package    Lectric Framework
* @author     Elliott Barratt
* @copyright  Elliott Barratt, all rights reserved.
*
*/ 
class controlAction 
{
	
	private $_type = '';
	private $_location = '';
	private $_message = '';
	private $_color = '';
	
    /**
     *
     * Set member variables for use in performAction()
     */
		function __construct(string $type = 'none', string $location = '', string $msg = '', string $color = '')
		{
			
			switch ($type){
				
				case 'view':
					$this->_type = $type;
					$this->_location = $location;
					$this->_message = $msg;
					$this->_color = $color;
				break;
				case 'none':
					$this->_message = $msg;
					$this->_color = $color;
				break;
				default:
					//do nothing
				break;
				
				}
				
			return;
			
		}
	
    /**
     * perform the action set up by construct.
     * 
     * @return void
     */
		public function performAction(): void
		{
			
			switch ($this->_type){
				
				case 'view':
					controller::setSessionMessage($this->_message, $this->_color);
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
