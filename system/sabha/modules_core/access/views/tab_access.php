<div><?php echo $this->load->view('addaccess');?></div>
<div class="toolbar1">
<form onsubmit="return false">Search <input type="text" name="searchtxt"
	id="searchtxt" class="text-input" value=""
	onKeydown="Javascript: if (event.keyCode==13){ $('#currentpage').val(0);showTable();}" />
<img src="<?php echo base_url();?>assets/style/img/icons/search.gif"
	class="searchbtn"
	onclick="javascript:$('#currentpage').val(0);showTable();" /> <span
	class="message"><span class="message_text"></span></span>
    <span
	class="loading"><?php echo icon("loading","Loading","gif","icon");?></span></form>
</div>
<div id="accesslist"
	style="display: none;"></div>
