<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<script language="javascript">
$(document).ready(function(){
	showclosedcallreportsTable();
	cancel();
	$("#frmclosedcallreports").validationEngine('attach', {
	  onValidationComplete: function(form, status){ if(status==true){generateclosedcallreports();}}  
	});
})
</script>
