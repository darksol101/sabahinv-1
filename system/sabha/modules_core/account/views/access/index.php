<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php $this->load->view('dashboard/header', array("header_insert"=>"script", "title"=>$this->lang->line('access_management'))); ?>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/style/css/jquery.ui.accordion.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo base_url();?>assets/style/css/base/jquery.ui.all.css" />
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/jquery/jquery.ui.widget.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/jquery/jquery.ui.accordion.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){	
	$( "#accordion" ).accordion({
		autoHeight: false,
		collapsible: true							
	});
})

</script>	
<div class="content-box-header">
<h3 style="cursor: s-resize;"><?php echo $this->lang->line('access');?></h3>
<ul class="content-box-tabs">
	<li><a class="default-tab" href="#tab1" id="account/access"><?php echo $this->lang->line('access')?></a></li>
	<li><a class="" href="#tab1" id="account/access/tasks"><?php echo $this->lang->line('access_tasks')?></a></li></ul>
<div class="clear"></div>
</div>
<div class="content-box-content">
<div id="tab1" class="tab-content default-tab">
<?php $this->load->view('dashboard/system_messages');?>
<?php 
/*$permissions = array(
			'view entry'=>'View Entry',
			'create entry'=>'Create Entry',
			'edit entry'=>'Edit Entry',
			'delete entry'=>'Delete Entry',
			'print entry'=>'Print Entry',
			'email entry'=>'Email Entry',
			'download entry'=>'Download Entry',
			'create ledger'=>'Create Ledger',
			'edit ledger'=>'Edit Ledger',
			'delete ledger'=>'Delete Ledger',
			'create group'=>'Create Group',
			'edit group'=>'Edit Group',
			'delete group'=>'Delete Group',
			//'create tag',
			//'edit tag',
			//'delete tag'=>'Delete tag',
			'view reports'=>'View Reports',
			'view log'=>'View Log',
			'clear log'=>'Clear Log',
			//'change account settings',
			'cf account'=>'CF Account',
			//'backup account',
			'view access'=>'View Access Management',
			'save access'=>'Save Access Management'
		);
	*/

		echo form_open('account/access/manage','name="access"');
		echo '<div id="accordion">';
		$i=1;
		foreach($access as $group){?>
			<h3 id="heading_<?php echo $group->usergroup_id;?>"><a href="#"><?php echo $group -> details;?></a></h3>
			<div id="access_content_<?php echo $group->usergroup_id;?>">	
			<table width="100%">
			<?php 
				$selected = false;
				$c = 1;
				$chek_all = false;
				$allowed = json_decode($group->access);
				if(is_object($allowed)){
					$tasks = $allowed->task;
					$chek_all = (count($tasks)==count($permissions))?true:false;
				}else{
					$tasks = array();
				}
				
				$data = array(
							    'name'        => 'chk_acess_all',
							    'id'          => 'chk_access_all_'.$group->usergroup_id,
							    'value'       => 1,
							    'checked'     => $chek_all,
							    'style'       => '',
							    'onclick' 	  => 'CheckAll(this)'
							    );
				echo '<span>'.form_checkbox($data).' All</span>';				
				foreach($permissions as $permission){
					//echo $permission->task_module;
					if(in_array($permission->task_name,$tasks)){
						$selected = true;
					}else{
						$selected =  false;
					}
					$data = array(
							    'name'        => 'chk_access['.$group->usergroup_id.'][]',
							    'id'          => 'chk_access_'.$i,
							    'value'       => $permission->task_name,
							    'checked'     => $selected,
							    'style'       => '',
							    );
					if($c%5==1){
						echo "<tr class=\"access_box\">";
					}
					echo '<td>'.ucfirst($permission->task_title).'</td>';
					echo '<td>'.form_checkbox($data).'</td>';
					if($c%5==0){ echo "</tr>";}
					$c++;
					$i++;
				}
				echo form_hidden('hdusergroup_id[]', $group->usergroup_id);
				echo form_hidden('hdnaccess_id['.$group->usergroup_id.']', $group->access_id);					
			?>
                        </table>
			</div>
		<?php 		
		}
		echo '</div>';
		echo form_submit('submit','Save','class="button"');
		echo form_close();		
?>
</div>
</div>
<?php $this->load->view('dashboard/footer');
