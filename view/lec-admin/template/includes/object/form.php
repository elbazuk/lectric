<?php

//include form
if(file_exists(DOC_ROOT.'/view/lec-admin/template/includes/object/plugin/'.$objectLoaded['include_file_before']) && $objectLoaded['include_file_before'] != ''){
	include(DOC_ROOT.'/view/lec-admin/template/includes/object/plugin/'.$objectLoaded['include_file_before']);
}
		
?>

<h1>
	<i class="fa <?php echo $objectLoaded['icon'] ;?>"></i> <?php echo $objectLoaded['name'];?>
	- <?php echo ($new)? 'New Item' : 'Edit Item'; ?>
</h1>

<?php $link = ($new) ? '/lec-admin/object?ob='.$objectLoaded['id'].'&list=yes' : '/lec-admin/object?ob='.$objectLoaded['id'].'&edit='.$itemLoaded['id'];

echo \LecAdmin\Form::startForm($objectLoaded['table'].'_form', 'post', $link, ' class="end" enctype="multipart/form-data" '); ?>

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
			
			$halfWidthCounter = 1;
			
			$fieldCount = count($formFields);
			$fieldCounter = 1;
			foreach($formFields as $fField){
				
				if(isset($fField['half_width'])){
					if($fField['half_width'] === 'yes'){
						if($halfWidthCounter % 2 == 1){
							?><div class="units-row end"><div class="unit-50 end"><?php
						} else {
							?><div class="unit-50 end"><?php
						}
					}
				}
				
				$fField['read_only'] = (isset($fField['read_only'])) ? $fField['read_only'] : '';
				$readOnly = ($fField['read_only'] === 'yes') ? 'readonly="readonly"' :'' ;
				$fField['populate'] = (isset($fField['populate'])) ? $fField['populate'] : 'yes';
				
				$itemLoaded[$fField['field']] = ($fField['populate'] === 'no') ? '' : $itemLoaded[$fField['field']];
			
				$mandatory = ($fField['mandatory'] === 'yes') ? 'mandatory' : '' ;
				
				if ( $fField['form_type'] === 'select'){
					
					?><label><?php
					echo $fField['name']; echo ($fField['mandatory'] === 'yes') ? '<span class="req">*</span>' : '';
					echo \LecAdmin\Form::makeSelect($fField['field'], \LecAdmin\Form::loadOptionsFromDbArray($this->DBH, ['id', $fField['select_field']], $fField['select_table']), ' class="width-100 '.$fField['class_inj'].' " ' , $fField['field'], $itemLoaded[$fField['field']]);
					echo (trim($fField['help_text']) === '')? '' : '<div class="forms-desc">'.$fField['help_text'].'</div>';
					?></label><br/><?php
					
				} else if ( $fField['form_type'] === 'select_yesno'){
					
					?><label><?php
					echo $fField['name']; echo ($fField['mandatory'] === 'yes') ? '<span class="req">*</span>' : '';
					echo \LecAdmin\Form::makeSelect($fField['field'], [1=>'Yes',0=>'No'], ' class="width-100 '.$fField['class_inj'].' " ' , $fField['field'], $itemLoaded[$fField['field']]);
					echo (trim($fField['help_text']) === '')? '' : '<div class="forms-desc">'.$fField['help_text'].'</div>';
					?></label><br/><?php
					
				} else if($fField['form_type'] === 'image'){
					
					if (file_exists(DOC_ROOT.$objectLoaded['img_directory'].$itemLoaded[$fField['field']]) && trim($itemLoaded[$fField['field']]) !== ''){
						
						?><p><img src="<?php echo $objectLoaded['img_directory'].$itemLoaded[$fField['field']]; ?>" alt="" style="max-height:100px;max-width:100px;"/></p><?php
						?><label><?php echo \LecAdmin\Form::makeInput('deletefile_'.$fField['field'], 'checkbox', 'deletefile_'.$fField['field'], (string)$itemLoaded[$fField['field']]); ?> Delete File?</label><br/><?php
						
					} else {
						?><label><?php
						echo $fField['name'];
						echo \LecAdmin\Form::makeInput($fField['field'], 'file', $fField['field'], str_replace('"', '&quot;', (string)$itemLoaded[$fField['field']]), '', ' class=" '.$fField['class_inj'].' '.$mandatory.' " ');
						echo (trim($fField['help_text']) === '')? '' : '<div class="forms-desc">'.$fField['help_text'].'</div>';
						?></label><br/><?php
					}
					
				} else {
					
					?><label><?php
					
						echo $fField['name'];
						echo ($fField['mandatory'] === 'yes') ? '<span class="req">*</span>' : '';
						
						//add image in if filemanager link box, and image
						if (strpos($fField['class_inj'],'filemanager') !== false){
							if(!$new && trim($itemLoaded[$fField['field']]) !== ''){
								
								$bits = explode('.',trim($itemLoaded[$fField['field']]));
								$ext = end($bits);
								$allowedExts = ['png', 'jpg', 'jpeg', 'gif'];
								if(in_array($ext, $allowedExts)){
									?><br/><img src="<?php echo (string)$itemLoaded[$fField['field']]; ?>" style="max-width:300px;padding:0.3em;"><?php
								}
							}
						}
						
						$cols = ($fField['form_type'] == 'textarea') ? 'rows="15"' : '';
						
						//add filenmanager button or normal
						if (strpos($fField['class_inj'],'filemanager') !== false){
							
							?>
								<div class="input-groups">
									<span class="input-prepend" style="padding:0;border:0;"><a href="/do/response/filemanager/view/" class="btn btn-green filemanager_button" type="button" data-field="<?php echo $fField['field']; ?>"><i class="fa fa-fw fa-file-o"></i> Open Filemanager</a></span>
									<?php echo \LecAdmin\Form::makeInput($fField['field'], $fField['form_type'], $fField['field'], str_replace('"', '&quot;', (string)$itemLoaded[$fField['field']]), $fField['placeholder'], ' '.$readOnly.' class="width-100 '.$fField['class_inj'].' '.$mandatory.' " '); ?>
								</div>
							<?php
							
						} else {
							//normal input!
							echo \LecAdmin\Form::makeInput($fField['field'], $fField['form_type'], $fField['field'], str_replace('"', '&quot;', (string)$itemLoaded[$fField['field']]), $fField['placeholder'], ' '.$readOnly.' class="width-100 '.$fField['class_inj'].' '.$mandatory.' " '.$cols.' '); 
							//for tinmymce
							 
						}
						
						//help text
						echo (trim($fField['help_text']) === '')? '' : '<div class="forms-desc">'.$fField['help_text'].'</div>';
					
					?></label><br/><?php
					
				}
				
				
				if (strpos($fField['class_inj'],'editor') !== false){
					
					if(ALLOW_CODE_IN_EDITOR === true) {
						?><div class="<?php echo $fField['field'].'_pre'; ?>" style="display:none;"><?php echo (string)$itemLoaded[$fField['field']]; ?></div><?php
					}
					
					?>
					
						<script>
							tinymce.init({
								selector:'#<?php echo $fField['field']; ?>',
								plugins: "image,link, fullscreen, <?php if (ALLOW_CODE_IN_EDITOR === true) { ?>code, codesample, <?php } ?>  filemanager, lists, paste, media,  table, colorpicker, textcolor, fontawesome",
								image_advtab: true,
								content_css : "https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css<?php echo EDITOR_STYLESHEETS; ?>", 
								width : '100%',
								toolbar: " undo redo | styleselect | bold italic underline strikethrough subscript superscript forecolor backcolor | inserttable bullist numlist outdent indent | alignleft aligncenter alignright alignjustify |  media link image  | <?php if (ALLOW_CODE_IN_EDITOR === true) { ?>code, codesample,<?php } ?> fullscreen | fontawesome",
								relative_urls: true,
								menubar: false,
								remove_script_host:false,
								document_base_url : "<?php echo SITE_LINK; ?>",
								convert_urls: false,
								statusbar: false,
								height : "280",
								filemanager_title:"Filemanager" ,
								external_plugins: { "filemanager" : "/do/response/filemanager/plugin/", "fontawesome" : "/view/lec-admin/js/plugins/fontawesome/plugin.min.js"},
								<?php if (ALLOW_CODE_IN_EDITOR === true) { ?>
									valid_elements : '+*[*]',
									setup: function (editor) {
										editor.on('init', function () {
										var theContent = $('.<?php echo $fField['field'].'_pre'; ?>').html();
											console.log(theContent);
											this.setContent(theContent);
										});
									} 
								<?php } ?>
							});
						</script>
					
					<?php
				}
				
				if(isset($fField['half_width'])){
					if($fField['half_width'] === 'yes'){
						
						//catch if last field is first of two half width fields...
						if($fieldCounter == $fieldCount){
							?></div></div><?php
							
						//first of two half width's
						} else if($halfWidthCounter === 1){
							?></div><?php
							$halfWidthCounter = 2;
							
						//second of two
						} else  {
							?></div></div><?php
							$halfWidthCounter = 1;
						}
						
					}
				} else if($halfWidthCounter == 2){
					//close the units-row div off....
					?></div><?php
				}
				
				$fieldCounter++;
				
			}
			
		} else {
			
			?><p>There are no form fields to edit this item.</p><?php
			
		} 
		
		//include form
		if(file_exists(DOC_ROOT.'/view/lec-admin/template/includes/object/plugin/'.$objectLoaded['include_file_after']) && $objectLoaded['include_file_after'] != ''){
			include(DOC_ROOT.'/view/lec-admin/template/includes/object/plugin/'.$objectLoaded['include_file_after']);
		} 
		
	?>
		
</fieldset>

<br/><br/>

<div class="saveCancel">
	<button class="btn btn-green object_form_submit" type="button" data-form="<?php echo $objectLoaded['table'].'_form'; ?>"><i class="fa fa-save"></i> Save</button>
	<a class="btn btn-red " href="/lec-admin/object?ob=<?php echo $objectLoaded['id'];?>&list=yes"><i class="fa fa-reply"></i> Go Back</a>
</div>

<?php

	if ($new){
		echo \LecAdmin\Form::makeInput('lec-admin_new', 'hidden', 'lec-admin_new', 'yes');
	} else {
		echo \LecAdmin\Form::makeInput('id', 'hidden', 'id', $itemLoaded['id']);
	}

	echo \LecAdmin\Form::closeForm().'<br/>';
	
