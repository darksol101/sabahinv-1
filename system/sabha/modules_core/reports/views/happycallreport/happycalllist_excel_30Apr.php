<div align="right">
<input type="button" name="button" class="button" value="<?php echo ($this->lang->line('excel_download'));?>" onclick="excelDownload();"/>
</div>
	<form onsubmit="return false" name="fname" id="fname" method="post">
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">

	<thead>
		<tr>
			
            <th align="center" style="background:#00689C;color:#EEE;"><?php echo (sprintf("Reports from %s  to %s",$fromdate,$todate));?></th> 
		</tr>
	</thead>
</table>
</form>

<table border="1" id="tbl_happy_call_report" class="tblgrid" width="100%" cellpadding="0" cellspacing="0">
<thead>
<tr style="background:#00689C;color:#EEE;">
<th>S.N</th>
<th>Call Id</th>
<th>Created by</th>
<th>Created Date</th>
<th>Last Modified by</th>
<th>Modified Date</th>

<?php foreach($questions['list'] as $questions1) { ?>
<th><?php echo $questions1->question;?></th>
<?php } ?>
</tr>
</thead>
<?php
$j=1;
foreach ($results as $result) {
	$trclass=($j%2==0)?" class='even' ": " class='odd' "; ?>
<tbody>

<tr <?php echo $trclass; ?>>
<td> <?php echo $j;?> </td>
<td style="color:#A9A9A9"; align="center"><?php echo $result->call_uid ;?></td>
<td><?php if (!empty($result->created_by)) { echo $this->mdl_users->getUserNameByUserId($result->created_by); } else { echo '' ;}?></td>
<td><?php echo $result->created_date;?></td>
<td><?php if (!empty($result->modified_by)) {echo $this->mdl_users->getUserNameByUserId($result->modified_by);} else { echo '' ;};?></td>
<td><?php echo $result->modified_date;?></td>
<?php for($i=1; $i<= 9; $i++){  
$ans_desc=$this->mdl_call_happy->getAnswerDesc($result->call_id, $i);


if($i < 9){
	
if( !empty($ans_desc)){?> <td style="text-align:center;"><?php  echo $ans_desc[0]->answer_desc; ?></td><?php }else{ ?> <td> </td><?php } 
}

	else{
		$remarks=$this->mdl_call_happy->getRemarks($result->call_id); 
		//print_r($remarks);?>
     <?php if( !empty($remarks)){ ?>   <td><?php echo $remarks[0]->answer;?></td> <?php }else{ ?> <td> </td><?php } ?>

<?php } }?>
</tr>
<?php $j++; } ?>
</tbody>

</table>