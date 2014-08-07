<div><?php echo $this->load->view('faultsettings/defect/adddefect');?></div>
<style type="text/css">
#main-content table.toolbox th,#main-content table.toolbox td {
    padding: 0px 1px!important;
}
.toolbox select{
	width:100%!important;
}
#symptom_search,#defect_search{
	width:80%!important;
}
form select#product_search {
    width: 100% !important;
}
</style>
<div class="toolbar1">
  <form onsubmit="return false">
  <table class="toolbox" cellpadding="0" cellspacing="0" width="100%">
  	<col width="5%" /><col width="5%" /><col /><col /><col /><col width="25%" />
    <tr>
    	<td>Search</td>
        <td><input type="text" name="searchtxt" id="searchtxt" class="text-input" value="" onKeydown="Javascript: if (event.keyCode==13) {$('#currentpage').val(0);showdefectlist();}" /></td>
        <td><?php echo $brand_search;?></td>
        <td><span id="product_box_search"><?php echo $product_search;?></span></td>
         <td><span id="symptom_box_search"><?php echo $symptom_search;?></span><img src="<?php echo base_url();?>assets/style/img/icons/search.gif" class="searchbtn" onclick="javascript:$('#currentpage').val(0);showdefectlist();" /></td>
        <td><span class="message"><span class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span></td>
    </tr>
  </table>
</form>
</div>
<div id="defectlist" style="display:none;"></div>
