<table width="100%" cellpadding="0" cellspacing="0">
	<col /><col /><col /><col /><col /><col /><col />
    <thead>
    	<tr>
    		<th colspan="7" style="background:none repeat scroll 0 0 #00689C; text-align:left; color:#FFFFFF; padding: 8px 10px;" colspan="2"><?php echo sprintf($this->lang->line('reminder_report_mail'));?></th>
        </tr>
    </thead>
</table>
<table width="100%" cellpadding="0" cellspacing="0">
 <thead>
	<tr class="odd">
		<th style="text-align:left;background: none repeat scroll 0 0 #EEEEEE;border-bottom: 1px solid #00689C;font-weight: bold;line-height: 100% !important;padding: 6px 3px;color: #555555;"><?php echo $this->lang->line('S.No.');?></th>	
		<th style="text-align:center;background: none repeat scroll 0 0 #EEEEEE;border-bottom: 1px solid #00689C;font-weight: bold;line-height: 100% !important;padding: 6px 3px;color: #555555;"><?php echo $this->lang->line('call_id');?></th>
		<th style="text-align:center;background: none repeat scroll 0 0 #EEEEEE;border-bottom: 1px solid #00689C;font-weight: bold;line-height: 100% !important;padding: 6px 3px;color: #555555;"><?php echo $this->lang->line('servicecenter');?></th>
		<th style="text-align:center;background: none repeat scroll 0 0 #EEEEEE;border-bottom: 1px solid #00689C;font-weight: bold;line-height: 100% !important;padding: 6px 3px;color: #555555;"><?php echo $this->lang->line('reminder_remark');?></th>
		<th style="text-align:center;background: none repeat scroll 0 0 #EEEEEE;border-bottom: 1px solid #00689C;font-weight: bold;line-height: 100% !important;padding: 6px 3px;color: #555555;"><?php echo $this->lang->line('reminder_dt');?></th>					
	</tr>
    </thead>
    <tbody>
  <?php
        $i=1;
        $data = json_decode($json);
        foreach($data as $row){
        $trstyle=$i%2==0?' style="text-align:center;background: none repeat scroll 0 0 #F2F9FC;"':' style="text-align:center;"';
        ?>
        <tr<?php echo $trstyle;?>>
        <?php $j=1; foreach($row as $v){
			$tdstyle=($j==1)?' style="text-align:left;"':' style="text-align:center;"';
			if($i==count($data) && $j==1){
				$tdstyle=' style="text-align:left; font-weight:bold;color:#00689C;"';
			}else{
				if($i==count($data)){
					$tdstyle=' style="text-align:center; font-weight:bold;color:#00689C;"';
				}
			}
			?>
            <td<?php echo $tdstyle;?>><?php echo $v;?></td>
        <?php $j++; }?>
        </tr>
        <?php $i++;}?>
       </tbody> 
</table>
