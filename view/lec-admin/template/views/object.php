<?php

	if (isset($_GET['ob'])){
		
		$objectLoaded = $this->lecAdmin->loadObject((int)$_GET['ob']);
		
		$permissions = \LecAdmin\Form::loadOptionsFromDbArray($this->DBH, ['id', 'identifier'], 'lec-admin_user_permission_types');
		
		if (isset($permissions[$objectLoaded['permission']])){
			if($this->adminUser->getAdminPermission($permissions[$objectLoaded['permission']])){
		
				//catch deletes
					if (isset($_POST['delete'])){
						
						if($this->lecAdmin->deleteItems($objectLoaded)){
							\Lectric\controller::setSessionMessage('<i class="fa fa-check"></i> '.$objectLoaded['s_word'].' Deleted.');
						} else {
							\Lectric\controller::setSessionMessage('<i class="fa fa-times"></i> Failed to Delete '.$objectLoaded['s_word'].'.');
						}
					
					}

				//catch dupes
					if (isset($_POST['duplicate'])){
						
						if($this->lecAdmin->duplicateItems($objectLoaded)){
							\Lectric\controller::setSessionMessage('<i class="fa fa-check"></i> '.$objectLoaded['s_word'].' Duplicated.');
						} else {
							\Lectric\controller::setSessionMessage('<i class="fa fa-times"></i> Failed to Duplicated '.$objectLoaded['s_word'].'.');
						}
					
					}

				//catch saves
					if (isset($_POST['id']) || isset($_POST['lec-admin_new'])){
						
						$new = (isset($_POST['lec-admin_new'])) ? true : false;
						
						if($this->lecAdmin->saveItem($objectLoaded, $new)){
							\Lectric\controller::setSessionMessage('<i class="fa fa-check"></i> '.$objectLoaded['s_word'].' Saved.');
						} else {
							\Lectric\controller::setSessionMessage('<i class="fa fa-times"></i> Failed to Save '.$objectLoaded['s_word'].'.');
						}
					
					}
					
				//output table or form
					if (isset($_GET['list'])){
						$this->lecAdmin->listHTML((int)$_GET['ob']);
					} else if(isset($_GET['edit']) || isset($_POST['id'])){
						$this->lecAdmin->formHTML((int)$_GET['ob'], (int)$_GET['edit'], false);
					} else if(isset($_GET['new'])){
						$this->lecAdmin->formHTML((int)$_GET['ob'], null, true);
					}
			} else {
				?><p class="tools-alert tools-alert-yellow">You do not have permission to view this page</p><?php
			}
		} else {
			?><p class="tools-alert tools-alert-yellow">You do not have permission to view this page</p><?php
		}
	}
