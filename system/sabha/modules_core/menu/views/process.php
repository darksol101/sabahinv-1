<?php 
switch (trim($ajaxaction)){
			case 'getuploadlist':
				displayFileList($files);
			break;
			}
function displayFileList($files){

?>
		
	<table style="width:100%">
    <col width="5%" /><col /><col width="15%" /><col width="20%" /><col width="10%" />
    	<thead>
        	<tr><th>S.No.</th><th>Title</th><th>Size</th><th>Type</th><th>&nbsp;</th></tr>
        </thead>
        <tbody>
<?php
	$i=1;
	$category="";
	foreach ($files as $file){
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		if($category!=$file->cat_id){
			$category=$file->cat_id;
		?>
		<tr><th colspan="5" style="text-align:center">&laquo;&laquo;--- <?php if((int)$file->cat_id==0){?><span id="listcategory">N/A</span><?php }?><?php echo $file->category;?> ---&raquo;&raquo;</th></tr>
		<?php
		}
		?>
    
    		<tr <?php echo $trstyle;?>><td><?php echo $i;?></td><td><a onclick="showImage('<?php echo $file->file?>')" ><?php echo $file->title;?></a></td><td><?php echo $file->file_size;?> KB </td><td><?php if((int)$file->cat_id==0){?><span id="listcategory"><span id="showcategory" onclick="showCategory('<?php echo $file->id;?>')">N/A</span></span><?php }?><?php echo $file->category;?></td><td align="center"><a onclick="delet('<?php echo $file->id?>')"><img src="<?php echo base_url();?>assets/images/icons/delete.png" class="iconbtn"></a></td></tr>
    <?php	
	$i++;
		}
?>
		</tbody>
    </table>        
<?php	
	}
?>