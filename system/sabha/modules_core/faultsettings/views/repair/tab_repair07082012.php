<div><?php echo $this->load->view('faultsettings/repair/addrepair');?></div>
<style>
#main-content table.toolbox th,#main-content table.toolbox td {
    padding: 0px 1px!important;
}
.toolbox select{
	width:100%!important;
}
</style>
<div class="toolbar1">
  <form onsubmit="return false">
  <table class="toolbox" width="100%" cellpadding="0" cellspacing="0">
  	<col width="5%" /><col width="10%" /><col width="20%" /><col width="20%" /><col  width="3%" /><col width="40%" />
    <tr>
    	<td valign="bottom"><label>Search</label></td>
    	<td><input style="width:150px;" type="text" name="searchtxt" id="searchtxt" class="text-input" value="" onKeydown="Javascript: if (event.keyCode==13) {$('#currentpage').val(0);showrepairlist();}" /></td>
    	<td><?php echo $brand_search;?></td>
        <td><span id="product_box_search"><?php echo $product_search;?></span></td>
        <td valign="baseline"><img src="<?php echo base_url();?>assets/style/img/icons/search.gif" class="searchbtn" onclick="javascript:$('#currentpage').val(0);showrepairlist();" /></td>
        <td><span class="message"><span class="message_text"></span></span> <span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span></td>
    </tr>
  </table>
</form>
</div>
<div id="repairlist" style="display:none;"></div>