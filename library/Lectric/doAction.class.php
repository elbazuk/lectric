<?php
namespace Lectric;

/**
* do-action class
*
* @package    Lectric Framework
* @author     Elliott Barratt
* @copyright  Elliott Barratt, all rights reserved.
*
*/ 
class doAction
{
	
    /**
     * The do action construct for calling do functions
     *
     * @param array URL_NODES the URL nodes array
     *
	 * @param object $DBH db handler 
	 *
     */
		function __construct(&$DBH)
		{
			
			if (count(URL_NODES) !== 5){
				throw new \Exception('wrong do node count for action.');
			}
			
			if (class_exists('\\'.URL_NODES[2].'\\'.URL_NODES[3])){
				
				$doClass = '\\'.URL_NODES[2].'\\'.URL_NODES[3];
				$doer = new $doClass($DBH);
				
				if (method_exists($doer, 'do_'.URL_NODES[4])){
				
					$action = $doer->{'do_'.URL_NODES[4]}()->performAction();
				
				} else {
					throw new \Exception('Class '.htmlentities('\\'.URL_NODES[2].'\\'.URL_NODES[3]).' doesn\'t contain method '.'do_'.URL_NODES[4].' in do action.');
				}
				
			} else {
				throw new \Exception('Class '.htmlentities('\\'.URL_NODES[2].'\\'.URL_NODES[3]).' doesn\'t exist for do action.');
			}
			
		}
	
}
