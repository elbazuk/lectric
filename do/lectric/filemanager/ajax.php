<?php 

$this->adminUser = new \LecAdmin\adminUser($this->DBH);
$this->filemanager = new \LecAdmin\filemanager('/uploads/');

if ( (isset($_POST['func']) || isset($_FILES['fm_upload_file'])) && $this->adminUser->loggedIn()){
	
	try{
	
		//catch uploaded file
		if (isset($_FILES['fm_upload_file']) && is_uploaded_file($_FILES['fm_upload_file']['tmp_name'])){
			
			echo $uploadedFile = $this->filemanager->uploadFile($_POST['directory']);
			
		}

		if (isset($_POST['func'])){
			//continue to do something
			switch ($_POST['func']){
				
				case 'CHANGE_DIRECTORY':
					$this->filemanager->loadDirectoryContentsHTML($_POST['new_dir'] );
				break;
				case 'GET_NEW_BREADCRUMB':
					echo $this->filemanager->getBreadcrumb($_POST['new_dir']);
				break;
				case 'MAKE_NEW_DIR':
					if ($this->filemanager->makeNewDirectory($_POST['make_new_dir'], $_POST['new_dir']) === false){
						echo 'permission_denied';
					}
				break;
				case 'DELETE_FILE':
					@unlink($_POST['item']);
					$this->filemanager->loadDirectoryContentsHTML($_POST['new_dir']);
				break;
				case 'DELETE_FOLDER':
					if($this->filemanager->deleteFolder($_POST['folder']) === false){
						echo 'remove_folder_failed'; exit;
					} else {
						$this->filemanager->loadDirectoryContentsHTML($_POST['new_dir']);
					}
				break;
				case 'GET_TREE':
					$this->filemanager->generateFileTreeHTML($_SESSION['userid']);
				break;
				
			}
		}
		
	} catch (\Exception $e){
		if (DEBUG){
			echo $e->getMessage();
		}
	}
	
}
