<br/>

<h1>
	<i class="fa <?php echo $objectLoaded['icon'] ;?>"></i> <?php echo $objectLoaded['name'];?>
	- <?php echo ($new)? 'New Item' : 'Edit Item'; ?>
</h1>

<br/>

<?php $link = ($new) ? '/lec-admin/object?ob='.$objectLoaded['id'].'&list=yes' : '/lec-admin/object?ob='.$objectLoaded['id'].'&edit='.$itemLoaded['id'];

echo \lectricFence\Form::startForm($objectLoaded['table'].'_form', 'post', $link, ' class="end" enctype="multipart/form-data" '); ?>

<fieldset class="forms item_edit_fieldset end">

	<?php
	if (($lecMessages = \Lectric\controller::getSessionMessages()) !== null){
		
		?><div class="tools-alert tools-alert-yellow"><?php
			foreach ($lecMessages as $msg){
				echo $msg.'<br/>';
			}	
			\Lectric\controller::clearSessionMessages();
		?></div><?php
		
	}

		$formFields = json_decode($objectLoaded['edit_fields'], true);
		
		if ($formFields != null){
			
			foreach($formFields as $fField){
				
					$mandatory = ($fField['mandatory'] === 'yes') ? 'mandatory' : '' ;
					
					if ( $fField['form_type'] === 'select'){
						
						?><label><?php
						echo $fField['name']; echo ($fField['mandatory'] === 'yes') ? '<span class="req">*</span>' : '';
						echo \lectricFence\Form::makeSelect($fField['field'], \lectricFence\Form::loadOptionsFromDbArray($this->DBH, ['id', $fField['select_field']], $fField['select_table']), ' class="width-100 '.$fField['class_inj'].' " ' , $fField['field'], $itemLoaded[$fField['field']]);
						echo (trim($fField['help_text']) === '')? '' : '<div class="forms-desc">'.$fField['help_text'].'</div>';
						?></label><br/><?php
						
					} else if ( $fField['form_type'] === 'select_yesno'){
						
						?><label><?php
						echo $fField['name']; echo ($fField['mandatory'] === 'yes') ? '<span class="req">*</span>' : '';
						echo \lectricFence\Form::makeSelect($fField['field'], [1=>'Yes',0=>'No'], ' class="width-100 '.$fField['class_inj'].' " ' , $fField['field'], $itemLoaded[$fField['field']]);
						echo (trim($fField['help_text']) === '')? '' : '<div class="forms-desc">'.$fField['help_text'].'</div>';
						?></label><br/><?php
						
					} else if($fField['form_type'] === 'image'){
						
						if (file_exists(DOC_ROOT.$objectLoaded['img_directory'].$itemLoaded[$fField['field']]) && trim($itemLoaded[$fField['field']]) !== ''){
							
							?><p><img src="<?php echo $objectLoaded['img_directory'].$itemLoaded[$fField['field']]; ?>" alt="" style="max-height:100px;max-width:100px;"/></p><?php
							?><label><?php echo \lectricFence\Form::makeInput('deletefile_'.$fField['field'], 'checkbox', 'deletefile_'.$fField['field'], (string)$itemLoaded[$fField['field']]); ?> Delete File?</label><br/><?php
							
						} else {
							?><label><?php
							echo $fField['name'];
							echo \lectricFence\Form::makeInput($fField['field'], 'file', $fField['field'], str_replace('"', '&quot;', (string)$itemLoaded[$fField['field']]), '', ' class=" '.$fField['class_inj'].' '.$mandatory.' " ');
							echo (trim($fField['help_text']) === '')? '' : '<div class="forms-desc">'.$fField['help_text'].'</div>';
							?></label><br/><?php
						}
						
					} else {
						
						?><label><?php
						echo $fField['name']; echo ($fField['mandatory'] === 'yes') ? '<span class="req">*</span>' : '';
						$cols = ($fField['form_type'] == 'textarea') ? 'rows="15"' : '';
						echo \lectricFence\Form::makeInput($fField['field'], $fField['form_type'], $fField['field'], str_replace('"', '&quot;', (string)$itemLoaded[$fField['field']]), $fField['placeholder'], ' class="width-100 '.$fField['class_inj'].' '.$mandatory.' " '.$cols.' '); 
						echo (trim($fField['help_text']) === '')? '' : '<div class="forms-desc">'.$fField['help_text'].'</div>';						
						?></label><br/><?php
						
					}
					
				
				
				if (strpos($fField['class_inj'],'editor') !== false){
					?>
					
						<script>
							tinymce.init({
								selector:'#<?php echo $fField['field']; ?>',
								plugins: "image,link, fullscreen, code,  filemanager, lists, paste, media,  table, colorpicker, textcolor",
								image_advtab: true,
								content_css : "/view/public/css/style.css,/view/default/css/style.css",
								width : '100%',
								toolbar: " undo redo | styleselect | bold italic underline strikethrough subscript superscript forecolor backcolor | inserttable bullist numlist outdent indent | alignleft aligncenter alignright alignjustify |  media link image  | code fullscreen",
								relative_urls: true,
								menubar: false,
								remove_script_host:false,
								document_base_url : "<?php echo SITE_LINK; ?>",
								convert_urls: false,
								statusbar: false,
								height : "280",
								filemanager_title:"Filemanager" ,
								external_plugins: { "filemanager" : "/view/lec-admin/filemanager/plugin.min.js"},
								external_filemanager_path:"/view/lec-admin/filemanager/",
								filemanager_access_key:"<?php echo $_SESSION['admin_userid']; ?>",
								file_browser_callback:function(fieldName, url, objectType, w) {
									filemanager(fieldName, url, objectType, w);
								}
							});
						</script>
					
					<?php
				}
			}
			
		} else {
			
			?><p>There are no form fields to edit this item.</p><?php
			
		} 
		
		if (file_exists(DOC_ROOT.'/view/lec-admin/template/includes/object/plugin/form_'.strtolower($objectLoaded['name']).'.php')) {include (DOC_ROOT.'/view/lec-admin/template/includes/object/plugin/form_'.strtolower($objectLoaded['name']).'.php'); }
		
	?>
		
</fieldset>

<div class="saveCancel">
	<button class="btn btn-green object_form_submit" type="button" data-form="<?php echo $objectLoaded['table'].'_form'; ?>"><i class="fa fa-save"></i> Save</button>
	<a class="btn btn-red " href="/lec-admin/object?ob=<?php echo $objectLoaded['id'];?>&list=yes"><i class="fa fa-reply"></i> Go Back</a>
</div>

<?php

	if ($new){
		echo \lectricFence\Form::makeInput('new', 'hidden', 'new', 'yes');
	} else {
		echo \lectricFence\Form::makeInput('id', 'hidden', 'id', $itemLoaded['id']);
	}

	echo \lectricFence\Form::closeForm();