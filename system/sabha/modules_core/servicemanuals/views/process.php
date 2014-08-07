<?php 
switch (trim($ajaxaction)){
	case 'getservicemanualslist':
		displayservicemanualsList($servicemanuals,$navigation);
		break;
}			
function displayservicemanualsList($servicemanuals,$navigation){?>
<script type="text/javascript">
$(document).ready(function(){ $(".pagination a").click(function(){$("#currentpage").val(this.id);getservicemanualslist();})});
</script>
<table style="width:70%" cellpadding="0" cellspacing="0" class="tblgrid"><col width="1%" /><col /><col /><col /><col width="5%" /><col width="15%" /><thead><tr><th>S.No.</th><th>Model Number</th><th>Description</th><th style="text-align:center">Product</th><th></th></tr></thead><tbody><?php
	$i=1;
	foreach ($servicemanuals as $svm){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	?><tr <?php echo $trstyle;?>><td><?php echo $i;?></td><td><?php echo $svm->model_number;?></td><td><?php echo $svm->manual_description;?></td><td style="text-align:center"><?php echo $svm->product_name;?></td><td><a class="btn" onclick="downloadManual('<?php echo $svm->model_id;?>')"><?php echo icon('download','Click to download','png');?></a></td></tr>
<?php $i++;}?>
</tbody><tfoot><tr><td colspan="6"><div class="pagination"><?php echo $navigation;?></div></td></tr></tfoot></table> 
<?php }?>
