<link rel="stylesheet"
	href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<link
	rel="stylesheet"
	href="<?php echo base_url();?>assets/style/css/base/jquery.ui.timepicker.css" />
<script
	src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js"
	type="text/javascript"></script>
<script
	src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js"
	type="text/javascript"></script>
<script
	src="<?php echo base_url();?>assets/jquery/jquery.ui.datepicker.min.js"
	type="text/javascript"></script>
<style>
table td img.ui-datepicker-trigger {
	padding-left: 5px;
}

form label {
	padding: 5px 0 !important;
}

#reportslist {
	position: relative
}

#reportslist .loading {
	position: absolute;
	left: 0px;
	top: 0;
	width: 100%;
	height: 30px;
	margin: 0 auto;
	text-align: center;
}

#sc_select {
	height: 100px;
}

.tool-icon a {
	padding: 5px;
}

#main-content .tblbox table.tbl tfoot tr td {
	border-top: 1px solid #00689C;
}
</style>

<span class="message"><span class="message_text"></span></span>
<form onsubmit="return false" id="frmallocate" name="frmallocate">
<table width="45%">
	<col width="25%" />
	<col />
	<tr>
		<th><label><?php echo $this->lang->line('service_center');?>: </label></th>
		<td><?php echo $servicecenter_select;?></td>
	</tr>
	<tr>
		<td colspan="5"><input class="button" type="submit"
			value="<?php echo $this->lang->line('generate'); ?>"
			name="btn_submit" id="btn_submit" onClick="getreportslist();" />&nbsp;<span
			id="loading"></span></td>
	</tr>
</table>
</form>
<div id="reportslist"
	style="display: none;"></div>
