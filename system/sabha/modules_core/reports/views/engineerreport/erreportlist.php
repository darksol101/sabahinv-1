
<script type="text/javascript">
$(function(){          


       $("#excel").click(function() {                                                                          
               var data='<table>'+$("#engineerclaim").html().replace(/<a\/?[^>]+>/gi, '')+'</table>';
               $('body').prepend("<form method='post' action='<?php echo base_url();?>reports/engineerreport/excelprint' style='display:none' id='engineerclaim'><input type='text' name='tableData' value='"+data+"' ></form>");
                $('#engineerclaim').submit().remove();
                return false;
       });

});
</script>



<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>

<table style="width:100%;" class="tblgrid" id="engineerclaim">
    	<col width="1%" />
        <col width="20%" />
        <col width="20%" />
        <col width="15%" />
         <col width="15%" />
        <col width="10%" />
        <col width="5%" />
        <col width="5%" />
        	
            <thead>
       
            <tr>
                <th>S.No.</th>
                <th >Engineer Name</th>
                <th >Verified Call</th>
                 <th>Part Pending Call</th>
                <th>Closed Call</th>
                <th>Cancelled call</th>
                <th>
                
                
                
                
                
                
                
                
                <div class="tool-icon"><label id="excel"> <?php echo icon('excel-download','Download as excel','png');?></label>
			</div></th>
            </tr>
            </thead>
        <tbody>
        
        <?php
		$i=1;		
		foreach($engineer_id as $engineer){
			$engineer_name= $this->mdl_engineers->getengineer_call($engineer);
			$verified = $this->mdl_callcenter->getengineerverifiedcall($engineer);
			$partpending = $this->mdl_callcenter->getengineerpartpendingcall($engineer);
			$cancelled = $this->mdl_callcenter->getengineercancelledcall($engineer);
			$closed = $this->mdl_callcenter->getengineerclosedcall($engineer);
			
		$trstyle=$i%2==0?" class='even' ": " class='odd' ";
		?>
	
            <tr <?php echo $trstyle;?>>
                <td><?php echo $i;?></td>
                <td ><?php echo $engineer_name->engineer_name;?></td>
                <td ><a href="<?php site_url();?>engineerreport/claimdetail/<?php echo $engineer;?>"><?php echo $verified->cnt;?></td>
                 <td><?php echo $partpending->cnt?></td>
                  <td><?php echo $closed->cnt;?></td>
                <td> <?php echo $cancelled->cnt;?></td>
                <td></td>
                
            </tr>
        <?php $i++; } ?>
        </tbody>
        
    </table>