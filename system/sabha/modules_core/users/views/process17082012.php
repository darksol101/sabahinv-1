<?php 
switch (trim($ajaxaction)){
			case 'getuserlist':
				displayUserList($users,$userID,$navigation,$page);
			break;
			case '':
			break;
			case 'getgrouplist':
				displayGroupList($groups);
			break;
			
			}

function displayGroupList($groups){
?>
	<table style="width:100%" class="tblgrid" cellpadding="0" cellspacing="0">
    <col width="5%" /><col /><col width="15%" /><col width="15%" /><col width="1%" />
    	<thead>
        	<tr><th>S.No.</th><th>Group</th><th>Group ID</th><th>Status</th><th>&nbsp;</th></tr>
        </thead>
        <tbody>
<?php
	$i=1;
	foreach ($groups as $group){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	$status=$group->usergroup_status=="1"?icon("tick","Active","png"):icon("cross","Inactive","png");
	?>
    		<tr <?php echo $trstyle;?>><td><?php echo $i;?></td><td><a onclick="showgroup('<?php echo $group->usergroup_id?>')" ><?php echo $group->details;?></a></td><td><?php echo $group->usergroup_id;?></td><td align="center"><?php echo $status;?></td><td><a onclick="deletgroup('<?php echo $group->usergroup_id?>')"><?php echo icon("delete","Delete","png");?></a></td></tr>
    <?php	
	$i++;
		}
?>
		</tbody>
         <tfoot><tr><td colspan="6" style="vertical-align:middle;"><div class="pagination"><?php echo $navigation;?></div></td></tr></tfoot>
    </table>        
<?php	
	}


function displayUserList($users,$userID,$navigation,$page){
?>
<script type="text/javascript">
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showTable();
	})
});
</script>
	<table style="width:100%" class="tblgrid" cellpadding="0" cellspacing="0">
    <col width="5%" /><col /><col width="20%" /><col width="15%" /><col width="1%" /><col width="1%" /><col width="1%" />
    	<thead>
        	<tr><th>S.No.</th><th>User</th><th>Store</th><th>User Group</th><th></th><th style="text-align:center;">Status</th><th>&nbsp;</th></tr>
        </thead>
        <tbody>
<?php
	$i=1;
	foreach ($users as $user){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	$ustatus=$user->ustatus=="0"?icon('user_inactive'):icon('user_active');
	?>
    		<tr <?php echo $trstyle;?>><td><?php echo $i;?></td><td><?php echo $user->username;?></td><td><?php echo $user->sc_name;?></td><td><?php echo $user->usergroup;?></td><td align="center"><a style="cursor:pointer;" onclick="showuser('<?php echo $user->id?>')" ><?php echo icon('edit','Click to edit user','png');?></a></td><td  style="text-align:center;"><a class="btn" onclick="changestatus('<?php echo $user->user_id?>','<?php echo $user->ustatus?>');"><?php echo $ustatus;?></a></td><td><a class="btn" onclick="delet('<?php echo $user->id?>')"><?php echo icon('delete_user','Click to delete','png')?></a></td></tr>
    <?php $i++;}?>
		</tbody>
        <tfoot><tr><td colspan="7"><div class="pagination"><?php echo $navigation;?></div></td></tr></tfoot>
    </table>
    <input type="hidden" name="autoincrementId" id="autoincrementId" value="<?php echo $userID;?>" /> 
<?php	
	}
?>