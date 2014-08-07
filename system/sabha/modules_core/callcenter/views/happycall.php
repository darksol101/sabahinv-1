
<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>


<script type="text/javascript">

function closehappyform($callid){
	window.location = '<?php echo site_url();?>callcenter/callregistration/'+$callid;
	
	};

</script>
<form method="post" name="happycall" id="happycall">

<table style="width:100%;" class="tblgrid">
    	 <col width="1%" />
        <col width="5%" />
        <col width="3%" />
        <col width="1%" />
            <thead>
            <tr>
                <th><?php echo $this->lang->line('sn.');?></th>
                <th><?php echo $this->lang->line('questions');?></th>
                <th style="text-align:center"><?php echo  $this->lang->line('answers');?></th>
                
                <th></th>
            </tr>
            </thead>
        <tbody>
        
        <?php
		$i=1;		
		foreach($questions as $question){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
	
            <tr <?php echo $trstyle;?>>
                <td><?php echo $i;?></td> 
                <td>  <?php echo $question->question;?> <input type="hidden" name="question[]" onclick = "javascript:validate(this.value);"value="<?php echo $question->question_id;?>" /> </td>
                <?php if ($question->question_type == 1){
					$answer_option= $this->mdl_calls_happy_answer->getAnswersOptionsByQuestion($question->question_id);
					
					$answer= $this->mdl_call_happy->getAnsByQuestion($question->question_id,$callid);
					
				
					array_unshift($answer_option, $this->mdl_html->option( '', 'Your Answer'));
					
					$answer_option  =  $this->mdl_html->genericlist($answer_option,'answer[]',array('class'=>'validate[required] text-input' ),'value','text',$answer);
					?>
                    <td style="text-align:center"><?php echo $answer_option;?></td>
					
					
					<?php }else {?>
                	<?php $answer= $this->mdl_call_happy->getAnsByQuestion($question->question_id,$callid);?>
                <td style="text-align:center"><input type="text"  class="text-input"  name="answer[]" value="<?php echo $answer;?>" /></td>
                <?php }?>
                <td> </td>
                
            </tr>
        <?php $i++; } ?>
        <tr>
        <td><?php echo $i;?> </td>
        <td> Call At </td>
        <td style="text-align:center"> <?php echo $callat_select;?> </td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
       <td colspan="3" style="text-align:center"> <?php if ($this->session->userdata('usergroup_id')!=4){?><input type="submit" name="savehappyorder"   value="Verified" class="button"  /><?php }?>
        <input type="button" name="happycall" id="happycall" value="Close" class="button" onclick="javascript:closehappyform(<?php echo $callid; ?>);" />
        <input type="button"  value="Re-Open" class="button" onclick="reopen(<?php echo $callid; ?>);" />
          <input type="button"  value="Called" class="button" onclick="happycalled(<?php echo $callid; ?>);" />
        </td>
        </tr>
        </tfoot>
      
    </table>
     <?php if (!empty($records)) {?>
   <table style="width:100%;" class="tblgrid">
    	 <col width="10%" />
        <col width="50%" />
        <col width="30%" />
       
            <thead>
            <tr>
            <th><?php echo 'S.No';?></th>
                <th><?php echo 'Called Date';?></th>
                <th><?php echo 'Remarks';?></th>
                <th></th>
            </tr>
            </thead>
        <tbody>
        
        <?php
		$i=1;		
		foreach($records as $record){
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
	
            <tr <?php echo $trstyle;?>>
                <td><?php echo $i;?></td> 
                <td>  <?php echo $record->date;?> </td>
                 <td>  <?php echo $record->remark;?> </td>
                <td> </td>
                
            </tr>
        <?php $i++; } ?>
        </tbody>
       <?php }?>
       
    </table>
    </form>