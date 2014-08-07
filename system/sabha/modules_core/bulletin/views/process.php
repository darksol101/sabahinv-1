<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>

<?php
switch (trim($ajaxaction)){
			case 'getbulletinlist':
				displayBulletinList($bulletins,$navigation,$page);
}

function displayBulletinList($bulletins,$navigation,$page){
?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showbulletinlist();
	})
});
</script>
	<table style="width:100%" cellpadding="0" cellspacing="0" class="tblgrid">
    <col width="1%" /><col /><col /><col width="1%"/> <col width="1%" /><col width="1%"/><col width="1%"/><col width="1%"/>
    	<thead>
        	<tr><th>S.No.</th><th>Bulletin Name</th><th>Description</th><th>Start Date</th><th>End Date</th><th>&nbsp;</th><th align="center">Status</th><th style="text-align:center">&nbsp;</th></tr>
        </thead>
        <tbody>
		
<?php
$i=1;
	foreach ($bulletins as $bulletin){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	$bulletin_board_status=$bulletin->bulletin_board_status=="0"?icon('delete'):icon('tick');
?>
 		<tr <?php echo $trstyle;?>><td><?php echo $i+$page['start'];?></td>
			<td><?php echo $bulletin->bulletin_board_name;?></td>
			<td><?php echo $bulletin->bulletin_board_desc;?></td>
			<td><?php echo  format_date(strtotime($bulletin->bulletin_board_start_dt));?></td>
			<td><?php echo  format_date(strtotime($bulletin->bulletin_board_end_dt));?></td>
			<td><a class="btn" onclick="showBulletinDetails('<?php  echo $bulletin->bulletin_board_id; ?>')"><?php echo icon('edit','edit','png');?></a></td>
			<td style="text-align:center;"><a class="btn" onclick="changeStatus('<?php  echo $bulletin->bulletin_board_id; ?>','<?php  echo $bulletin->bulletin_board_status; ?>')"><?php echo $bulletin_board_status;?></a></td>
			<td><a class="btn" onclick="deleteBulletin('<?php echo $bulletin->bulletin_board_id;?>')"><?php echo icon("delete","Delete","png");?></a></td>
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

?>