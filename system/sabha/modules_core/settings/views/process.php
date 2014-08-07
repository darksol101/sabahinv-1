<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php 
switch (trim($ajaxaction)){
			case 'getfaultlist':
				displayFaultList($faults,$navigation,$page);
				break;
			case 'getreasonlist':
				displayReasonList($reasons,$navigation,$page);
				break;
			case 'getpartpendinglist':
				displayPartPendingList($partpendings,$navigation,$page);
				break;
			case 'getcancellationlist':
				displayCancellationList($cancellations,$navigation,$page);
				break;
			case 'getclosurelist':
				displayClosureList($closures,$navigation,$page);
				break;
			case 'getwarrantylist':
				displayWarrantyList($warrantys,$navigation,$page);
				break;
}
			
//for citylist
function displayReasonList($reasons,$navigation,$page){
?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showreasons();
	})
});
</script>
	<table style="width:100%" cellpadding="0" cellspacing="0" class="tblgrid">
    <col width="1%" /><col /><col width="1%" /><col width="1%" /><col width="1%" />
    	<thead>
        	<tr><th>S.No.</th><th>Reason</th><th>&nbsp;</th><th>Status</th><th>&nbsp;</th></tr>
        </thead>
        <tbody>
<?php
	$i=1;
	foreach ($reasons as $pending){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	$reason_status=$pending->pending_status=="0"?icon('delete'):icon('tick');
	if($pending->pending_id!=32){
	?>
    		<tr <?php echo $trstyle;?>><td><?php echo $i+$page['start'];?></td><td><?php echo $pending->pending;?></td><td><a class="btn" onclick="showReason('<?php  echo $pending->pending_id; ?>')"><?php echo icon('edit','edit','png');?></a></td><td><a onclick="changeStatus('<?php echo $pending->pending_id;?>','<?php echo $pending->pending_status;?>');" class="btn"><?php echo $reason_status;?></a></td><td><a class="btn" onclick="deletReason('<?php echo $pending->pending_id;?>')"><?php echo icon("delete","Delete","png");?></a></td></tr>
    <?php	
	$i++;
		}
	}
?>
		</tbody>
        <tfoot><tr><td colspan="6"><div class="pagination"><?php echo $navigation;?></div></td></tr></tfoot>
    </table> 
<?php
}
//end city list
?>
<?php
function displayFaultList($faults,$navigation,$page){
?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showfaults();
	})
});
</script>
	<table style="width:100%" cellpadding="0" cellspacing="0" class="tblgrid">
    <col width="1%" /><col /><col width="1%" /><col width="1%" /><col width="1%" />
    	<thead>
        	<tr><th>S.No.</th><th>Fault</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th></tr>
        </thead>
        <tbody>
<?php
	$i=1;
	foreach ($faults as $fault){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	$fault_status=$fault->fault_status=="0"?icon('delete'):icon('tick');
	?>
    		<tr <?php echo $trstyle;?>><td><?php echo $i+$page['start'];?></td><td><?php echo $fault->fault;?></td><td><a class="btn" onclick="showFault('<?php  echo $fault->fault_id; ?>')"><?php echo icon('edit','edit','png');?></a></td><td><a class="btn" onclick="changeStatus('<?php  echo $fault->fault_id; ?>','<?php  echo $fault->fault_status; ?>')"><?php echo $fault_status;?></a></td><td><a class="btn" onclick="deletFault('<?php echo $fault->fault_id;?>')"><?php echo icon("delete","Delete","png");?></a></td></tr>
    <?php	
	$i++;
		}
?>
		</tbody>
        <tfoot><tr><td colspan="6"><div class="pagination"><?php echo $navigation;?></div></td></tr></tfoot>
    </table> 
<?php
}
?>
<?php
function displayPartPendingList($partpendings,$navigation,$page){

?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showPartpending();
	})
});
</script>
	<table style="width:100%" cellpadding="0" cellspacing="0" class="tblgrid">
    <col width="1%" /><col /><col width="1%" /><col width="1%" /><col width="1%" />
    	<thead>
        	<tr><th>S.No.</th><th>Reason for Part pending</th><th>&nbsp;</th><th align="center">Status</th><th>&nbsp;</th></tr>
        </thead>
        <tbody>
<?php
$i=1;
foreach ($partpendings as $partpending){
$trstyle=$i%2==0?" class='even' ": " class='odd' ";
$partpending_status=$partpending->partpending_status=="0"?icon('delete'):icon('tick');
?>
  <tr <?php echo $trstyle;?>><td><?php echo $i+$page['start'];?></td><td><?php echo $partpending->partpending;?></td>
  <td><a class="btn" onclick="showPartpendingDetails('<?php  echo $partpending->partpending_id; ?>')"><?php echo icon('edit','edit','png');?></a></td>
  <td><a class="btn" onclick="changeStatus('<?php echo $partpending->partpending_id;?>','<?php echo $partpending->partpending_status;?>')" ><?php echo $partpending_status;?></a></td>
  <td><a class="btn" onclick="deletePartpending('<?php echo $partpending->partpending_id;?>')"><?php echo icon("delete","Delete","png");?></a></td></tr>
<?php 
$i++;
} 
?>
	</tbody>
        <tfoot><tr><td colspan="6"><div class="pagination"><?php echo $navigation;?></div></td></tr></tfoot>
    </table> 
<?php
}
?>
<?php
//for cancellation
function displayCancellationList($cancellations,$navigation,$page){

?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showcancellationlist();
	})
});
</script>
	<table style="width:100%" cellpadding="0" cellspacing="0" class="tblgrid">
    <col width="1%" /><col /><col width="1%" /><col width="1%" /><col width="1%" />
    	<thead>
        	<tr><th>S.No.</th><th>Reason for Cancellation</th><th>&nbsp;</th><th align="center">Status</th><th>&nbsp;</th></tr>
        </thead>
        <tbody>
<?php
$i=1;
	foreach ($cancellations as $cancellation){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	$cancellation_status=$cancellation->cancellation_status=="0"?icon('delete'):icon('tick');
?>
 		<tr <?php echo $trstyle;?>><td><?php echo $i+$page['start'];?></td>
			<td><?php echo $cancellation->cancellation;?></td>
			<td><a class="btn" onclick="showCancellationDetails('<?php  echo $cancellation->cancellation_id; ?>')"><?php echo icon('edit','edit','png');?></a></td>
			<td style="text-align:center;"><a class="btn" onclick="changeStatus('<?php  echo $cancellation->cancellation_id; ?>','<?php  echo $cancellation->cancellation_status; ?>')"><?php echo $cancellation_status;?></a></td>
			<td><a class="btn" onclick="deleteCancellation('<?php echo $cancellation->cancellation_id;?>')"><?php echo icon("delete","Delete","png");?></a></td>
			</tr>
<?php 
$i++;
} 
?>
	</tbody>
        <tfoot><tr><td colspan="6"><div class="pagination"><?php echo $navigation;?></div></td></tr></tfoot>
    </table> 
<?php
}
//end of cancellation
?>


<?php
//start of closure
function displayClosureList($closures,$navigation,$page){

?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showclosurelist();
	})
});
</script>
	<table style="width:100%" cellpadding="0" cellspacing="0" class="tblgrid">
    <col width="1%" /><col /><col width="1%" /><col width="1%" /><col width="1%" />
    	<thead>
        	<tr><th>S.No.</th><th>Reason for Closure</th><th>&nbsp;</th><th align="center">Status</th><th>&nbsp;</th></tr>
        </thead>
        <tbody>
<?php
$i=1;
	foreach ($closures as $closure){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	$closure_status=$closure->closure_status=="0"?icon('delete'):icon('tick');
?>
 		<tr <?php echo $trstyle;?>><td><?php echo $i+$page['start'];?></td>
			<td><?php echo $closure->closure;?></td>
			<td><a class="btn" onclick="showClosureDetails('<?php  echo $closure->closure_id; ?>')"><?php echo icon('edit','edit','png');?></a></td>
			<td style="text-align:center;"><a class="btn" onclick="changeStatusc('<?php  echo $closure->closure_id; ?>','<?php  echo $closure->closure_status; ?>')"><?php echo $closure_status;?></a></td>
			<td><a class="btn" onclick="deleteClosure('<?php echo $closure->closure_id;?>')"><?php echo icon("delete","Delete","png");?></a></td>
			</tr>
<?php 
$i++;
} 
?>
	</tbody>
        <tfoot><tr><td colspan="6"><div class="pagination"><?php echo $navigation;?></div></td></tr></tfoot>
    </table> 
<?php
}
//end of closure
?>
<?php
//for warranty
function displayWarrantyList($warrantys,$navigation,$page){
?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showwarrantylist();
	})
});
</script>
	<table style="width:100%" cellpadding="0" cellspacing="0" class="tblgrid">
    <col width="1%" /><col /><col width="1%" /><col width="1%" /><col width="1%" />
    	<thead>
        	<tr><th>S.No.</th><th>Warranty Information</th><th>&nbsp;</th><th align="center">Status</th><th>&nbsp;</th></tr>
        </thead>
        <tbody>
<?php
$i=1;
	foreach ($warrantys as $warranty){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	$warranty_status=$warranty->warranty_status=="0"?icon('delete'):icon('tick');
?>
 		<tr <?php echo $trstyle;?>><td><?php echo $i+$page['start'];?></td>
			<td><?php echo $warranty->warranty;?></td>
			<td><a class="btn" onclick="showWarrantyDetails('<?php  echo $warranty->warranty_id; ?>')"><?php echo icon('edit','edit','png');?></a></td>
			<td style="text-align:center;"><a class="btn" onclick="changeStatus('<?php  echo $warranty->warranty_id; ?>','<?php  echo $warranty->warranty_status; ?>')"><?php echo $warranty_status;?></a></td>
			<td><a class="btn" onclick="deleteWarranty('<?php echo $warranty->warranty_id;?>')"><?php echo icon("delete","Delete","png");?></a></td>
			</tr>
<?php 
$i++;
} 
?>
	</tbody>
        <tfoot><tr><td colspan="6"><div class="pagination"><?php echo $navigation;?></div></td></tr></tfoot>
    </table> 
<?php
}
//end of warranty
?>