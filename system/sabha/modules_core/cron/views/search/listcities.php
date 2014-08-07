<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php
$this->load->view('search/script_search');
?>
<style>
#ct_zone_search,#ct_district_search {
	position: relative;
}

#ct_zone_search .loading,#ct_district_search .loading {
	position: absolute;
	left: 0px;
	top: 0;
	width: 100%;
	height: 30px;
	margin: 0 auto;
	text-align: center;
}
</style>
<div style="width: 650px;">
<div class="toolbar1">
<form onsubmit="return false">
<table width="100%">
	<col width="5%" />
	<col width="30%" />
	<col width="30%" />
	<col width="30%" />
	<col width="5%" />
	<tr>
		<td>Search</td>
		<td><input type="text" name="searchtxt" id="searchtxt"
			class="text-input" value=""
			onKeydown="Javascript: if (event.keyCode==13) {$('#currentpage').val(0);showCityList();}" /></td>
		<td><span id="ct_zone_search"><?php echo $zone_search; ?></span></td>
		<td><span id="ct_district_search"><?php echo $district_search; ?></span></td>
		<td><img
			src="<?php echo base_url();?>assets/style/img/icons/search.gif"
			style="margin-bottom: -8px;" class="searchbtn"
			onclick="javascript:$('#currentpage').val(0);showCityList();" /></td>
	</tr>
</table>
<span class="message"><span class="message_text"></span></span><input
	type="hidden" name="currentpage" id="currentpage" value="0" /></form>
</div>
<div id="listcities"></div>
</div>
