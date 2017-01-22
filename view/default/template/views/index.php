
	<?php 
		if (($lecMessages = \Lectric\controller::getSessionMessages()) !== null){
			
			?><div class="unit-25 unit-centered"><div class="tools-alert tools-alert-yellow"><?php
				foreach ($lecMessages as $msg){
					echo $msg.'<br/>';
				}	
				\Lectric\controller::clearSessionMessages();
			?></div></div><?php
			
		} else {
	?>

		<?php echo $this->page['html']; ?>

		<p>So we already know you can VIEW things, but can you DO things? To find out - <span class="test_do">click here</span>.</p> 

		<div class="response"></div>

		<?php }