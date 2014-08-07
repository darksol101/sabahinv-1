<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	showReportsTable();
	cancel();
	$("#frmreports").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){generatereports();}}  
	});
})
</script>
