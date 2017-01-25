
	<footer class="clear">
	</footer>
	
	<script src="<?php echo $this->_jsLocalDir; ?>/script.js"></script>
	
	<?php  if (file_exists(DOC_ROOT.$this->_jsLocalDir.'/'.$this->page['js']) && trim($this->page['js']) !== '') {include (DOC_ROOT.$this->_jsLocalDir.'/'.$this->page['js']); }?>
     
 </section><!--body wrapper-->

</body>
</html>
