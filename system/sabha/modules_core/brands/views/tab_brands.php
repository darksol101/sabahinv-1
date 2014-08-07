<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>

<div><?php echo $this->load->view('brands/addbrand');?></div>
<div class="toolbar1">
  <form onsubmit="return false;">
    Search
      <input type="text" name="searchtxt" id="searchtxt" value="" class="text-input" onKeydown="Javascript: if (event.keyCode==13) showBrandList();" />
      <img src="<?php echo base_url();?>assets/style/img/icons/search.gif" class="searchbtn"  onclick="showBrandList();" />  
  <span class="message"><span class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span>
  </form>
</div>
<div id="brandlist" style="width:100%;"></div>
