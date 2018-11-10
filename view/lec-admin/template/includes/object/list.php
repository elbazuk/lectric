<?php
	
	$itemCount = $this->countObjectItems($objectLoaded['table']);
	$fieldArray = explode(',',$objectLoaded['table_fields']);
	foreach($fieldArray as $key => $value){
		$fieldArray[$key] = trim($value, '`');
	}
	
	//add to array where field name is the key, to use in select_yesno 1 + 0 output on table...
	$formFields = json_decode($objectLoaded['edit_fields'], true);
	foreach($formFields as $key => $settings){
		unset($formFields[$key]);
		$formFields[$settings['field']] = $settings;
	}
	
?>

<h1><i class="fa <?php echo $objectLoaded['icon'] ;?>"></i> <?php echo $objectLoaded['name'];?></h1>

<div class="units-row end">

	<div class="unit-50 end">
	
		<?php if ($objectLoaded['add_new'] === 1 || $objectLoaded['deletions'] === 1 || $objectLoaded['duplications'] === 1){ ?>
	
			<table class="table-simple end">
				<tr>
		
					<?php if ($objectLoaded['add_new'] === 1){ ?>
						<td>
							<a href="/lec-admin/object?ob=<?php echo $objectLoaded['id']; ?>&new=yes" class="btn btn-green add_new"><i class="fa <?php echo $objectLoaded['icon'] ;?>"></i> New <?php echo $objectLoaded['s_word'];?></a>
						</td>
					<?php } ?>
					<?php if ($objectLoaded['deletions'] === 1){ ?>
						<td>
							<button type="button" class="btn btn-red  delete_button"><i class="fa fa-trash-o"></i> Delete <span class="mobile-hide">Selected</span></button>
						</td>
					<?php } ?>
					<?php if ($objectLoaded['duplications'] === 1){ ?>
						<td>
							<button type="button"  class="btn btn-blue  duplicate_button"><i class="fa fa-copy"></i> Duplicate <span class="mobile-hide">Selected</span></button>
						</td>
					<?php } ?>
				</tr>
			</table>
			
		<?php } ?>
		
	</div>
	<div class="unit-50 end" >
	
		<table class="table-simple end">
			<tr>
			
				<?php
				
					$showSort = false;
					$sortArray = [];
				
					foreach ($formFields as $fieldSettings){
						
						if(isset($fieldSettings['sortable'])){
							
							if($fieldSettings['sortable'] === 'yes'){
								
								$showSort = true;
								if(isset($_POST['order_by'])){
									$ascSelected = ($_POST['order_by'] == $fieldSettings['field'].'_lec-admin_ASC') ? 'selected="selected"' :'' ;
									$descSelected = ($_POST['order_by'] == $fieldSettings['field'].'_lec-admin_DESC') ? 'selected="selected"' :'' ;
								} else {
									$ascSelected = '';
									$descSelected = '';
								}
								$sortArray[] = '<option '.$ascSelected.' value="'.$fieldSettings['field'].'_lec-admin_ASC">'.$fieldSettings['name'].' Ascending</option>';
								$sortArray[] = '<option '.$descSelected.' value="'.$fieldSettings['field'].'_lec-admin_DESC">'.$fieldSettings['name'].' Descending</option>';
								continue;
								
							}
							
						}
						
					}
					
					//only show if not searching.
					if($showSort === true && !isset($_POST['search'])){
						
						?><td><?php
						echo \LecAdmin\Form::startForm('order_by_form', 'post', URL_REQUEST , ' class="end" style="display:inline;" ');
						?>
							<select class="width-100" name="order_by" onchange="$('#order_by_form').submit();">
								<option value="none">Sort Page By</option>
								<?php
									foreach($sortArray as $sa){
										echo $sa;
									}
								?>
							</select>
							<button type="submit" style="display:none;">Submit</button>
						<?php
						echo \LecAdmin\Form::closeForm();
						?></td><?php
					}
				
				?>
	
				<?php if ($objectLoaded['search'] === 1){ ?>
				
					<td>
						<?php echo \LecAdmin\Form::startForm('search_form', 'post', '/lec-admin/object?ob='.$objectLoaded['id'].'&list=yes', ' class="end" enctype="multipart/form-data" style="display:inline;" '); ?>
						<?php $s = (isset($_POST['search'])) ? $_POST['search'] : '';?>
						<?php echo \LecAdmin\Form::makeInput('search', 'text', 'search', $s, 'Search', ' class="input-search width-100"  ');?>
						<button type="submit" style="display:none;">Submit</button>
						<?php echo \LecAdmin\Form::closeForm(); ?>
					</td>
					<td style="line-height:35px;white-space:nowrap;width:1%;">
						<a href="/lec-admin/object?ob=<?php echo $objectLoaded['id']; ?>&list=yes" class="right" style="display:block;padding-left:20px;">Clear X </a>
					</td>
					
				<?php } ?>
		
			</tr>
		</table>
	
	</div>
	
</div>

<div class="clear"></div>

<?php

	if (($lecMessages = \Lectric\controller::getSessionMessages()) !== null){
		
		?><div class="tools-alert tools-alert-yellow"><?php
			foreach ($lecMessages as $msg){
				echo $msg.'<br/>';
			}	
			\Lectric\controller::clearSessionMessages();
		?></div><?php
		
	}	
	
	$pagination = new \LecAdmin\pagination($itemCount);
	
	echo \LecAdmin\Form::startForm('adminTable', 'post', '/lec-admin/object?ob='.$objectLoaded['id'].'&list=yes', ' enctype="multipart/form-data" ');

	?><table id="admin_list_table" class="table-hovered width-100" style="background:white;"><?php
		
	try {
		
		//selection limits based on pagination
			$limitInj = 'LIMIT '.PAG_START_ADMIN.','.PER_PAGE_FRONT_ADMIN;
			$limitArray = [PAG_START_ADMIN, PER_PAGE_FRONT_ADMIN];
		
		//is there a search?
			if (isset($_POST['search'])){
				$sqlInj = str_replace('|||search|||', $_POST['search'], $objectLoaded['search_inj']);
			} else {
				$sqlInj = '';	
			}
			
		//hows the orderby?
			if (isset($_POST['order_by'])){
				
				if($_POST['order_by'] === 'none'){
					$orderBy = ['id'=>'DESC'];
				} else {
					$bits = explode('_lec-admin_', $_POST['order_by']);
					$orderBy = [$bits[0]=>$bits[1]];
				}
			} else {
				$orderBy = ['id'=>'DESC'];
			}
	
		//load normal table, or searched results table. 
			if ($sqlInj == ''){
				$fieldArrayHere = $fieldArray;
				$fieldArrayHere[] = 'id';
				$this->setSelectFields($fieldArrayHere);
				$this->setOrderBy($orderBy);
				$this->setLimit($limitArray);
				$loadedItems = $this->selStrict($objectLoaded['table'], \Lectric\lecPDO::MULTI);
			} else {
				$loadedItems = $this->selLax('SELECT `id`,'.$objectLoaded['table_fields'].' FROM `'.trim($objectLoaded['table'], '`').'` '.$sqlInj.' '.$limitInj, null, \Lectric\lecPDO::MULTI);
			}
			
	} catch (\Exception $e){
		if(DEBUG){
			echo 'Failed to load items from object table for lec admin list: '.$e->getMessage();
		}
	}
	
	if ($loadedItems == null){
		?><tr><td>There are no items of this type.</td></tr></table><?php
	} else {
		
		//make friendly versions of field names as table head
			?><thead><tr><th></th><?php
			
				foreach ($fieldArray as $value){
					
					$value = str_replace('`', '',  $value);
						
					?><th><?php echo ucwords(str_replace('`', '', str_replace('_',' ',$value))); ?></th><?php
					
				}
			
			?></tr></thead><?php
		
		//go through each item and produce a table row of the selected fields
		
			?><tbody><?php
			
				foreach ($loadedItems as $item){
				
					?><tr><?php
					
					if (in_array($item['id'],json_decode($objectLoaded['nodelete'], true))){
						?><td style="width:10px;"><input type="checkbox" disabled="disabled"/></td><?php
					} else {
					
						?><td style="width:10px;"><input type="checkbox" id="admin_table_item_check_<?php echo $item['id']; ?>" name="admin_table_item_check_<?php echo $item['id']; ?>" class="admin_table_item_check"/></td><?php
					}
					
						//go through each selected field and output onto table.
						foreach ($fieldArray as $fieldName){
							
							$fieldName = str_replace('`', '',  $fieldName);
							
							$cellValue = '';
							
							//Catch date fields and mark up appropriately
							if (preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $item[$fieldName]) || preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/', $item[$fieldName])){
								if ($item[$fieldName] == '0000-00-00'){
									$cellValue = 'Not Set';
								} else {
									$dateTime = new DateTime(date($item[$fieldName]));
									$cellValue = $dateTime->format('d/m/Y');
								}
							} else if(isset($formFields[$fieldName])){
							
								//replace select_yesno values with yes/no
								if($formFields[$fieldName]['form_type'] == 'select_yesno'){
									
									$highLightInList = (isset($formFields[$fieldName]['highlight_in_list'])) ? $formFields[$fieldName]['highlight_in_list'] : 'no' ;
									
									if($highLightInList == 'yes'){
										
										if ($item[$fieldName] == 1){
											$cellValue = '<span class="label label-green">Yes</span>';
										} else {
											$cellValue = '<span class="label label-red">No</span>';
										}
										
									} else {
										
										if ($item[$fieldName] == 1){
											$cellValue = 'Yes';
										} else {
											$cellValue = 'No';
										}
										
									}
									
								} else if($formFields[$fieldName]['form_type'] == 'select'){
									
									$itemsHere = \LecAdmin\Form::loadOptionsFromDbArray($this->DBH, ['id', $formFields[$fieldName]['select_field']], $formFields[$fieldName]['select_table']);
									$item[$fieldName] = $itemsHere[$item[$fieldName]];
									
								}
								
							}
							
							//catch anything not caught above as default value of item field.
							$cellValue = ($cellValue === '') ? htmlentities($item[$fieldName]) : $cellValue ;
							 
							?><td><a href="<?php echo '/lec-admin/object?ob='.$objectLoaded['id'].'&edit='.$item['id']; ?>"><?php echo $cellValue;?></a></td><?php
						}
					
					?></tr><?php
					
				}
							
			?></tbody></table><?php
		
		if ($objectLoaded['deletions'] === 0){
			?><p class="tools-alert tools-alert-yellow">Deletions Disabled for <?php echo $objectLoaded['name']; ?></p><?php
		} 
			
	}

	echo \LecAdmin\Form::closeForm();

	$pagination = new \LecAdmin\pagination($itemCount);
	
	?><p style="text-align:center;" class="end"><?php echo $itemCount; ?> total entries</p><br/>
	
	<?php
	//include form
	if(file_exists(DOC_ROOT.'/view/lec-admin/template/includes/object/plugin/'.$objectLoaded['include_file_list']) && $objectLoaded['include_file_list'] != ''){
		include(DOC_ROOT.'/view/lec-admin/template/includes/object/plugin/'.$objectLoaded['include_file_list']);
	}
