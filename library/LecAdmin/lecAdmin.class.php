<?php
namespace LecAdmin;

/**
* Lec Admin class, deals with all thing admin.
*
* @author     Elliott Barratt
* @copyright  Elliott Barratt, all rights reserved.
* @license    As license.txt in root
*
*/ 
class lecAdmin extends \Lectric\SQLQueryPDO 
{
	
	private $_tabs_table = '`lec-admin_tabs`';
	private $_objects_table = '`lec-admin_objects`';
	
	private $_tabs_html = '/view/lec-admin/template/includes/tabs/tabs.php';
	private $_object_list_html = '/view/lec-admin/template/includes/object/list.php';
	private $_object_form_html = '/view/lec-admin/template/includes/object/form.php';
	
	private $_selectionTable;
	private $_selectTableFields = array();
	private $_sql_inj;
	private $_adminUrl;
	
	/* HTML FUNCTION */
	
        /**
         * Function to output the navigation html in admin area
         * 
         * 
         * @return void
         */
		public function adminTabsHTML(): void
		{
			
			$r = '';
			
			try {
				
				$this->setOrderBy(array('sortorder' => 'ASC'));
				$tabs = $this->selStrict($this->_tabs_table, 'MULTI', 'NOT_TABLED');
				
				require_once(DOC_ROOT.$this->_tabs_html);
				
			} catch (SQLException $e) {
				if(DEBUG){
					echo 'Failed to load tabs for admin navigation in adminTabsHTML(): '.$e->getMessage();
				}
			}
			
			return;
			
		}
	
		public function listHTML(int $objectid): void
		{
			
			try{
				$objectLoaded = $this->loadObject($objectid);
				
				require_once(DOC_ROOT.$this->_object_list_html);
				
			} catch (SQLException $e) {
				if(DEBUG){
					echo 'Failed to load object in listHTML(): '.$e->getMessage();
				}
				return;
			}
			
		}
		
        /**
         * Output the object form HTML
         * 
         * @param int $objectid Id of the Object
         * @param int $itemid  Id of the item being edited
         * @param bool $new  Switch for making new item / saving pre-existing one
         * 
         * @return void
         */
		public function formHTML(int $objectid, int $itemid = null, bool $new = true): void
		{
			
			try{
				
				$objectLoaded = $this->loadObject($objectid);
				
				if ($objectLoaded !== null){
					
					if($new){
						
						$itemLoaded = [];
						foreach(json_decode($objectLoaded['edit_fields'], true) as $editField){
							$itemLoaded[$editField['field']] = '';
						}
						
					} else {
						
						$itemLoaded = $this->loadItem($itemid, $objectLoaded['table']);
						
						if ($itemLoaded === null){
							echo 'This item no longer exists.';
							return;
						}
						
					}
					
					require_once(DOC_ROOT.$this->_object_form_html);
					
				} else {
					echo 'This object does not exist.';
					return;
				}
				
			} catch (SQLException $e) {
				if(DEBUG){
					echo 'Failed to load object in formHTML(): '.$e->getMessage();
				}
				return;
			}
			
		}
		
	/* END HTML FUNCTION */
	
	/* DB FUNCTION */
	
		/**
         * duplicateItems
         * 
         * @param array $object  
         * 
         * @return bool
         */
		public function duplicateItems(array $objectLoaded): bool
		{
			
			foreach ($_POST as $key => $idDelete){
				
				if (preg_match('#admin_table_item_check_[0-9]+#', $key)){
			
					$itemId = str_replace('admin_table_item_check_','',$key);
					
					try{
			
						//load item
							$itemLoaded = $this->loadItem($itemId, $objectLoaded['table']);
					
						//make array
							$insertArray = [];
							foreach ($itemLoaded as $field => $value){
								if ($field == 'id'){continue;}
								$insertArray[$field] = $value;
							}
					
						//insert
							$this->setQueryFields($insertArray);
							$lastId = $this->insertStrict($objectLoaded['table']);
						
					} catch (SQLException $e) {
						if (DEBUG){
							echo 'Failed to insert new item in duplicateItems() : '.$e->getMessage();
						}
						return false;						
					}
				}
			}
			
			return true;

		}			
	
		/**
         * deleteItems
         * 
         * @param array $object  
         * 
         * @return bool
         */
		public function deleteItems(array $objectLoaded): bool
		{
						
			foreach ($_POST as $key => $idDelete){
				
				if (preg_match('#admin_table_item_check_[0-9]+#', $key)){
			
					$itemId = str_replace('admin_table_item_check_','',$key);
					
					//load item
					$itemLoaded = $this->loadItem($itemId, $objectLoaded['table']);
					
					try { 
					
						//main table delete
							$this->setWhereFields(array('id' => $itemId));
							$this->setWhereOps('=');
							$this->deleteStrict($objectLoaded['table']);
							
						//images
							$imageFields = json_decode($objectLoaded['img_fields'], true);
							if ($imageFields !==null){
								if (!empty($imageFields)){
									
									foreach($imageFields as $imgField){
										if (file_exists(DOC_ROOT.$objectLoaded['img_directory'].$itemLoaded[$imgField]) && trim($itemLoaded[$imgField]) !== ''){
											unlink(DOC_ROOT.$objectLoaded['img_directory'].$itemLoaded[$imgField]);
										}
										if (file_exists(DOC_ROOT.$objectLoaded['thumb_directory'].$itemLoaded[$imgField]) && trim($itemLoaded[$imgField]) !== ''){
											unlink(DOC_ROOT.$objectLoaded['thumb_directory'].$itemLoaded[$imgField]);
										}
									}
									
								}
							}
							
						//deletion tables
							$deletionTables = json_decode($objectLoaded['deletion_tables'], true);
							if ($deletionTables !== null){
								if (!empty($deletionTables)){
									
									foreach($deletionTables as $table){
										$this->setWhereFields(array($table['field'] => $itemId));
										$this->setWhereOps('=');
										$this->deleteStrict($table['table']);
									}
									
								}
							}
						
					} catch (SQLException $e) {
						if (DEBUG){
							echo 'Failed to delete item in admin_deleteFromTable() : '.$e->getMessage();
						} else {
							$_SESSION['adminmsg'][] = 'Deletion from table: '.$table.' failed.';
						}
						
					}
					
				}
				
			}
			
			return true;
			
		}
		
        /**
         * Save an item to the database
         * 
         * @param array $objectLoaded 
         * @param bool $newItem  
         * 
         * @return bool
         */
		public function saveItem(array $objectLoaded, bool $newItem = false): bool
		{
				
			//construct insert array
				$insertArray = [];
				foreach(json_decode($objectLoaded['edit_fields'], true) as $editField){
					
					switch ($editField['edit_type']){
						case 'image':
							continue; //dealt with below
						break;
						case 'url':
							$insertArray[$editField['field']] = preg_replace('/[^0-9a-zA-z\.\-\/_]/', '', strtolower (trim($_POST[$editField['field']])));
						break;
						case 'text':
							$insertArray[$editField['field']] = htmlentities(trim($_POST[$editField['field']]));
						break;
						case 'textlower':
							$insertArray[$editField['field']] = htmlentities(strtolower(trim($_POST[$editField['field']])));
						break;
						case 'html':
							$insertArray[$editField['field']] = $_POST[$editField['field']];
						break;
						case 'number':
							$insertArray[$editField['field']] = preg_replace('/[^0-9\.]/', '', $_POST[$editField['field']]);
						break;
						case 'password':
							if (trim($_POST[$editField['field']] !== '')){
								//only if new one entered
								$insertArray['salt'] = $salt = bin2hex(random_bytes(5));
								$insertArray[$editField['field']] = password_hash($_POST[$editField['field']].$salt, PASSWORD_DEFAULT); 
							}
						break;
					}
					
				}
				
				//deal with deletion of images
				
					foreach ($_POST as $key => $value){
        
						if (preg_match('/^deletefile_(.*)$/', $key)){
							
							if (file_exists(DOC_ROOT.$objectLoaded['img_directory'].$value)){
								unlink(DOC_ROOT.$objectLoaded['img_directory'].$value);
							}
							//get the thumb too
							if (file_exists(DOC_ROOT.$objectLoaded['thumb_directory'].$value)){
								unlink(DOC_ROOT.$objectLoaded['thumb_directory'].$value);
							}
							
						}
						
					}
					
				
				//deal with image uploads
					if (trim($objectLoaded['img_fields']) !== ''){
						
						$imgFields = json_decode($objectLoaded['img_fields'], true);
						
						if (!empty($imgFields)){
						
							foreach($imgFields as $imageField){
								
								//any attacks or suspicious params?
								if (isset($_FILES[$imageField])  && is_uploaded_file($_FILES[$imageField]['tmp_name'])){
							
									//any litteral errors
										switch ($_FILES[$imageField]['error']) {
											case UPLOAD_ERR_OK:
												break;
											case UPLOAD_ERR_NO_FILE:
												$message =  '1: No file sent.';
												\Lectric\controller::setSessionMessage('Errors trying to upload your image file: '.$message);
											case UPLOAD_ERR_INI_SIZE:
											case UPLOAD_ERR_FORM_SIZE:
												$message =  'Exceeded filesize limit.';
												\Lectric\controller::setSessionMessage('Errors trying to upload your image file: '.$message);
											default:
												$message =  'Unknown errors.';
												\Lectric\controller::setSessionMessage('Errors trying to upload your image file: '.$message);
										}
									
									//does it exceed a certain file size?
										if ($_FILES[$imageField]['size'] > 10000000) {
											$message =  'Exceeded filesize limit.';
											\Lectric\controller::setSessionMessage('Errors trying to upload your image file: '.$message);
											break;
										}
										
									//check the mime type thoroughly
										$finfo = new \finfo(FILEINFO_MIME_TYPE);
										@$finfoFile = $finfo->file($_FILES[$imageField]['tmp_name']);
										if ($finfoFile === false){
											$message =  'Invalid file format';
											\Lectric\controller::setSessionMessage('Errors trying to upload your image file: '.$message);
											break;
										} else {
											
											if (
												(false === $ext = array_search($finfoFile, ['jpg' => 'image/jpeg'], true)) && 
												(false === $ext = array_search($finfoFile, ['jpg' => 'image/pjpeg'], true)) && 
												(false === $ext = array_search($finfoFile, ['jpeg' => 'image/jpeg'], true)) && 
												(false === $ext = array_search($finfoFile, ['jpeg' => 'image/pjpeg'], true)) && 
												(false === $ext = array_search($finfoFile, ['png' => 'image/png'], true))
											) {
												$message =  'Invalid file format..';
												\Lectric\controller::setSessionMessage('Errors trying to upload your image file: '.$message);
												break;
											}
											
											//try to move the file
												
												if (!is_dir(DOC_ROOT.$objectLoaded['img_directory'])){
													mkdir(DOC_ROOT.$objectLoaded['img_directory']);
												}
											
												$nameTemp = htmlentities($_FILES[$imageField]["name"]);
												$newImage = DOC_ROOT.$objectLoaded['img_directory'].$nameTemp;
												if(!move_uploaded_file($_FILES[$imageField]["tmp_name"], $newImage)){
													$message =  'Failed to move uploaded image.';
													\Lectric\controller::setSessionMessage('Errors trying to upload your image file: '.$message);
													break;
												}
											//add to db edit
												$insertArray[$imageField] = $nameTemp;
										}
										
									//thumbs
										if (!is_dir(DOC_ROOT.$objectLoaded['thumb_directory'])){
											mkdir(DOC_ROOT.$objectLoaded['thumb_directory']);
										}
									
										if (($_FILES[$imageField]["type"] == "image/jpeg") || ($_FILES[$imageField]["type"] == "image/jpg")){
					
											$desired_width = 200;
											
											/* read the source image */
											$source_image = imagecreatefromjpeg($newImage);
											$width = imagesx($source_image);
											$height = imagesy($source_image);
											
											/* find the "desired height" of this thumbnail, relative to the desired width  */
											$desired_height = floor($height * ($desired_width / $width));
											
											/* create a new, "virtual" image */
											$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
											
											/* copy source image at a resized size */
											imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
											
											/* create the physical thumbnail image to its destination */ 
											imagejpeg($virtual_image, DOC_ROOT.$objectLoaded['thumb_directory'].$nameTemp);
											
											imagedestroy($source_image);
											imagedestroy($virtual_image);
											
										} else if ($_FILES[$imageField]["type"] == "image/png"){
											
											$desired_width = 200;
											
											/* read the source image */
											$source_image = imagecreatefrompng($newImage);
											$width = imagesx($source_image);
											$height = imagesy($source_image);
											
											/* find the "desired height" of this thumbnail, relative to the desired width  */
											$desired_height = floor($height * ($desired_width / $width));
											
											/* create a new, "virtual" image */
											$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
											
											/* copy source image at a resized size */
											imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
											
											/* create the physical thumbnail image to its destination */
											imagepng($virtual_image, DOC_ROOT.$objectLoaded['thumb_directory'].$nameTemp);
											
											imagedestroy($source_image);
											imagedestroy($virtual_image);
											
										}
								}
								
							}
						}
					}
				
			//make the db edit
				try {
					if ($newItem){
						$this->setQueryFields($insertArray);
						$_POST['id'] = $this->insertStrict($objectLoaded['table']); //set the post id here so you can use it in include files on list page...
					} else {
						$this->setQueryFields($insertArray);
						$this->setWhereFields(array('W_id'=>$_POST['id']));
						$this->setWhereOps('=');
						$this->updateStrict($objectLoaded['table']);
					}
				} catch (SQLException $e) {
					if (DEBUG){
						echo 'Failed to save item in saveItem() : '.$e->getMessage();
					} 
					return false;
				}
				
			return true;
			
		}
	
	/* END DB FUNCTION */
	
	/* UTILITY FUNCTION */
	
        /**
         * Load a given object from lec admin objects table
         * 
         * @param int $objectid 
         * 
         * @return array
         */
		public function loadObject(int $objectid): ?array
		{
			try{
				$this->setWhereFields(['id' => $objectid]);
				$this->setWhereOps('=');
				$objectLoaded = $this->selStrict($this->_objects_table, 'SINGLE', 'NOT_TABLED');
			} catch (SQLException $e){
				if (DEBUG){
					echo 'Failed to load object: '.$e->getMessage();
				}
				return null;
			}
			
			return $objectLoaded;
			
		}
	
        /**
         * Load an item from an object table
         * 
         * @param int $itemid 
         * @param string $table 
         * 
         * @return array
         */
		public function loadItem(int $itemid, string $table): ?array
		{
			try{
				$this->setWhereFields(['id' => $itemid]);
				$this->setWhereOps('=');
				$itemLoaded = $this->selStrict($table, 'SINGLE', 'NOT_TABLED');
			} catch (SQLException $e){
				if (DEBUG){
					echo 'Failed to load item: '.$e->getMessage();
				}
				return null;
			}
			
			return $itemLoaded;
			
		}
	
        /**
         * countObjectItems
         * 
         * @param string $table  
         * 
         * @return int
         */
		public function countObjectItems(string $table = ''): int
		{
			
			$table =  trim ($table, '`');
			
			try{
				$count = $this->select('SELECT COUNT(*)  AS "COUNT" FROM `'.$table.'`', 'SINGLE', 'STRICT');
			} catch (SQLException $e){
				if (DEBUG){
					echo 'Failed to count table items: '.$e->getMessage();
				}
				return $count = 0;
			}
			
			return $count['']['COUNT'];
			
		}
	
	/* END UTILITY FUNCTION */
		
}
