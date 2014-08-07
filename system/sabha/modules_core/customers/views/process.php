<?php 
switch (trim($ajaxaction)){
			case 'getcustomerlist':
				displayCustomerList($customers,$navigation,$page);
				break;
			}
function displayCustomerList($cusomers,$navigation,$page){
?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showCustomerList();
	})
});
</script>
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
	<col width="1%" /><col /><col  /><col  /><col  />
	<thead><tr><th width="1%" align="center">S.No.</th><th style="text-align:left;">Customer Name</th><th style="text-align:center;">Customer Address</th><th style="text-align:center">Home Phone</th><th style="text-align:center">Mobile Phone</th></tr></thead>
	<tbody>
    <?php 
    $i=1;
    $trclass=$i%2==0?" class='even' ": " class='odd' ";
    foreach($cusomers as  $row){?>
            <tr<?php echo $trclass;?>>
                <td style="text-align:center;"><?php echo $i;?></td>
                <td style="padding:2px;text-align:center;text-align:left" align="left"><a onclick="setCustomer('<?php echo $row->cust_id;?>')" class="btn"><?php echo $row->cust_first_name.' '.$row->cust_last_name;?></a></td>
                <td style="text-align:center"><?php echo $row->cust_address;?></td>
                <td style="text-align:center"><?php echo $row->cust_phone_home;?></td>
                <td style="text-align:center"><?php echo $row->cust_phone_mobile;?></td>
            </tr>
    <?php $i++; }?>
	</tbody>
    <tfoot><tr><td colspan="5"><div class="pagination"><?php echo $navigation;?></div></td></tr></tfoot>
</table>
<?php }?>