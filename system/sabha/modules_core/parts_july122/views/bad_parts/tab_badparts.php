<div class="toolbar1">
  <form onsubmit="return false">
    Search
    <input type="text" name="searchtxt"	id="searchtxt" class="text-input" value=""	onKeydown="Javascript: if (event.keyCode==13){ $('#currentpage').val(0);showBadPartsList();}" />
    <img src="<?php echo base_url();?>assets/style/img/icons/search.gif" class="searchbtn" onclick="javascript:$('#currentpage').val(0);showBadPartsList();" /> <span class="message"><span class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span>
  </form>
</div>
<div id="badpartlist" style="display: none;"></div>
<input type="hidden" name="currentpage" id="currentpage" value="0" />