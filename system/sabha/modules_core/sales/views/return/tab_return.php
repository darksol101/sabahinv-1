<script type="text/javascript">
$(document).ready(function(){
	showSalesReturnList();							 
});
</script>
<div class="toolbar1">
<form onsubmit="return false">Search <input type="text" name="searchtxt"
	id="searchtxt" class="text-input" value=""
	onKeydown="Javascript: if (event.keyCode==13){ $('#currentpage').val(0);showSalesReturnList();}" />
<img src="<?php echo base_url();?>assets/style/img/icons/search.gif"
	class="searchbtn" onclick="javascript:$('#currentpage').val(0);showSalesReturnList();" /> <span
	class="message"><span class="message_text"></span></span></form>
</div>
<div id="salesreturnlist" style="display: none;"></div>
