<?php

//add new permission
if(isset($_POST['lec-admin_new'])){
	
	//add permission
	$this->setInsertFields([
		'user'=>$_POST['id'],
		'permission'=>1
	]);
	$this->insertStrict('lec-admin_user_permissions');
	
}

//remove permission
if (isset($_POST['delete'])){
	foreach($_POST as $key => $value){
		
		if(strpos($key, 'admin_table_item_check_') !== false){
			
			$deleteid = str_replace('admin_table_item_check_', '', $key);
			$this->setWhereFields(['user'=>$deleteid]);
			$this->setWhereOps('=');
			$this->deleteStrict('lec-admin_user_permissions');
			
		}
		
	}
}
