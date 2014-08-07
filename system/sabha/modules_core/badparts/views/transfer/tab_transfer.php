<?php $this->load->view('dashboard/system_messages');?>
<style type="text/javascript">
.loading{ display:none;}
input#from_date,input#to_date{ width:100px;}
table td img.ui-datepicker-trigger {padding-left: 5px!important;margin-top: -4px;vertical-align: middle;}
table td img.ui-datepicker-trigger{	border:red 1px solid;}
</style>
<div class="toolbar1">
<form onsubmit="return false;" id="frmpartslist">
<table width="100%">
<col width="25%" /><col width="30%" /><col width="30%" /><col width="5%" />
    <tr>
    	<td><?php echo $servicecenter_select;?></td>
        <td>From <input type="text" style="width:80px; text-align:center" class="text-input datepicker" id="from_date" /></td>
        <td>To <input type="text" style="width:80px; text-align:center" class="text-input datepicker" id="to_date" /></td>
        <td><img src="<?php echo base_url();?>assets/style/img/icons/search.gif" class="searchbtn" onclick="gettransferpartsorders();" /><span class="message"><span class="message_text"></span></span><span class="loading"><?php echo icon("loading","Loading","gif","icon");?></span></td>
    </tr>
</table>
</form>
</div>

<div id="gettransferpartlist"></div>
