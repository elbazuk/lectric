
	</div><!--admin page wrapper-->

	<footer class="clear end">
		<ul class="blocks-5 end">
			<li class="text-centered  end">Powered by: </li>
			<li class="text-centered  end"><i class="fa fa-circle-o"></i> imperavi kube</li>
			<li class="text-centered end"><i class="fa fa-flag"></i> Font Awsome</li>
			<li class="text-centered end"><img src="/view/lec-admin/img/tinymce.png" alt="" style="height:38px;"></li>
			<li class="text-centered end"><i class="fa fa-bolt"></i> Lectric</li>
		</ul>
	</footer>
	
	<script src="<?php echo $this->_jsLocalDir; ?>/script.js"></script>
	
	<?php  if (file_exists(DOC_ROOT.$this->_jsLocalDir.'/'.$this->page['js']) && trim($this->page['js']) !== '') {include (DOC_ROOT.$this->_jsLocalDir.'/'.$this->page['js']); }?>

</body>
</html>
