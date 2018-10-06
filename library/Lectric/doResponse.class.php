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
     * @param array URL_NODES the URL nodes array
     *
	 * @param object $DBH db handler 
	 *
     */
		function __construct(&$DBH)
		{
			
			parent::__construct($DBH);
			
			if (count(URL_NODES) !== 4){
				throw new \Exception('wrong do node count for response.');
			}
			
			if (file_exists(DOC_ROOT.'/do/'.URL_NODES[2].'/'.URL_NODES[3].'.php')) {
				require(DOC_ROOT.'/do/'.URL_NODES[2].'/'.URL_NODES[3].'.php');
			} else {
				throw new \Exception('do file not in do directory: '.URL_NODES[2]);
			}
			
		}
	
}
