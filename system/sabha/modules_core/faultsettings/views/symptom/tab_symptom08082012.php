<div><?php echo $this->load->view('faultsettings/symptom/addsymptom');?></div>
<style>
#zone_search,#district_search{
	width:15%!important;
}
</style>
<div class="toolbar1">
  <form onsubmit="return false">
  <table width="100%">
  <col width="5%" /><col width="10%" /><col /><col /><col width="30%" />
  	<tr>
    	<td>Search</td>
        <td><input style="width:110px;" type="text" name="searchtxt" id="searchtxt" class="text-input" value="" onKeydown="Javascript: if (event.keyCode==13) {$('#currentpage').val(0);showsymptomlist();}" /></td>
        <td><?php echo $brand_search;?></td>
        <td><span id="product_box_search"><?php echo $product_search;?></span><img src="<?php echo base_url();?>assets/style/img/icons/search.gif" class="searchbtn" onclick="javascript:$('#currentpage').val(0);showsymptomlist();" /></td>
        <td><span class="message"><span class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span></td>
    </tr>
  </table>
</form></div>
<div id="symptomlist" style="display:none;"></div>