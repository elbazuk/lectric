<!--Main css stylesheet-->
<link rel="stylesheet" href="/view/lec-admin/css/style.css">
<script src="/view/lec-admin/js/plugins.js"></script>
<style>
	<?php $this->getCSS(); ?>
</style>
<div class="page">
<div class="fm_wrapper">

	<!--main record of directory-->
	<input type="hidden" name="fm_global_dir" id="fm_global_dir" value="<?php echo $directory; ?>" /> 

	<?php include(DOC_ROOT.$this->_filemanager_top_bar_template); ?>

	<?php $this->loadDirectoryContentsHTML($directory); ?>
	
	<div class="fm_footer">
		<div class="fm_message"></div>
		Allowed File Types: 
			<?php 
				foreach($this->_allowed_file_types as $ext => $val){
					echo $ext.' ';
				}
			?>
	</div>

</div><!--fm_wrapper-->
</div>

<?php $this->getJS(); ?>
