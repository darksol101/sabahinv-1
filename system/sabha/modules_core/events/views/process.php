<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<?php
switch (trim($ajaxaction)){
			case 'getbulletinlist':
				displayBulletinList($events,$navigation,$page);
}

function displayBulletinList($events,$navigation,$page){
?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showeventslist();
	})
});
</script>
<?php
	$i=1;
	if(count($events)>0){
		foreach ($events as $event){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	?>
	<table width="100%" class="tblgrid rdbox">
	  <tr>
		<th><?php echo $event->bulletin_board_name;?><span><?php echo $event->bulletin_board_start_dt;?></span> - <span><?php echo $event->bulletin_board_end_dt;?></span></th>
	  </tr>
	  <tr>
		<td><?php echo $event->bulletin_board_desc;?></td>
	  </tr>
	</table>
	<?php 
	$i++;
	} 
	?>
	</tbody>
	<tfoot>
	  <tr>
		<td colspan="6"><div class="pagination"><?php echo $navigation;?></div></td>
	  </tr>
	</tfoot>
	</table>
	<?php
	}else{
		echo 'No  events available.';
	}
}

?>
