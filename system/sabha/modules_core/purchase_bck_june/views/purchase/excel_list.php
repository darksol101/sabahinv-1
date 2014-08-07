<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<form method="post" name="xlForm" id="xlForm">
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
<col width="1%" /><col /><col /><col /><col /><col /><col width="20%" />
<tr>
<td>
	<input type="button" name="save_xldata" id="save_xldata" value="Save" class="button" onclick="saveXldata();" /></td>
</tr>
	<tr>
    	<th>S.No.</th>
    	<th>Item Number</th>
        <th>Item description</th>
        <th style="text-align:center">Company</th>
        <th style="text-align:center">Quantity</th>
        <th colspan="2" style="text-align:center">Status</th>
    </tr>
<?php

for ($i = 2; $i <= $row_count; $i++) {
	$trstyle=$i%2==0?" class='even datas' ": " class='odd datas' ";
	if(!isset($rows[$i][2])){
		$rows[$i][2] = '';
	}
	if(!isset($rows[$i][3])){
		$rows[$i][3] = '';
	}
	if(!isset($rows[$i][4])){
		$rows[$i][4] = 'Default';
	}
	if(!isset($rows[$i][5])){
		$rows[$i][5] = '';
	}
	if($rows[$i][2]){
		if($rows[$i][2] == '' || $rows[$i][4] == '' || $rows[$i][5] == '' || (!in_array($rows[$i][2],$part_arr)) || (!in_array($rows[$i][4],$company_arr)) || !is_numeric($rows[$i][5])  || ($rows[$i][5] <= 0)){
			$duplicate = true;
		}else{
			$duplicate = false;
		}


	?>
    	<tr<?php echo $trstyle;?> id="appendThisToForm">
    	<td><?php echo $i-1;?></td>
    	<td style="text-align:center"><ii><?php echo $rows[$i][2];?></ii> <input type="hidden" name="part_no[]" value="<?php echo rtrim(ltrim(str_replace('"',"'",$rows[$i][2])));?>" /></td>
        <td style="text-align:center"><ii><?php echo $rows[$i][3];?></ii> <input type="hidden" name="part_dsc[]" value="<?php echo rtrim(ltrim(str_replace('"',"'",$rows[$i][3])));?>" /></td>
        <td style="text-align:center"><ii><?php echo $rows[$i][4];?></ii> <input type="hidden" name="company[]" value="<?php echo rtrim(ltrim(str_replace('"',"'",$rows[$i][4])));?>" /></td>
        <td style="text-align:center"><ii><?php echo (int) $rows[$i][5];?></ii> <input type="hidden" name="quantity[]" value="<?php echo rtrim(ltrim(str_replace('"',"'",$rows[$i][5])));?>" /></td>
        <td style="text-align:center">
        	
        <?php
			$options = array();
			if($duplicate==true){
				echo '<span style="padding:0 1px;">Invalid Entry</span>';
				//array_unshift($options, $this->mdl_html->option( '2', 'Update'));
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