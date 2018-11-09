<?php
	//var_dump($dirContents);
?>

<div class="fm_content">

	<?php echo $this->getBackButton($directory);?>
	
	<?php
		if (empty($dirContents['files']) && empty($dirContents['folders'])){
			
			?><br/><p class="fm_no_contents">There are no files or folders in this directory.</p><?php
			
		} else {
			
			?>
			
				<table class="fm_contents_table">
					<thead>
						<tr>
							<th></th>
							<th width="78%">Name</th>
							<th>Size</th>
							<th  width="10%">Actions</th>
						</tr>
					</thead>
					<tbody>
			<?php
			
				if (!empty($dirContents['folders'])){
					
					$folderCounter = 1;
					foreach($dirContents['folders'] as $folder){
						
						?>
							<tr class=" " >
								<td class="fm_change_directory fm_pointer " data-dir="<?php echo urlencode($directory.$folder.'/'); ?>"><i class="fa fa-fw fa-lg fa-folder " ></i></td>
								<td class="fm_change_directory fm_pointer" data-dir="<?php echo  urlencode($directory.$folder.'/'); ?>"><?php echo $folder; ?></td>
								<td>-</td>
								<td class="fm_actions_cell">
									<div class="fm_delete_folder" id="fm_delete_folder_<?php echo $folderCounter; ?>" >
										<i class="fa fa-check fa-fw fm_pointer fm_delete_folder_submit"  data-folder="<?php echo urlencode($directory.$folder.'/'); ?>"></i>&nbsp;&nbsp;&nbsp;
										<i class="fa fa-times fa-fw fm_hide_elem fm_pointer" data-elem="fm_delete_folder_<?php echo $folderCounter; ?>" ></i>
									</div>
									<i class="fa fa-trash-o fa-fw fm_pointer fm_show_elem" data-elem="fm_delete_folder_<?php echo $folderCounter; ?>"></i>&nbsp;&nbsp;&nbsp;
								</td>
							</tr>
						<?php
						$folderCounter++;
					}
					
				}
				
				if (!empty($dirContents['files'])){
					
					$fileCounter = 1;
					foreach($dirContents['files'] as $file){
						
						?>
							<tr class="fm_click_file" >
								<td><i class="fa fa-fw fa-lg fa-<?php echo $this->getFAIconFile($file); ?>"></i></td>
								<td class="fm_select_file fm_pointer" data-file="<?php echo str_replace(DOC_ROOT,'',$directory.$file); ?>">
									<?php echo $file; ?>
									<form target="_blank" class="fm_file_click_form" method="post" action="<?php echo $this->_download_serve_file; ?>" id="file_click_form_<?php echo $fileCounter; ?>">
										<input type="hidden" name="file" value="<?php echo $directory.$file; ?>" />
										<input type="hidden" name="filename" value="<?php echo $file; ?>" />
									</form>
								</td>
								<td><?php echo $this->fileSizeHuman(filesize($directory.$file)); ?></td>
								
								<td class="fm_actions_cell">
										<div class="fm_delete_file" id="fm_delete_file_<?php echo $fileCounter; ?>" >
											<i class="fa fa-check fa-fw fm_pointer fm_delete_file_submit"  data-file="<?php echo $directory.$file; ?>"></i>&nbsp;&nbsp;&nbsp;
											<i class="fa fa-times fa-fw fm_hide_elem fm_pointer" data-elem="fm_delete_file_<?php echo $fileCounter; ?>" ></i>
										</div>
										<i class="fa fa-trash-o fa-fw fm_pointer fm_show_elem" data-elem="fm_delete_file_<?php echo $fileCounter; ?>"></i>&nbsp;&nbsp;&nbsp;
									
									<i class="fa fa-download fa-fw fm_click_file fm_pointer" data-file="<?php echo $directory.$file; ?>" data-form="file_click_form_<?php echo $fileCounter; ?>"></i>
									<?php echo ($this->isImage($file)) ? '<i class="fa fa-eye fa-fw fm_file_preview fm_pointer" data-file-row="'.$fileCounter.'"></i>' : ''; ?>
								</td>
							</tr>
							
							<?php
								if ($this->isImage($file)){
									
									?>
									
										<tr style="display:none;" class="file_preview_tr_<?php echo $fileCounter; ?>">
											<td colspan="4"><img src="<?php echo $this->_download_serve_file;?>?file=<?php echo str_replace(DOC_ROOT,'',$directory).$file; ?>" alt="" /></td>
										</tr>
									
									<?php
									
								}
							?>
							
						<?php
						$fileCounter++;
					}
					
				}
			
			?></tbody></table><?php
			
		}
	?>
	
</div>
