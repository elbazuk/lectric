<?php
	
	/*
	* process do-response ajax call from /view/default/template/views/index.php
	*/
	if (isset($_POST['do'])){
		
		?><p>Congrats, this message means you can DO things too! Here's some info about your current configuration:</p><?php
		?><p class="end">The SESSION is <?php echo (isset($_SESSION)) ? 'ON':'OFF'; ?>: /engine/app_config.php -> SESSION_IGNORES definition.</p><?php
		?><p class="end">The default view directory is <?php echo DEFAULT_DIRECTORY; ?>: /engine/app_config.php -> DEFAULT_DIRECTORY definition.</p><?php
		?><p class="end">The Project Root is <?php echo DOC_ROOT; ?></p><?php
		?><p class="end">The Request URL for this do action is <?php echo URL_REQUEST; ?></p>
		<br/>
		<form action="/do/action/Lectric/lecDefault/test/" method="post"><button type="submit" class="btn btn-large btn red">Push Me!</button></form>
		
		<?php
		
	}
