<?php



	/*

	* Set DB connection details

	*/

		if (strpos($_SERVER['HTTP_HOST'],'spdev.website') !== false) {

			// DEV

			define('DOC_ROOT', '/home/lectric/public_html/'); //define('DOC_ROOT', '/customers/8/2/5/cms.co.uk//httpd.www/');

			define('DB_NAME', 'lectric_db');

			define('DB_USER', 'lectric_user');

			define('DB_PASSWORD', '=Z6Q[N3W7dha');

			define('DB_HOST', 'localhost');

			

			/*Error Reporting on, turn off after deployment. */

			define('DEBUG', TRUE);

		} else { 

			// LIVE

			define('DOC_ROOT', '/home/demo/public_html/'); //define('DOC_ROOT', '/customers/8/2/5/cms.co.uk//httpd.www/');

			define('DB_NAME', 'demo_db');

			define('DB_USER', 'demo_user');

			define('DB_PASSWORD', 'E-9o=&*PD)JW');

			define('DB_HOST', 'localhost');

			

			/*Error Reporting on, turn off after deployment. */

			define('DEBUG', FALSE);

		}





	/*

	* Timezone 

	*/

		date_default_timezone_set('Europe/London'); 

		

	/* 

	* core definitions

	*/

		define('SESSION_IGNORES', []);

		define('DEFAULT_DIRECTORY', 'default');

		define('SITE_NAME','LECTRIC FRAMEWORK');

		define('SITE_LINK','lectric.spdev.website');

		define('SITE_DESCRIPTION','Dashboard Data Demo');

