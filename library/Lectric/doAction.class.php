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
	 * @param object $DBH db handler (passthrough only)
	 *
     */
		function __construct(&$DBH)
		{
			
			if (count(URL_NODES) !== 5){
				throw new \Exception('Wrong Node Count for Do-Action.');
			}
			
			if (class_exists('\\'.URL_NODES[2].'\\'.URL_NODES[3])){
				
				$doClass = '\\'.URL_NODES[2].'\\'.URL_NODES[3];
				$doer = new $doClass($DBH);
				
				if (method_exists($doer, 'do_'.URL_NODES[4])){
					
					$action = $doer->{'do_'.URL_NODES[4]}();
					
					if ($action instanceof \Lectric\controlAction) {
					
						$action->performAction();
						
					} else {
						throw new \Exception('Class '.htmlentities('\\'.URL_NODES[2].'\\'.URL_NODES[3].' doesn\'t return a controllAction class in Do-Action.'));
					}
				
				} else {
					throw new \Exception('Class '.htmlentities('\\'.URL_NODES[2].'\\'.URL_NODES[3].' doesn\'t contain method '.'do_'.URL_NODES[4].' in Do-Action.'));
				}
				
			} else {
				throw new \Exception('Class '.htmlentities('\\'.URL_NODES[2].'\\'.URL_NODES[3]).' doesn\'t exist for Do-Action.');
			}
			
		}
	
}
