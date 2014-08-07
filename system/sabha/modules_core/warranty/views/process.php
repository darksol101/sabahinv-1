<?php 
switch (trim($ajaxaction)){
			case 'getwarrantyuploadlist':
				displayWarrantyUploadList($warranty_uploads);
			break;
			}
function displayWarrantyUploadList($warranty_uploads)
{
	if(count($warranty_uploads)>0){
	?>
    <table width="100%" class="tblgrid" cellpadding="0" cellspacing="0">
    <col width="1%" /><col /><col width="1%" /><col width="1%" />
    <thead><tr><th>S.No.</th><th>File Name</th><th style="text-align:center">Download</th><th style="text-align:center">Delete</th></tr></thead>
    <tbody>
    <?php
	$i=1;
	foreach($warranty_uploads as $row){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";?>
		<tr<?php echo $trstyle;?>>
        	<td><?php echo $i;?></td>
            <td style="vertical-align:top;"><?php echo icon(strtolower(str_replace(".","", $row->warranty_file_ext)),'file','png')?><?php echo $row->warranty_file_name;?></td>
            <td style="text-align:center"><a href="<?php echo base_url();?>uploads/product_warranty/<?php echo $row->warranty_file_name;?>" target="_blank"><?php echo icon('download','Download','png');?></a></th>
            <td style="text-align:center"><a class="btn" onclick="deleteWarrantyUpload('<?php echo $row->call_id;?>','<?php echo $row->warranty_upload_id;?>','<?php echo $row->warranty_file_name;?>');"><?php echo icon('delete','Delete','png');?></a></td>
        </tr>
	<?php	$i++;
	}?></tbody>
    </table>
<?php } } ?>