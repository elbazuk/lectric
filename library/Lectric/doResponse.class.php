<?php
namespace Lectric;

/**
* do response class
*
* @package    Lectric Framework
* @author     Elliott Barratt
* @copyright  Elliott Barratt, all rights reserved.
*
*/ 
class doResponse extends SQLQueryPDO
{
	/**
     * The do response construct for calling do response files
     *
     * @param array $lecNodes the URL nodes array
     *
	 * @param object $DBH db handler 
	 *
     */
		function __construct(array $lecNodes, &$DBH)
		{
			
			parent::__construct($DBH);
			
			if (count($lecNodes) !== 4){
				throw new \Exception('wrong do node count for response.');
			}
			
			if (file_exists(DOC_ROOT.'/do/'.$lecNodes[2].'/'.$lecNodes[3].'.php')) {
				require(DOC_ROOT.'/do/'.$lecNodes[2].'/'.$lecNodes[3].'.php');
			} else {
				throw new \Exception('do file not in do directory: '.$lecNodes[2]);
			}
			
		}
	
}
