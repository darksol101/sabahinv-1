<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
function createPageLinks($page,$limit,$total){
	$cnt = ceil($total/$limit);
	echo '<div class="pagination">';	
	echo '<span class="prev">&laquo;</span>';
		for($i=1;$i<=$cnt;$i++){
			echo '<span class="page" id="page_'.$i.'">'.$i.'</span>';
		}
	echo '<span class="next">&raquo;</span>';
	echo '</div>';
}