<?php
switch (trim($ajaxaction)){
	case 'getgrouplist':
		displayAccessList($groups,$this_access);
		break;
}

function displayAccessList($groups,$this_access){
	?>
<script type="text/javascript">
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showGroupTable();
	})
});
</script>
<table style="width: 100%" class="tblgrid" cellpadding="0"
	cellspacing="0">
	<col width="5%" />
	<col />
    <col />
	<col width="1%" />
	<col width="1%" />

	<thead>
		<tr>
			<th>S.No.</th>
			<th>Group</th>
			<th>Access</th>
            <th></th>
			<th>Status</th>			
		</tr>
	</thead>
	<tbody>
	<?php
	$i=1;
	foreach ($groups as $group){
		
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		$status = $group->usergroup_status == "1"?icon("tick","Active","png"):icon("cross","Inactive","png");
		?>
		<tr <?php echo $trstyle;?>>
			<td><?php echo $i;?></td>
			<td><?php echo $group->text;?></td>
            <td><?php foreach($this_access->mdl_menus->getMenusByGroupId($group->value) as $menulist){
				echo $menulist->title.', ';
			};?></td>
			<td><a class="btn"
				onclick="showgroup('<?php echo $group->value?>')"><?php echo icon("edit","Edit","png");?></a></td>
			<td style="text-align: center;"><a class="btn"><?php echo $status;?></a></td>
		
		</tr>
		<?php
		$i++;
	}
	?>
	</tbody>
    
</table>
<?php	
	}
	?>

