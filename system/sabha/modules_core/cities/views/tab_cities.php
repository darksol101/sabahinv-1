<div><?php echo $this->load->view('cities/addcities');?></div>
<style>
#zone_search,#district_search{
	width:12%!important;
}
#sc_search{
	width:20%!important;
}
#searchcitytxt{
	width:15%;
}
</style>
<div class="toolbar1">
  <form onsubmit="return false">Search
  <input type="text" name="searchcitytxt" id="searchcitytxt" class="text-input" value="" onKeydown="Javascript: if (event.keyCode==13){ $('#currentpage').val(0);showCityTable();}" /><span>&nbsp;&nbsp;<?php echo $zone_search;?></span>&nbsp;&nbsp;<span id="search_district_box"><?php echo $district_search;?></span>&nbsp;<span><?php echo $servicecenter_search;?></span>
  <img src="<?php echo base_url();?>assets/style/img/icons/search.gif" class="searchbtn" onclick="javascript:$('#currentpage').val(0);showCityTable();" /> <span class="message"><span class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span></div>
</form>
<div id="citylist" style="display:none;"></div>