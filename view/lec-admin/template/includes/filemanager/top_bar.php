<div class="fm_top_bar forms">
	
	<div id="fm_progress">
		<div class="fm_bar" style="width: 0%;"></div>
	</div>
	
	<form class="fm_upload_file_form" method="post" action="<?php echo $this->_ajax_file; ?>" enctype="multipart/form-data">
		<table class="upload_file_table">
			<tr>
				<td><input id="fm_upload_file" name="fm_upload_file" type="file" value="" placeholder="" data-url="<?php echo $this->_ajax_file; ?>" style="display:inline" /><i class="fa fa-times fa-lg fa-fw fm_pointer fm_top_bar_slide_up" data-elem="fm_upload_file_form"></i></td>
			</tr>
		</table>
	</form>
	
	<div class="fm_new_folder_form">
		<table class="new_folder_table width-50">
			<tr>
				<td><input id="fm_new_folder_name" type="text" value="" placeholder="New Folder" class="width-100" /></td>
				<td><select id="fm_new_folder_universal" style="padding:3px;" class="width-100" /><option vlaue="no">Hidden (Add folder to user to see).</option><option value="yes">Universal</option></select></td>
				<td><i class="fa fa-check fa-lg fa-fw fm_pointer fm_add_folder_submit" data-elem="fm_new_folder_form"></i></td>
				<td><i class="fa fa-times fa-lg fa-fw fm_pointer fm_top_bar_slide_up" data-elem="fm_new_folder_form"></i></td>
			</tr>
		</table>
	</div>

	<i class="fa fa-fw fa-lg fa-upload fm_pointer fm_top_bar_slide_down tooltip_open" title="Upload File"  data-elem="fm_upload_file_form"></i>
	
	&nbsp;&nbsp;&nbsp;&nbsp;
	
	<span class="fm_top_bar_slide_down fm_pointer fm_folder_add_slider tooltip_open" title="New Folder" data-elem="fm_new_folder_form"><i class="fa fa-plus"></i><i class="fa fa-lg fa-folder"></i></span>
	
	&nbsp;&nbsp;&nbsp;&nbsp;
	
	<!--<span class="fm_add_folder fm_pointer"><i class="fa fa-lg fa-sort-amount-asc"></i><i class="fa fa-caret-down"></i></span>-->
		
	<div class="fm_breadcrumb">
	
		
		
		<span class="fm_breadcrumb_additonal"><?php echo $this->getBreadcrumb($directory); ?></span>
	
	</div>

</div>
