<?php

namespace Lectric;

/**

* Controller routing class for do/view

*

* The controller determines whether do and action or vieww something

*

* @package    RWS Framework

* @author     Elliott Barratt

* @copyright  Elliott Barratt, all rights reserved.

* @license    As license.txt in root

*

*/ 

class controller {

	

    /**

     * construct contains the main do or view logic

     *

     * @param array URL_NODES array of URL nodes 

	 *

     */

	function __construct($DBH){

		

		if (VIEW){

			//VIEW!

			$lecView = new view($DBH);

		} else {

			//DO!

			if (count(URL_NODES) !== 3){

				

				if (DEBUG){

					echo 'wrong do node count.';

				}

				exit;

				

			} else {

				

				if (file_exists(DOC_ROOT.'/do/'.URL_NODES[1].'/'.URL_NODES[2].'.php')) {

					require(DOC_ROOT.'/do/'.URL_NODES[1].'/'.URL_NODES[2].'.php');

				} else {

					if (DEBUG){

						echo 'do file not in do directory: '.URL_NODES[1];

					}

					exit;

				}

				

			}

		}

		

	}

	

}
