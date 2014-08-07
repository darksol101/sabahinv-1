<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php if (isset($page_title)) echo ' | ' . $page_title; ?></title>
<?php echo link_tag(asset_url() . 'images/favicon.ico', 'shortcut icon', 'image/ico'); ?>
</head>
<body>
<div id="reportContent" style="height:500px; overflow:auto; margin:0;width:650px;">
	<div id="print-account-name"><span class="value"><?php echo  $this->config->item('account_name'); ?></span></div>
	<div id="print-account-address"><span class="value"><?php echo $this->config->item('account_address'); ?></span></div>
	<br />
	<div id="print-report-title"><span class="value"><?php echo $title; ?></span></div>
	<div id="print-report-period">
		<span class="value">
			Financial year<br />
			<?php echo date_mysql_to_php_display($this->mdl_mcb_data->setting('account_fy_start')); ?> - <?php echo date_mysql_to_php_display($this->mdl_mcb_data->setting('account_fy_end')); ?>
		</span>
	</div>
	<br />
	<div id="main-content-print">
		<?php $this->load->view($report,array('print_preview'=>(isset($print_preview))?true:false)); ?>
	</div>
	<br />
        </div>
</body>
<form>
<input class="hide-print button" type="button" onClick="PrintReport()" value="Print Statement">
</form>
<iframe id="ifmcontentstoprint" style="height: 0px; width: 0px; position: absolute"></iframe>
</html>