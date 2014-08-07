<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<script>
$(document).ready(function(){
	$(".pagination a").click(function(){
		$("#currentpage").val(this.id);
		getcallpartlist();
	})
});
</script>
    <table style="width: 100%" class="tblgrid" cellpadding="0" cellspacing="0">
        <col width="1%" /><col width="5%" /><col width="20%" />
        <thead>
            <tr>
                <th style="text-align: left;"><?php echo $this->lang->line('sn');?></th>
                <th><?php echo $this->lang->line('partnumber');?></th>
                <th style="text-align: center;"><?php echo $this->lang->line('description');?></th>
            </tr>
        </thead>
        <tbody>
        <?php
        $i=1;
        foreach ($parts as $part){
            $trstyle=$i%2==0?" class='even' ": " class='odd' ";
            ?>
            <tr <?php echo $trstyle;?>>
                <td style="text-align:left"><?php echo $i+$page['start'];?></td>
                <td><a class="setpartpending" style="cursor:pointer;"><?php echo $part->part_number;?></a></td>
                <td style="text-align: center;"><?php echo $part->part_desc;?></td>
            </tr>
            <?php
            $i++;
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td align="center" colspan="5">
                <div class="pagination"><?php echo $navigation;?></div>
                </td>
            </tr>
        </tfoot>
    </table>