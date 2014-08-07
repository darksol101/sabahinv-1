<?php 
switch (trim($ajaxaction)){
			case 'getmanuallist':
				displayManualList($manuals);
			break;
			}
function displayManualList($manuals)
{
	if(count($manuals)>0){
	?>
    <table width="100%" class="tblgrid" cellpadding="0" cellspacing="0">
    <col width="1%" /><col /><col width="1%" />
    <thead><tr><th>S.No.</th><th>File Name</th><th>&nbsp;</th></tr></thead>
    <tbody>
    <?php
	$i=1;
	foreach($manuals as $row){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";?>
		<tr<?php echo $trstyle;?>>
        	<td><?php echo $i;?></td>
            <td style="vertical-align:top;"><a href="<?php echo base_url();?>uploads/product_models/<?php echo $row->file_name;?>" target="_blank"><?php echo icon(strtolower(str_replace(".","", $row->file_ext)),'file','png')?><?php echo $row->file_name;?></a></td>
            <td><a class="btn" onclick="deleteManual('<?php echo $row->model_id;?>','<?php echo $row->manual_id;?>','<?php echo $row->file_name;?>')"><?php echo icon('delete','Delete','png');?></a></td>
        </tr>
	<?php	$i++;
	}?></tbody>
    </table>
<?php } } ?>