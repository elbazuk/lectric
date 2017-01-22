
	<footer class="clear">
	</footer>
	
	<script src="/view/<?php echo $this->_fileDirectory; ?>/js/script.js"></script>
	
	<?php  if (file_exists(DOC_ROOT.'/view/'.$this->_fileDirectory.'/js/'.$this->page['js']) && trim($this->page['js']) !== '') {include (DOC_ROOT.'/view/'.$this->_fileDirectory.'/js/'.$this->page['js']); }?>
     
 </section><!--body wrapper-->

</body>
</html>
