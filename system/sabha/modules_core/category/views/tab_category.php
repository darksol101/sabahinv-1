<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<div><?php echo $this->load->view('category/addcategory');?></div>
<div class="toolbar1">
<form onsubmit="return false">
  Search
  <input type="text" name="searchtxt" class="text-input" id="searchtxt" value="" onKeydown="Javascript: if (event.keyCode==13) showCategoryList();" />
  <img src="<?php echo base_url();?>assets/style/img/icons/search.gif" class="searchbtn"  onclick="showCategoryList();" /> <span class="message"><span class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span></div>
</form>
<div id="categorylist" style="width:100%;"></div>
