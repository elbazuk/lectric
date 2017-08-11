<br/>

<?php


	$objectsLoaded = $this->selStrict('lec-admin_objects', 'MULTI', 'NOT_TABLED');

	if ($objectsLoaded !== null){
		
		?><div class="units-row"><?php
		
		$i = 1;
		$objectCount = count($objectsLoaded);
		foreach($objectsLoaded as $ob){
			
			?><div class="unit-20 index_object_button">
				
				<a href="/lec-admin/object?ob=<?php echo $ob['id']; ?>&list=yes"><i class="fa fa-fw <?php echo $ob['icon']; ?>"></i><?php echo $ob['name']; ?></a>
			
			</div><?php
			
			if (($i % 5 == 0)){
				
				if ($i < $objectCount){
					?></div><div class="units-row"><?php
				}
				
			}
			
			$i++;
			
		}
		
		?></div><?php
		
	} else {
		?><p>There are no objects in this administration panel.</p><?php
	}
	