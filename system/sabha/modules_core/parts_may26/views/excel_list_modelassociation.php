<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<form method="post" name="xlForm" id="xlForm">
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
<col width="1%" /><col /><col /><col /><col /><col /><col width="10%" /><col width="20%" />
<tr>
<td><input type="button" name="save_xldata" id="save_xldata" value="Save" class="button" onclick="saveXldata_modelassociation();" /></td>
</tr>
	<tr>
    	<th>S.No.</th>
    	<th>Item Number</th>
        <th>Model Number</th>
        <th colspan="2" style="text-align:center">Status</th>
    </tr>
<?php
for ($i = 2; $i <= $row_count; $i++) {
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	if(!isset($rows[$i][2])){
		$rows[$i][2] = '';
	}
	if(!isset($rows[$i][3])){
		$rows[$i][3] = '';
	}
	
	if($rows[$i][2]){
		foreach($parts as $part){
	   
	   if($rows[$i][2]== $part->part_number && strtoupper($rows[$i][3])== strtoupper($part->model_number)){
			$duplicate = true;
		break;
		}else{
			$duplicate = false;
		}
		}
	?>
    <tr<?php echo $trstyle;?>>
    	<td><?php echo $i-1;?></td>
    	<td><?php echo $rows[$i][2];?><input type="hidden" name="part_no[]" value="<?php echo rtrim(ltrim(str_replace('"',"'",$rows[$i][2])));?>" /></td>
        <td><?php echo $rows[$i][3];?><input type="hidden" name="model_no[]" value="<?php echo strtoupper(rtrim(ltrim(str_replace('"',"'",$rows[$i][3]))));?>" /></td>
        
        <td style="text-align:center">
        <?php
			$options = array();
			if($duplicate==true){
				echo '<span style="padding:0 1px;">Already exists</span>';
				array_unshift($options, $this->mdl_html->option( '2', 'Update'));
				$selected = 3;
			}else{
				echo '<span style="padding:0 30px;">Ok</span>';
				$selected = 1;
				array_unshift($options, $this->mdl_html->option( '1', 'Insert'));
			}
		?> 
        </td>
        <td style="text-align:center">
		<?php
			array_unshift($options, $this->mdl_html->option( '3', 'Ignore'));
			$select = $this->mdl_html->genericlist($options,'task_select[]',array('style'=>'width:60%;padding-left:5px;','class'=>'validate[required] select-one'),'value','text',$selected);
			echo $select;
		?>
        </td>
    </tr>
<?php } }?>
</table>
</div>
</form>