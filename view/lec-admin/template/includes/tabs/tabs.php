<?php

if ($tabs !== null){

	?><ul><?php
	
	foreach ($tabs as $tab){
		
		$tabSelected = false;
		
		try{
			$this->setWhereFields(['tab' => $tab['id']]);
			$this->setWhereOps('=');
			$objects = $this->selStrict($this->_objects_table, 'MULTI', 'NOT_TABLED');
		} catch (SQLException $e) {
			if(DEBUG){
				echo 'Failed to load objects for tab for admin navigation: '.$e->getMessage();
			}
			return;
		}
		
		if ($objects != null){
			
			$webLinkMenu = '<ul class="header_dropdown end">';
			
			foreach ($objects as $ob){
				
				if (isset($_GET['ob'])){
					$selected = ($_GET['ob'] == $ob['id']) ? 'nav_active' : '';
					$tabSelected = ($_GET['ob'] == $ob['id']) ? true : $tabSelected;
				} else {
					$selected = '';
				}
				
				$webLinkMenu .= '<li><a href="/lec-admin/object?ob='.$ob['id'].'&list=yes" class="'.$selected.'"><i class=" fa  '.$ob['icon'].' fa-fw"></i> '.$ob['name'].'</a></li>';
				
			}
			
			$webLinkMenu .= '</ul>';
			
			$tabActive = ($tabSelected === true) ? 'nav_active' : '';
			?><li class="header_tab <?php echo $tabActive; ?>"><?php echo $tab['name'].$webLinkMenu; ?></li><?php
			
		}
		
	}
	
	?></ul><?php

}