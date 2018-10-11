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
class doResponse extends lecPDO
{
	/**
     * The do response construct for calling do response files
     *
	 * @param object $DBH db handler 
	 *
     */
		function __construct(&$DBH)
		{
			
			parent::__construct($DBH);
			
			if (count(URL_NODES) !== 4){
				throw new \Exception('Wrong Node Count for Do-Response.');
			}
			
			if (file_exists(DOC_ROOT.'/do/'.URL_NODES[2].'/'.URL_NODES[3].'.php')) {
				require(DOC_ROOT.'/do/'.URL_NODES[2].'/'.URL_NODES[3].'.php');
			} else {
				throw new \Exception('File not in /do/ directory: '.htmlentities(URL_NODES[2]));
			}
			
		}
	
}
