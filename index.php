<?php

/**
* call up the bootstrap initialiser 
*/
if (file_exists(__DIR__.'/engine/init.php')) {
	require(__DIR__.'/engine/init.php');
} else {
	die('Init file not found');
}
