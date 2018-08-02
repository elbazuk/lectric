<br/>

<h1><i class="fa <?php echo $objectLoaded['icon'] ;?>"></i> <?php echo $objectLoaded['name'];?></h1>

<br/>

<div class="units-row end">

	<div class="unit-50">
	
		<a href="/lec-admin/object?ob=<?php echo $objectLoaded['id']; ?>&new=yes" class="btn btn-blue add_new"><i class="fa <?php echo $objectLoaded['icon'] ;?>"></i> Add New <?php echo $objectLoaded['s_word'];?></a>
		<button type="button" class="btn btn-red  delete_button"><i class="fa fa-trash-o"></i> Delete Selected</button>
		<button type="button"  class="btn btn-blue  duplicate_button"><i class="fa fa-copy"></i> Duplicate Selected</button>
		
	</div>
	
	<div class="unit-50">
	
		<?php
		if ($objectLoaded['search'] === 1){
			echo \lectricFence\Form::startForm('search_form', 'post', '/lec-admin/object?ob='.$objectLoaded['id'].'&list=yes', ' class="end" enctype="multipart/form-data" '); ?>
			<p class="text-right end" style="line-height:35px;">
				<?php $s = (isset($_POST['search'])) ? $_POST['search'] : '';?>
				<a href="/lec-admin/object?ob=<?php echo $objectLoaded['id']; ?>&list=yes" class="right" style="display:block;padding-left:20px;">Clear X </a>
				<?php echo \lectricFence\Form::makeInput('search', 'text', 'search', $s, 'Search', ' class="input-search right"  ');?>
				<button type="submit" style="display:none;">Submit</button>
			</p>
			<?php echo \lectricFence\Form::closeForm();
		} else {
		} ?>
	
	</div>
	
</div>

<br/>

<?php

	if (($lecMessages = \Lectric\controller::getSessionMessages()) !== null){
		
		?><div class="tools-alert tools-alert-yellow"><?php
			foreach ($lecMessages as $msg){
				echo $msg.'<br/>';
			}	
			\Lectric\controller::clearSessionMessages();
		?></div><?php
		
	}
	
	$itemCount = $this->countObjectItems($objectLoaded['table']);
	$pagination = new \lectricFence\pagination($itemCount);
	$fieldArray = explode(',',$objectLoaded['table_fields']);
	
	echo \lectricFence\Form::startForm('adminTable', 'post', '/lec-admin/object?ob='.$objectLoaded['id'].'&list=yes', ' enctype="multipart/form-data" ');

	?><table id="admin_list_table" class="table-hovered width-100" style="background:white;"><?php
		
	try {
		
		//selection limits based on pagination
			$limitInj = 'LIMIT '.PAG_START.','.PER_PAGE_FRONT;
			$limitArray = [PAG_START, PER_PAGE_FRONT];
		
		//is there a search?
			if (isset($_POST['search'])){
				$sqlInj = str_replace('|||search|||', $_POST['search'], $objectLoaded['search_inj']);
			} else {
				$sqlInj = '';	
			}
	
		//load normal table, or searched results table. 
			if ($sqlInj == ''){
				$fieldArrayHere = $fieldArray;
				$fieldArrayHere[] = 'id';
				$this->setSelectFields($fieldArrayHere);
				$this->setOrderBy(['id'=>'DESC']);
				$this->setLimit($limitArray);
				$loadedItems = $this->selStrict($objectLoaded['table'], 'MULTI', 'NOT_TABLED');
			} else {
				$loadedItems = $this->select('SELECT `id`,'.$objectLoaded['table_fields'].' FROM `'.trim($objectLoaded['table'], '`').'` '.$sqlInj.' '.$limitInj, 'MULTI', 'NOT_STRICT', 'NOT_ECHO', null, 'NOT_TABLED');
			}
			
	} catch (SQLException $e){
		if(DEBUG){
			echo 'Failed to load items from object table for lec admin list: '.$e->getMessage();
		}
	}
	
	if ($loadedItems == null){
		?><tr><td>There are no items of this type.</td></tr></table><?php
	} else {
		
		?><thead><tr><th></th><?php
		
		foreach ($fieldArray as $value){
			
			$value = str_replace('`', '',  $value);
				
			?><th><?php echo ucwords(str_replace('`', '', str_replace('_',' ',$value))); ?></th><?php
			
		}
		
		?></tr></thead><tbody><?php
				
		foreach ($loadedItems as $item){
		
			?><tr><?php
			
			if (in_array($item['id'],json_decode($objectLoaded['nodelete'], true)) || $objectLoaded['deletions'] === 0){
				?><td style="width:10px;"><input type="checkbox" disabled="disabled"/></td><?php
			} else {
			
				?><td style="width:10px;"><input type="checkbox" id="admin_table_item_check_<?php echo $item['id']; ?>" name="admin_table_item_check_<?php echo $item['id']; ?>" class="admin_table_item_check"/></td><?php
			}
			
				foreach ($fieldArray as $fieldName){
					
					$fieldName = str_replace('`', '',  $fieldName);
					
					if (preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $item[$fieldName]) || preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/', $item[$fieldName])){
						if ($item[$fieldName] == '0000-00-00'){
							$item[$fieldName] = 'Not Set';
						} else {
							$dateTime = new DateTime(date($item[$fieldName]));
							$item[$fieldName] = $dateTime->format('d/m/Y');
						}
					}
					 
					if ($fieldName == 'live'){
						if ($item[$fieldName] == 1){
							$item[$fieldName] = 'Yes';
						} else {
							$item[$fieldName] = 'No';
						}
					}
					 
					?><td><a href="<?php echo '/lec-admin/object?ob='.$objectLoaded['id'].'&edit='.$item['id']; ?>"><?php echo htmlentities($item[$fieldName]);?></a></td><?php
				}
			
			?></tr><?php
		}
						
		?></tbody></table><?php
		
		if ($objectLoaded['deletions'] === 0){
			?><p class="tools-alert tools-alert-yellow">Deletions Disabled for <?php echo $objectLoaded['name']; ?></p><?php
		} 
			
	}

	echo \lectricFence\Form::closeForm();

	$pagination = new \lectricFence\pagination($itemCount);
	
	?><p style="text-align:center;" class="end"><?php echo $itemCount; ?> total entries</p><br/>
	
	
	<?php
	//include form
	if(file_exists(DOC_ROOT.'/view/lec-admin/template/includes/object/plugin/'.$objectLoaded['include_file_list']) && $objectLoaded['include_file_list'] != ''){
		include(DOC_ROOT.'/view/lec-admin/template/includes/object/plugin/'.$objectLoaded['include_file_list']);
	}
