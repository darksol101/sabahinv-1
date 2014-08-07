<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<style type="text/css">
#searchtxt{ width:80%!important}
.toolbar1 table td{ padding: 0px 0px!important;}
</style>
<div class="toolbar1">
<form onsubmit="return false">
<table width="100%">
	<col width="30%" /><col width="20%" /><!-- <col width="20%" /> --><col />
	<tr>
    	<td><?php echo $this->lang->line('search');?> <input type="text" name="searchtxt" id="searchtxt" value="" class="text-input" onKeydown="Javascript: if (event.keyCode==13){ showstockList();}" /></td>
        <td><?php echo $servicecenters;?></td>
    <!--     <td><?php echo $company_options;?></td> -->
        <td>
        <img src="<?php echo base_url();?>assets/style/img/icons/search.gif"	class="searchbtn" onclick="showstockList();" /> 
        <span class="message"><span class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span>
        </td>
        <td></td>
    </tr>
</table>

</form>
</div>

<div id="stocklist" style="margin-top:30px;">
    
</div>