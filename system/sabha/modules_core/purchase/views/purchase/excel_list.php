<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>

<form method="post" name="xlForm" id="xlForm">
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
<col width="1%" /><col width="25%" /><col width="25%" /><col width="10%" /><col width="10%" /><col width="30%" />
<tr>
<td>
	<input type="button" name="save_xldata" id="save_xldata" value="Save" class="button" onclick="saveXldata();" /></td>
</tr>
	<tr>
    	<th>S.No.</th>
    	<th style="text-align:center">Item Number</th>
    	<th style="text-align:center">Item Desc</th>
      	<th style="text-align:center">Quantity</th>
        <th style="text-align:center; display:none">Company</th>
        <th>&nbsp;</th>
        <th colspan="2" style="text-align:center">Status</th>
    </tr>
<?php
$part_list = array();
for ($i = 2; $i <= $row_count; $i++) {
	$trstyle=$i%2==0?" class='even datas' ": " class='odd datas' ";
	//item _number
	if(!isset($rows[$i][2])){
		$rows[$i][2] = '';
	}
	//item_desc
	if(!isset($rows[$i][3])){
		$rows[$i][3] = '';
	}
		//item qty
	if(!isset($rows[$i][4])){
		$rows[$i][4] = '';
	}

	
	if($rows[$i][2]){
		if($rows[$i][2] == '' || $rows[$i][4] == '' || (!in_array($rows[$i][2],$part_arr)) || !is_numeric($rows[$i][4])  || ($rows[$i][4] <= 0)){
			$duplicate = true;
		}else if(in_array($rows[$i][2], $part_list)){
			$duplicate = true;
		}
		else{
			$duplicate = false;
		}


	?>
   <tr<?php echo $trstyle;?> id="appendThisToForm">
   <td><?php echo $i-1;?></td>
   <td style="text-align:center"><ii><?php echo $rows[$i][2];?></ii> <input type="hidden" name="part_no[]" value="<?php echo rtrim(ltrim(str_replace('"',"'",$rows[$i][2])));?>" /></td>
    <td style="text-align:center"><ii><?php echo $rows[$i][3];?></ii> <input type="hidden" name="part_dsc[]" value="<?php echo rtrim(ltrim(str_replace('"',"'",$rows[$i][3])));?>" /></td>
    <td style="text-align:center"><ii><?php echo $rows[$i][4];?></ii> <input type="hidden" name="quantity[]" value="<?php echo rtrim(ltrim(str_replace('"',"'",$rows[$i][4])));?>" /></td>
   <td style="text-align:center; display:none"><input type="hidden" name="company[]" value="Default"/></td>

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
<?php 
$part_list[] = $rows[$i][2];
 } }?>
</table>
</div>
</form>