<style type="text/css">
.tool-icon a{padding:5px;}
#main-content .tblbox table.tbl tfoot tr td{ border-top: 1px solid #00689C;}
</style>
<form onsubmit="return false" name="fname" id="fname" method="post">
	<table width="100%" cellpadding="0" cellspacing="0" class="tblgrid">
		<col /><col /><col /><col /><col /><col /><col />
        <thead>
            <tr>
                <th colspan="6"><?php echo sprintf($this->lang->line('closed_call_report'),$report_from_date,$report_to_date);?></th>
                <th style="text-align:right">
                    <div class="tool-icon">
                        <a class="btn" onclick="export_exl();" title="Download as excel"><?php echo icon('excel-download','Download as excel','png');?></a><a class="btn" onclick="email_pop();" title="E-mail"><?php echo icon('mail','E-mail','png');?></a>
                    </div>
                </th>
            </tr>
        </thead>
	</table>
	<div class="tblbox" id="tblhtml">
    <table width="100%" cellpadding="0" cellspacing="0" class="tbl tblgrid" id="tbldata">
    	<col /><col /><col /><col /><col /><col /><col />
        <thead>
            <tr>
                <th style="text-align:left"><?php echo $this->lang->line('serivcecenter');?></th>
                <th style="text-align:left"><?php echo $this->lang->line('total_closed_calls');?></th>
                <th style="text-align:center"><?php echo $this->lang->line('average_closing');?></th>
                <th style="text-align:center"><?php echo $this->lang->line('tat_less_than_2days');?></th>
                <th style="text-align:center"><?php echo $this->lang->line('tat_between_2and7');?></th>
                <th style="text-align:center"><?php echo $this->lang->line('tat_between_7and15');?></th>
                <th style="text-align:center"><?php echo $this->lang->line('tat_greater_than15');?></th>
            </tr>
        </thead>   
        <tbody>
        <?php
        $i=1;
        $total_closed_calls = 0;
        $total_average_closing = 0;
        $total_tat_less_than_2days = 0;
        $total_tat_between_2and7 = 0;
        $total_tat_between_7and15 = 0;
        $total_tat_greaterthan15 = 0;
        foreach($service_centers as $service_center){
            $trstyle=$i%2==0?" class='even' ": " class='odd' ";
            $service_center->total_closed_calls= $this->mdl_closedcallreports->getTotalClosedCallsByDate($service_center->value);
            $total_closed_calls+=$service_center->total_closed_calls;
            
            $service_center->average_closing= $this->mdl_closedcallreports->getAverageClosingTimeByDate($service_center->value);
            $total_average_closing+=$service_center->average_closing;
            
            $service_center->tat_less_than_2days= $this->mdl_closedcallreports->getClosedCallsByTimeLess($service_center->value);
            $total_tat_less_than_2days+=$service_center->tat_less_than_2days;
            
            $service_center->tat_between_2and7= $this->mdl_closedcallreports->getClosedCallsByTimeBetween2and7($service_center->value);
            $total_tat_between_2and7+=$service_center->tat_between_2and7;
            
            $service_center->tat_between_7and15= $this->mdl_closedcallreports->getClosedCallsByTimeBetween7and15($service_center->value);
            $total_tat_between_7and15+=$service_center->tat_between_7and15;
            
            $service_center->tat_greaterthan15= $this->mdl_closedcallreports->getClosedCallsByTimeGreaterthan15($service_center->value);
            $total_tat_greaterthan15+=$service_center->tat_greaterthan15;
        ?>
            <tr<?php echo $trstyle;?>>
                <td style="text-align:left"><?php echo $service_center->text;?></td>
                <td style="text-align:center"><?php echo $service_center->total_closed_calls;?></td>
                <td style="text-align:center"><?php echo $service_center->average_closing;?></td>
                <td style="text-align:center"><?php echo $service_center->tat_less_than_2days;?></td>
                <td style="text-align:center"><?php echo $service_center->tat_between_2and7;?></td>
                <td style="text-align:center"><?php echo $service_center->tat_between_7and15;?></td>
                <td style="text-align:center"><?php echo $service_center->tat_greaterthan15;?></td>
            </tr>
        <?php $i++;}?>
        </tbody>
        <tfoot>
            <tr>
                <td style="text-align:right"><strong>Total</strong></td>
                <td style="text-align:center"><strong><?php echo $total_closed_calls;?></strong></td>
                <td style="text-align:center"><strong><?php echo ((int)$total_average_closing>$total_average_closing)?$total_average_closing.' '.$this->lang->line('days'):$total_average_closing.' '.$this->lang->line('day');?></strong></td>
                <td style="text-align:center"><strong><?php echo $total_tat_less_than_2days;?></strong></td>
                <td style="text-align:center"><strong><?php echo $total_tat_between_2and7;?></strong></td>
                <td style="text-align:center"><strong><?php echo $total_tat_between_7and15;?></strong></td>
                <td style="text-align:center"><strong><?php echo $total_tat_greaterthan15;?></strong></td>
            </tr>
        </tfoot>
    </table>
</div>
<input type="hidden" name="content" id="content" value="" />
<input type="hidden" name="report_from_date" id="report_from_date" value="<?php echo $report_from_date;?>" />
<input type="hidden" name="report_to_date" id="report_to_date" value="<?php echo $report_to_date;?>" />
</form>