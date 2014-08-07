<style>
.first { background:#CCC}
.second { background:}
</style>

<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		showhappycalllist();
	})
});
</script>


<div align="right">
<input type="button" title="Excel Download" name="button" class="button" value="<?php echo ($this->lang->line('download'));?>" onclick="excelDownload();"/>
</div>
<div>&nbsp;</div>
<div style="float: right; margin-bottom: 5px;"><?php
	$page = $this->input->post('currentpage');
	$start = 0;
	if($config['total_rows']>0){
		$start = $page+1;
		if($config['total_rows']>($page+$config['per_page'])){
			$end = $page+$config['per_page'];
		}else{
			$end = $config['total_rows'];
		}
		?> 

  <span><strong><?php echo $start;?> - <?php echo $end?></strong></span> of <span><strong><?php echo $config['total_rows'];?></strong></span>
  <?php }?>
</div>
<div id="loading_icon">&nbsp;</div>

<form onsubmit="return false" name="fname" id="fname" method="post">
<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">

	<thead>
		<tr>
			<th align="left"><?php echo($this->lang->line('happy_call_report'));?></th>
            <th align="center"><?php echo (sprintf("Reports from %s  to %s",$fromdate,$todate));?></th>
		</tr>
	</thead>
</table>
</form>



<table id="tbl_happy_call_report" class="tblgrid" border="0" width="100%" cellpadding="0" cellspacing="0">

<thead>
<tr>
<th>S.N</th>
<th>Call Id</th>
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
<td> <?php echo $page+$j;?> </td>
<td style="color:#A9A9A9"; align="center"><?php echo $result->call_uid ;?></td>
<?php for($i=1; $i<= $question_count; $i++){  
$ans_desc=$this->mdl_call_happy->getAnswerDesc($result->call_id, $i);


if($i < 9 || $i>9){
	
if( !empty($ans_desc)){?> <td style="text-align:center;"><?php  echo $ans_desc[0]->answer_desc; ?></td><?php }else{ ?> <td> </td><?php } 
}

	else{
		$remarks=$this->mdl_call_happy->getRemarks($result->call_id); 
		
     if( !empty($remarks)){ ?>   <td><?php echo $remarks[0]->answer;?></td> <?php }else{ ?> <td> </td><?php } ?>

<?php } }?>
</tr>
<?php $j++; } ?>
</tbody>

<tfoot>
<tr><td colspan="11"></td></tr>
		<tr>
			<td align="center" colspan="11">
			<div class="pagination"><?php echo $navigation;?></div>
			</td>
		</tr>
	</tfoot>
    
</table>