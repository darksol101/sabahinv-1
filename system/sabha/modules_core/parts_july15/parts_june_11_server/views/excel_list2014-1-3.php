<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<style type="text/css">

</style>
<form method="post" name="xlForm" id="xlForm">
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
<col width="1%" /><col /><col /><col /><col /><col /><col width="10%" /><col width="20%" />
<tr>
<td><input type="button" name="save_xldata" id="save_xldata" value="Save" class="button" onclick="saveXldata();" /></td>
</tr>
<tr>
   	<th>S.No.</th>
   	<th>Item Number</th>
       <th>Item description</th>
       <th style="text-align:center">Landing Price</th>
       <th style="text-align:center">Customer Price</th>
       <th style="text-align:center">Store Price</th>
       <th colspan="2" style="text-align:center">Status</th>
   </tr>

<?php
$temp = array();
$error_status = false;
for ($i = 2; $i <= $row_count; $i++) {
$dup_class = '';
//$temp = $rows[$i][3];
$duplicate_row = false;
if(in_array($rows[$i][2],$temp)){
$duplicate_row = true;
$dup_class = ' duplicate';
$error_status = true;
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
if($rows[$i][2]){
if(in_array(strtoupper($rows[$i][2]),strtoupper($part_arr))){
$duplicate = true;
}else{
$duplicate = false;
}
if(in_array($rows[$i][2],$temp)){
$duplicate = true;
}


$temp[] =  strtoupper ($rows[$i][2]);	
$trstyle=$i%2==0?" class='even".$dup_class."' ": " class='odd".$dup_class."' ";
//echo '<pre>';	
//print_r($temp);
//echo '</pre>';
//echo $dup_class;
?>
   <tr<?php echo $trstyle;?>>
   	<td><?php echo $i-1;?></td>
   	<td><?php echo $rows[$i][2];?><input type="hidden" name="part_no[]" value="<?php echo rtrim(ltrim(str_replace('"',"'",$rows[$i][2])));?>" /></td>
       <td><?php echo $rows[$i][3];?><input type="hidden" name="part_dsc[]" value="<?php echo rtrim(ltrim(str_replace('"',"'",$rows[$i][3])));?>" /></td>
       <td style="text-align:center"><?php echo $rows[$i][4];?><input type="hidden" name="company[]" value="<?php echo str_replace (',','',$rows[$i][4]);?>" /></td>
       <td style="text-align:center"><?php echo $rows[$i][5];?><input type="hidden" name="quantity[]" value="<?php echo str_replace (',','',$rows[$i][5]);?>" /></td>
        <td style="text-align:center"><?php echo $rows[$i][6];?><input type="hidden" name="quantity_sc[]" value="<?php echo str_replace (',','',$rows[$i][6]);?>" /></td>
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