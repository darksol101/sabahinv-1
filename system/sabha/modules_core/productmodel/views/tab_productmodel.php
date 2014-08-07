<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>
<div><?php echo $this->load->view('productmodel/addmodel');?></div>
<div class="toolbar1">
<form onsubmit="return false">
  Search
  <input type="text" name="pmsearchtxt" id="pmsearchtxt" class="text-input" value="" onKeydown="Javascript: if (event.keyCode==13) $('#currentpage').val(0);showProductmodelList();" />
  <img src="<?php echo base_url();?>assets/style/img/icons/search.gif" class="searchbtn"  onclick="javascript:$('#currentpage').val(0);showProductmodelList();" /> <span class="message"><span class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span></div>
</form>  
<div id="productmodellist" style="display:none; width:100%;"></div>
