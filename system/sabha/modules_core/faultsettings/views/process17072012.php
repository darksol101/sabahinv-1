<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php 
switch (trim($ajaxaction)){
			case 'getsymptomlist':
				displaySymptomList($symptoms,$navigation,$page);
				break;
			case 'getdefectlist':
				displayDefectList($defects,$navigation,$page);
				break;
			case 'getrepairlist':
				displayRepairList($repairs,$navigation,$page);
				break;
}
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
<?php
//for symptomcode
function displaySymptomList($symptoms,$navigation,$page){
?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showsymptomlist();
	})
});
</script>
	<table style="width:100%" cellpadding="0" cellspacing="0" class="tblgrid">
    <col width="1%" /><col /><col /><col /><col width="1%"/> <col width="1%" /><col width="1%"/>
    	<thead>
        	<tr><th>S.No.</th><th>Symptom Code</th><th>Description</th><th style="text-align:center">Product</th><th>&nbsp;</th><th align="center">Status</th><th>&nbsp;</th></tr>
        </thead>
        <tbody>
		
<?php
$i=1;
	foreach ($symptoms as $symptom){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	$symptom_status=$symptom->symptom_status=="0"?icon('delete'):icon('tick');
?>
 		<tr <?php echo $trstyle;?>><td><?php echo $i+$page['start'];?></td>
			<td><?php echo $symptom->symptom_code;?></td>
			<td><?php echo $symptom->symptom_description;?></td>
            <td style="text-align:center"><?php echo $symptom->product_name;?></td>
			<td><a class="btn" onclick="showSymptomDetails('<?php  echo $symptom->symptom_id; ?>')"><?php echo icon('edit','edit','png');?></a></td>
			<td style="text-align:center;"><a class="btn" onclick="changeStatus('<?php  echo $symptom->symptom_id; ?>','<?php  echo $symptom->symptom_status; ?>')"><?php echo $symptom_status;?></a></td>
			<td><a class="btn" onclick="deleteSymptom('<?php echo $symptom->symptom_id;?>')"><?php echo icon("delete","Delete","png");?></a></td>
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
//end of symptomcode
?>

<?php
//for defectcode
function displayDefectList($defects,$navigation,$page){
?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showdefectlist();
	})
});
</script>
	<table style="width:100%" cellpadding="0" cellspacing="0" class="tblgrid">
    <col width="1%" /><col /><col /><col /><col width="1%"/> <col width="1%" /><col width="1%"/>
    	<thead>
        	<tr><th>S.No.</th><th>Defect Code</th><th>Symptom Code</th><th>Description</th><th>&nbsp;</th><th align="center">Status</th><th>&nbsp;</th></tr>
        </thead>
        <tbody>
		
<?php
$i=1;
	foreach ($defects as $defect){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	$defect_status=$defect->defect_status=="0"?icon('delete'):icon('tick');
?>
 		<tr <?php echo $trstyle;?>><td><?php echo $i+$page['start'];?></td>
			<td><?php echo $defect->defect_code; ?></td>
			<td><?php echo $defect->symptom_code;?></td>
			<td><?php echo $defect->defect_description;?></td>
			<td><a class="btn" onclick="showDefectDetails('<?php  echo $defect->defect_id; ?>')"><?php echo icon('edit','edit','png');?></a></td>
			<td style="text-align:center;"><a class="btn" onclick="changeStatus('<?php  echo $defect->defect_id; ?>','<?php  echo $defect->defect_status; ?>')"><?php echo $defect_status;?></a></td>
			<td><a class="btn" onclick="deleteDefect('<?php echo $defect->defect_id;?>')"><?php echo icon("delete","Delete","png");?></a></td>
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
//end of defectcode
?>
<?php
//for repaircode
function displayRepairList($repairs,$navigation,$page){
?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showrepairlist();
	})
});
</script>
	<table style="width:100%" cellpadding="0" cellspacing="0" class="tblgrid">
    <col width="1%" /><col /><col /><col /><col /><col width="1%"/> <col width="1%" /><col width="1%"/>
    	<thead>
        	<tr><th>S.No.</th><th>Repair Code</th><th>Defect Code</th><th>Symptom Code</th><th>Description</th><th>&nbsp;</th><th align="center">Status</th><th>&nbsp;</th></tr>
        </thead>
        <tbody>
		
<?php
$i=1;
	foreach ($repairs as $repair){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	$repair_status=$repair->repair_status=="0"?icon('delete'):icon('tick');
?>
 		<tr <?php echo $trstyle;?>><td align="center"><?php echo $i+$page['start'];?></td>
			<td><?php echo $repair->repair_code;?></td>
			<td><?php echo $repair->defect_code;?></td>
            <td><?php echo $repair->symptom_code;?></td>
			<td><?php echo $repair->repair_description;?></td>
			<td><a class="btn" onclick="showRepairDetails('<?php  echo $repair->repair_id; ?>')"><?php echo icon('edit','edit','png');?></a></td>
			<td style="text-align:center;"><a class="btn" onclick="changeStatus('<?php  echo $repair->repair_id; ?>','<?php  echo $repair->repair_status; ?>')"><?php echo $repair_status;?></a></td>
			<td><a class="btn" onclick="deleteRepair('<?php echo $repair->repair_id;?>')"><?php echo icon("delete","Delete","png");?></a></td>
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
//end of repaircode
?>