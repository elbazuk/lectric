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
     * @param array $lecNodes the URL nodes array
     *
	 * @param object $DBH db handler 
	 *
     */
		function __construct(array $lecNodes, $DBH)
		{
			
			if (count($lecNodes) !== 5){
				throw new \Exception('wrong do node count for action.');
			}
			
			if (class_exists('\\'.$lecNodes[2].'\\'.$lecNodes[3])){
				
				$doClass = '\\'.$lecNodes[2].'\\'.$lecNodes[3];
				$doer = new $doClass($DBH);
				
				if (method_exists($doer, 'do_'.$lecNodes[4])){
				
					$action = $doer->{'do_'.$lecNodes[4]}()->performAction();
				
				} else {
					throw new \Exception('Class '.htmlentities('\\'.$lecNodes[2].'\\'.$lecNodes[3]).' doesn\'t contain method '.'do_'.$lecNodes[4].' in do action.');
				}
				
			} else {
				throw new \Exception('Class '.htmlentities('\\'.$lecNodes[2].'\\'.$lecNodes[3]).' doesn\'t exist for do action.');
			}
			
		}
	
}
