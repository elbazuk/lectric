<?php
namespace Lectric;

/**
* default class for testing purposes
*
* @package    Lectric Framework
* @author     Elliott Barratt
* @copyright  Elliott Barratt, all rights reserved.
*
*/ 
class lecDefault extends SQLQueryPDO 
{

    /**
     * construct does nothing...
     *
     * @param array DBH array of URL nodes 
	 *
     */
		function __construct($DBH)
		{
			
			parent::__construct($DBH);
			
		}
	
    /**
	* test function for do-action
	*
	* @param array $postData passed do post data
	*
	* @return \Lectric\controlAction
	*/	
		public function do_test(): \Lectric\controlAction
		{ 
			return new \Lectric\controlAction('view', '/', 'That\'s everything checked! your project is now ready to be developed on.');
		}
	
}
