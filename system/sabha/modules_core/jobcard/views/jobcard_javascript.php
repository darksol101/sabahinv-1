<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<div id="jobcard">
  <iframe id="ifmcontentstoprint" style="height: 0px; width: 0px; position: absolute"></iframe>
  <div id="cardContent" style="height:500px; overflow:auto; margin:0; ">
    <style>
#jobcard{width:1100px;}#jobcard td{font-size:11px!important;}.space{float:left;width:420px}.space1{float:left;width:245px}#facebox .popup #cardContent .tblgrid{background:#fff;border-collapse:collapse;border:0}#facebox .popup #cardContent .tblgrid td,#facebox .popup #cardContent .tblgrid th{border:1px solid #ccc}#facebox .popup #cardContent .tblgrid .tbl td,#facebox .popup #cardContent .tblgrid .tbl th{border:none}#facebox .popup #cardContent .tblgrid th{background:none;color:#000;height:21px}#facebox .popup #cardContent .tblgrid td{line-height:15px}#facebox .popup #cardContent .tblgrid tr{height:20px}#facebox .popup #cardContent .tblgrid .even{background:none repeat scroll 0 0 #F2F9FC}
.class1{border-top:none;border-left:none;border-right:none; vertical-align:middle;}
#facebox .content table td#class2{border-top:none;border-left:none;border-right:none; color:#00689C; font-size:20px!important; font-weight:bold;}
#class3{border-top:none;border-left:none;border-right:none; text-align:right;}
</style>
    <script type="text/javascript">
	<?php $i=0; foreach($jobcard as $preview_details){?> 
	var table = document.createElement('table');
	var tr = document.createElement('tr');
	var tbody = document.createElement('tbody');
	var td1 = document.createElement('td');
	var td2 = document.createElement('td');
	var td3 = document.createElement('td');
	var img1 = document.createElement('img');
	var img2 = document.createElement('img');
	img1.src="<?php echo base_url();?>assets/style/images/cglogo.jpg";
	td1.className = 'class1';
	table.setAttribute('cellPadding',0);
	table.setAttribute('cellSpacing',0);
	table.setAttribute('width','100%');
	td1.setAttribute('claspan',5);
	td1.appendChild(img1);
	tr.appendChild(td1);
	td2.setAttribute('id','class2');
	td2.setAttribute("colspan",3);
	td2.innerHTML='Customer Service Management';
	tr.appendChild(td2);
	img2.src='<?php echo base_url();?>assets/style/images/cgelectronics.jpg';
	td3.id='class3';
	td3.setAttribute('colspan',5);
	td3.appendChild(img2);
	tr.appendChild(td3);
	tbody.appendChild(tr);
	table.appendChild(tbody);
	//for next table
	document.getElementById('cardContent').appendChild(table);
	
	var table1_<?php echo $i;?> = document.createElement('table');
	var tbody1_<?php echo $i;?> = document.createElement('tbody');
	var tr1_<?php echo $i;?> = document.createElement('tr');
	var td1_<?php echo $i;?> = document.createElement('td');
	
	td1_<?php echo $i;?>.setAttribute('colspan','5');
	td1_<?php echo $i;?>.setAttribute('style','vertical-align:top;');
	
	var table11_<?php echo $i;?> = document.createElement('table');
	var tbody11_<?php echo $i;?> = document.createElement('tbody');
	var tr11_<?php echo $i;?> = document.createElement('tr');
	var td11_<?php echo $i;?> = document.createElement('td');
	var th11_<?php echo $i;?> = document.createElement('th');
	//inner table
	th11_<?php echo $i;?>.setAttribute('style','font-size:11px; text-align:left;');
	th11_<?php echo $i;?>.innerHTML = '<label>Name : </label>';
	td11_<?php echo $i;?>.setAttribute('style','font-size:11px; text-align:left;');
	td11_<?php echo $i;?>.innerHTML = '<span><?php echo $preview_details->cust_first_name.' '.$preview_details->cust_first_name;?></span>';
	tr11_<?php echo $i;?>.appendChild(th11_<?php echo $i;?>);
	tr11_<?php echo $i;?>.appendChild(td11_<?php echo $i;?>);
	tbody11_<?php echo $i;?>.appendChild(tr11_<?php echo $i;?>);
	table11_<?php echo $i;?>.appendChild(tbody11_<?php echo $i;?>);
	table11_<?php echo $i;?>.setAttribute('class','tbl');
	td1_<?php echo $i;?>.appendChild(table11_<?php echo $i;?>);
	//innet table ends here
	tr1_<?php echo $i;?>.appendChild(td1_<?php echo $i;?>);
	tbody1_<?php echo $i;?>.appendChild(tr1_<?php echo $i;?>);
	
	table1_<?php echo $i;?>.setAttribute('class','tblgrid');
	table1_<?php echo $i;?>.setAttribute('cellpadding',5);
	table1_<?php echo $i;?>.setAttribute('cellspacing',0);
	table1_<?php echo $i;?>.setAttribute('align','center');
	table1_<?php echo $i;?>.setAttribute('width','100%');
	table1_<?php echo $i;?>.setAttribute('class','tblgrid');
	table1_<?php echo $i;?>.appendChild(tbody1_<?php echo $i;?>);
	document.getElementById('cardContent').appendChild(table1_<?php echo $i;?>);		
    <?php $i++; }?>
	</script>
  </div>
  
</div>
