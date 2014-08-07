<?php 
switch (trim($ajaxaction)){
			case 'getgrouplist':
				displayGroupList($groups);
			break;
}

function displayGroupList($groups){
?>
	<table style="width:100%" class="tblgrid" cellpadding="0" cellspacing="0">
    <col width="5%" /><col /><col width="1%" /><col width="1%" /><col width="1%" />
    	<thead>
        	<tr><th>S.No.</th><th>Group</th><th></th><th>Status</th><th>&nbsp;</th></tr>
        </thead>
        <tbody>
<?php
	$i=1;
	foreach ($groups as $group){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	$status=$group->usergroup_status=="1"?icon("tick","Active","png"):icon("cross","Inactive","png");
	?>
    		<tr <?php echo $trstyle;?>><td><?php echo $i;?></td><td><?php echo $group->details;?></td><td><a class="btn" onclick="showgroup('<?php echo $group->usergroup_id?>')" ><?php echo icon("edit","Edit","png");?></a></td><td style="text-align:center;"><a class="btn"><?php echo $status;?></a></td><td><a class="btn" onclick="deletgroup('<?php echo $group->usergroup_id?>')"><?php echo icon("delete","Delete","png");?></a></td></tr>
    <?php	
	$i++;
		}
?>
		</tbody>
    </table>        
<?php	
	}

