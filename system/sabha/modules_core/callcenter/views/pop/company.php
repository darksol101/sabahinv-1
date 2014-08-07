<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<style type="text/css">
#facebox .footer {visibility: visible;}
#cplist tbody tr td.tdcls_2,#cplist tbody tr td.tdcls_3,#cplist tbody tr td.tdcls_3 input, .same_row input{ text-align:center;}
#cplist tbody tr:nth-child(even){ background:none repeat scroll 0 0 #F2F9FC!important;}
#cplist tbody tr:hover{ background: none repeat scroll 0 0 #FFF5E7;}
a.addpart,a.deletepart,a.editpart{ cursor:pointer}
#facebox .content table.tblgrid td{ padding:2px 5px;}
#facebox .content table td:last{ color:#F00!important;}
#facebox .content table td a img{ margin-left:7px;}
#facebox .content table td input[readonly=readonly]{
	background:none repeat scroll 0 0 #F7F7F7;
}
#facebox .content table td span{ font-weight:bold;color:#060;}
#facebox .content table td {padding: 0px;}
.toolbar1 { padding:1px 10px;}
#facebox a.iconimg img{	margin-bottom:-3px!important;}
</style>
<script type="text/javascript">
$(document).ready(function(){
	getcompanylist();
});
</script>
<div style="width:900px;">
<div class="toolbar1">
<form onsubmit="return false">
<table>
	<tr>
    	<td>Search : <input type="text" name="searchtxt" id="searchtxt" value="" class="text-input" onKeydown="Javascript: if (event.keyCode==13) { getcompanylist(); }"/></td>
        <td> <img
	src="<?php echo base_url();?>assets/style/img/icons/search.gif"	class="searchbtn" onclick="getcompanylist();" /> <span class="message"><span
	class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span></td>
    </tr>
</table>
</form>
</div>
<div id="getcompanylist" style="display:none"></div>
<input type="hidden" name="currentpage" id="currentpage" value="0" />

</div>