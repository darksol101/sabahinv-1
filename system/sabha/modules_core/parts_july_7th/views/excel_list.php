<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<form method="post" name="xlForm" id="xlForm">
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
<col width="1%" />
<col width="12%" />
<col width="20%" />
<col width="20%" />
<col width="12%" />
<col width="12%" />
<col width="12%" />
<col width="30%" />
<col width="20%" />
<tr>
<td><input type="button" name="save_xldata" id="save_xldata" value="Save" class="button" onclick="saveXldata();" /></td>
</tr>
	<tr>
    	<th>S.No.</th>
    	<th>Old Item Number</th>
        <th>New Item Number</th>
        <th>Item description</th>
		<th>Landing Price</th>
        <th>Customer Price</th>
        <th>Store Price</th>
        <th>Remarks</th>
        <th>Status</th>
    </tr>
<?php
$part_list = array();
for ($i = 2; $i <= $row_count; $i++) {
	$trstyle=$i%2==0?" class='even' ": " class='odd' ";
	if(!isset($rows[$i][1])){
		$rows[$i][1] = '';
	}
	if(!isset($rows[$i][2])){
		$rows[$i][2] = '';
	}
	if(!isset($rows[$i][3])){
		$rows[$i][3] = '';
	}
	if(!isset($rows[$i][4])){
		$rows[$i][4] = '0.00';
	}
	if(!isset($rows[$i][5])){
		$rows[$i][5] = '0.00';
	}
	if(!isset($rows[$i][6])){
		$rows[$i][6] = '0.00';
	}
	if(!isset($rows[$i][7])){
		$rows[$i][7] = '';
	}
	

	$part_no[$i] = $rows[$i][2];

	/*if($rows[$i][4]){
		$part_no[$i] .="-".$rows[$i][4];
	}

	if($rows[$i][4]){
		$part_no[$i] .="-".$rows[$i][5];
	}*/

	//echo $part_no[$i].PHP_EOL;

	$err = false;
	$rows[$i][4] = (int) $rows[$i][4];
	$rows[$i][5] = (int) $rows[$i][5];
	$rows[$i][6] = (int) $rows[$i][6];

	if($rows[$i][4] =='0' || !is_numeric($rows[$i][4])){
		$rows[$i][4] = '0.00';
		$err = true;
	}

	if($rows[$i][5] =='0' || !is_numeric($rows[$i][5])){
		$rows[$i][5] = '0.00';
		$err = true;
	}
	
	if($rows[$i][6] =='0' || !is_numeric($rows[$i][6])){
		$rows[$i][6] = '0.00';
		$err = true;
	}

	
	

	if($rows[$i][2]){
		if(in_array($part_no[$i],$part_arr)){
			$duplicate = true;
		}
		else if(in_array($part_no[$i], $part_list)){
			$duplicate = true;
		}
		else{
			$duplicate = false;
		}




	?>
    <tr<?php echo $trstyle;?>>
    	<td><?php echo $i-1;?></td>
    	<td><?php echo $rows[$i][1];?><input type="hidden" name="part_initial_no[]" value="<?php echo rtrim(ltrim(str_replace('"',"'",$rows[$i][1])));?>" /></td>
        <td><?php echo $rows[$i][2];?><input type="hidden" name="part_number[]" value="<?php echo rtrim(ltrim(str_replace('"',"'",$rows[$i][2])));?>" /></td>
        <td><?php echo $rows[$i][3];?><input type="hidden" name="part_desc[]" value="<?php echo $rows[$i][3];?>" /></td>
        <td><?php echo $rows[$i][4];?><input type="hidden" name="part_landing_price[]" value="<?php echo $rows[$i][4];?>" /></td>
		<td><?php echo $rows[$i][5];?><input type="hidden" name="part_customer_price[]" value="<?php echo $rows[$i][5];?>" /></td>
        <td><?php echo $rows[$i][6];?><input type="hidden" name="part_sc_price[]" value="<?php echo $rows[$i][6];?>" /></td>
        <td><?php echo $rows[$i][7];?><input type="hidden" name="remarks[]" value="<?php echo $rows[$i][7];?>" /></td>
     	<td>
        <?php
			$options = array();
			if($duplicate==true){
				echo '<span style="padding:0 1px;">Already exists</span>';
				array_unshift($options, $this->mdl_html->option( '2', 'Update'));
				$selected = 3;
			}else if($err == true){
				echo '<span style="padding:0 1px;">Error In Prices</span>';
				array_unshift($options, $this->mdl_html->option( '2', 'Update'));
				$selected = 3;
			}
			else{
				echo '<span style="padding:0 30px;">Ok</span>';
				$selected = 1;
				array_unshift($options, $this->mdl_html->option( '1', 'Insert'));
			}


		?> 
       

		<?php
			array_unshift($options, $this->mdl_html->option( '3', 'Ignore'));
			
			$select = $this->mdl_html->genericlist($options,'task_select[]',array('style'=>'padding-left:5px;','class'=>'validate[required] select-one'),'value','text',$selected);
			echo $select;
		?>

        </td>
    </tr>
<?php 
	$part_list[] = $rows[$i][2];
} 


}

?>
</table>
</div>
</form>