<?php defined('BASEPATH') or exit('Direct access script is not allowed');?>

<table style="width:100%;" class="tblgrid">
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
                <th></th>
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
                <td ><?php echo $verified->cnt;?></td>
                 <td><?php echo $partpending->cnt?></td>
                  <td><?php echo $closed->cnt;?></td>
                <td> <?php echo $cancelled->cnt;?></td>
                
            </tr>
        <?php $i++; } ?>
        </tbody>
        
    </table>