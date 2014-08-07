<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php
$this->load->view('customers/script');
?>
<div style="width:650px;">
<div class="toolbar1">
  <form onsubmit="return false">Search
  <input type="text" name="searchtxt" id="searchtxt" class="text-input" value="" onKeydown="Javascript: if (event.keyCode==13) $('#currentpage').val(0);showCustomerList();" />
  <img src="<?php echo base_url();?>assets/style/img/icons/search.gif" style="margin-bottom: -8px;" class="searchbtn"  onclick="javascript:$('#currentpage').val(0);showCustomerList();" /> <span class="message"><span class="message_text"></span></span><input type="hidden" name="currentpage" id="currentpage" value="0"  /></form></div>
<div id="listcustomers"></div>
</div>