<?php

	/*try{
		
		
		$pdo = new \Lectric\lecPDO($this->DBH);
		$pdo->setWhereFields(['url'=>'index']);
		$pdo->setWhereOps('X');
		$pdo->setGroupBy('url');
		//$pdo->setGroupBy(['url', 'id']);
		$pdo->setOrderBy(['url'=>'ASC']);
		//$pdo->setOrderBy(['url'=>'asc']);
		$pdo->setLimit(1);
		//$pdo->setLimit([$x,$y]);
		//$pdo->setLimit('1');
		//$selection = $pdo->selStrict('default_views', \Lectric\lecPDO::SQL_ECHO);
		
		//var_dump($selection);
		
		$updateArray = ['filename'=>'pooper'];
		$pdo->setUpdateFields($updateArray);
		$pdo->setWhereFields(['url'=>'index']);
		$pdo->setWhereOps('=');
		//$pdo->updateStrict('default_views', \Lectric\lecPDO::SQL_ECHO);
		
		$insertArray = ['filename'=>'poop'];
		$pdo->setInsertFields($updateArray);
		//echo $id = $pdo->insertStrict('default_views', \Lectric\lecPDO::SQL_ECHO);
		
		$pdo->setWhereFields(['id'=>'14,15']);
		$pdo->setWhereOps('I');
		//$pdo->deleteStrict('default_views', \Lectric\lecPDO::SQL_ECHO);
		
	} catch (\Exception $e){
		echo $e->getMessage();
	}*/
	
	if (($lecMessages = \Lectric\controller::getSessionMessages()) !== null){

		?><div class="unit-25 unit-centered"><div class="tools-alert tools-alert-yellow"><?php
			foreach ($lecMessages as $msg){
				echo $msg.'<br/>';
			}	
			\Lectric\controller::clearSessionMessages();
		?></div></div><?php

	} else {
	
		echo $this->page['html']; ?>
		<p>So we already know you can VIEW things, but can you DO things? To find out - <span class="test_do">click here</span>.</p> 
		<div class="response"></div>

	<?php }
