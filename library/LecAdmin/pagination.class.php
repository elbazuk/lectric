<?php
namespace LecAdmin; 

/**
* Simple pagination
*
* A generator for Pagination
*
* @package    lectricFence
* @author     Elliott Barratt
* @copyright  Elliott Barratt, all rights reserved.
*
*/ 
class pagination 
{

    /**
     * Construct to start output of pagination html
     * 
     * @param int $itemcount  
     * 
     * @return void
     */
		public function __construct(int $itemcount = 1)
		{
		
			if (!defined('PER_PAGE_FRONT')){
				define('PER_PAGE_FRONT', 10);
			}
			
			if (!defined('PAG_PAGE')){
				define('PAG_PAGE', 1);
			}
			
			?>
				<ul class="pagination">
					<?php $this->get_pages($itemcount, PAG_PAGE);?>
				</ul>
			<?php
			
		}
		
    /**
     * Get the numbered pages as html links.
     * 
     * @param int $itemcount 
     * @param int $page 
     * 
     * @return void
     */
		private function get_pages(int $itemcount,  int $page): void
		{
			
			$number_of_pages = ($itemcount / PER_PAGE_FRONT);
			
			if ($number_of_pages > 1){
				
				$getString = '';
				foreach($_GET as $key => $val){
				
					if ($key === 'page'){
						continue;	
					} else {
						$getString .= '&'.$key.'='.$val;	
					}
					
				}
				
				$page_loop = 0;
				while ($page_loop < ceil($number_of_pages)){

					if (($page_loop+1) == $page){
					   $here = true;
					} else {
					$here = false;
					}
					
					if ($page_loop == 0){
						if ($here === true){
							?><li><span><?php echo ($page_loop+1);?></span></li><?php
						} else {
							?><li><a class="" href="?<?php echo $getString; ?>"><?php echo ($page_loop+1);?></a></li><?php
						}
						
					} else {
						if ($here === true){
							?><li><span><?php echo ($page_loop+1)?></span></li><?php
						} else {
							?><li><a class="pagination_link" href="?page=<?php echo ($page_loop+1);?><?php echo $getString; ?>"><?php echo ($page_loop+1);?></a></li><?php
						}
					}
					
					$page_loop++;
				}
			}
			
			return;
			
		}
	
}
