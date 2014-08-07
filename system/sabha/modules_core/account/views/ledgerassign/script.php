<script type="text/javascript">
$(document).ready(function(){
	$('.content-box ul.content-box-tabs li a').click(function(){
		window.location='<?php echo base_url()?>'+this.id;													  
	});
	getLedgerAssignList();
})
function saveBillInfo(){
	showloading();
	var csrf = $('[name="csrf_test_name"]').val();
	$.post( "<?php echo site_url()?>account/ledgerassign/saveledgerassign",
		{ledger_id: $('#ledger_id').val(), billing_select: $('#billing_select').val(), sc_id: $('#sc_id').val() ,ledger_assign_id:$('#ledger_assign_id').val(),ledger_assign_type:$('#ledger_assign_type').val(),csrf_test_name: csrf ,unq: ajaxunq()},
		function( data ) {
			hideloading(data);
			$('.notification').delay(7000).fadeOut(1500);
	  		getLedgerAssignList();
		}).done(function() {
  	})
  	.fail(function() {
    		
  	});
}
function getLedgerAssignList(){
	$('#ledger_assign_id').val(0);
	$('#ledger_id').val(0);
	$('#billing_select').val('');
	$('#sc_id').val(0);
	$('#ledger_assign_type').val(0);
	$('#save_ledger_assign').val('Save');
	
	$("#ledgerassignlist").css({'display':'none'});
	$('#ledgerassign').validationEngine('hideAll');
	showloading();
	var searchtxt=$("#searchgrptxt").val()
	var currentpage = $('#currentpage').val();
	var filter_sc_id = $('#filter_sc_id').val();
	var params="filter_sc_id="+filter_sc_id+"&currentpage="+currentpage+"&searchtxt="+searchtxt+"&unq="+ajaxunq()
	$.ajax({			
			type	:	"POST",
			url		:	"<?php echo site_url();?>account/ledgerassign/getledgerassignlist",
			data	:	params,
			success	:	function (data){
				$("#ledgerassignlist").css({'display':'none'});
				$("#ledgerassignlist").html(data);
				$("#ledgerassignlist").slideDown('slow');
				hideloading();
				}								
		});//end  ajax	
}
function deleteLedgerAssgn(ledger_assign_id){
	$('#ledgerassign').validationEngine('hideAll');
	if(confirm('Are you sure to delete this assign?')){
		$.post( "<?php echo site_url()?>account/ledgerassign/deleteledgerassign",
			{ledger_assign_id: ledger_assign_id ,unq: ajaxunq()},
			function( data ) {
				hideloading(data);
				getLedgerAssignList();
			}).done(function() {
		})
		.fail(function() {
			alert( "error" );
		});
	}
}
function editLedgerAssgn(ledger_assign_id){
	$('#ledgerassign').validationEngine('hideAll');
	$('#save_ledger_assign').val('Edit  ');
	$.post( "<?php echo site_url()?>account/ledgerassign/editledgerassign",
		{ledger_assign_id: ledger_assign_id ,unq: ajaxunq()},
		function( data ) {
			var _obj = jQuery.parseJSON( data);			
			$('#ledger_assign_id').val( _obj.ledger_assign_id );
			$('#ledger_id').val( _obj.ledger_id );
			$('#billing_select').val( _obj.ledger_assign_name +':'+ _obj.ledger_assign_key);
			$('#ledger_assign_type').val(_obj.ledger_assign_type);
			$('#sc_id').val( _obj.sc_id );
			
		}).done(function() {
	})
	.fail(function() {
		
	});
}
</script>