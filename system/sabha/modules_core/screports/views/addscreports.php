<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	showscreportsTable();
	cancel();
	$("#frmscreports").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){generatescreports();}}  
	});
})
</script>
