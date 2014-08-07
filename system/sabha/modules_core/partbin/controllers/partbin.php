<?php (defined('BASEPATH')) OR exit('no direct script access allowed');

class Partbin extends Admin_Controller {

    function __construct(){
        parent::__construct();
        $this->load->language('partbin',$this->mdl_mcb_data->setting('default_language'));
    }
    public function index(){
        $this->redir->set_last_index();
		$this->load->model(array('servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data', 'utilities/mdl_html','partbin/mdl_partbin'));
		if($this->session->userdata('usergroup_id')==1){
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptions();
			array_unshift($scentersOptions, $this->mdl_html->option( '', 'All Store'));
		}else{
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptionsBySc($this->session->userdata('sc_id'));
		}
		$servicecenters=$this->mdl_html->genericlist( $scentersOptions, "sc_id",array('class'=>'validate[required] text-input'),'value','text','');
		$servicecenter_search = $this->mdl_html->genericlist( $scentersOptions, "sc_id_search",array('class'=>'validate[required] text-input'),'value','text','');
		$data = array(
					  'servicecenters'=>$servicecenters,
					  'servicecenter_search'=>$servicecenter_search
					  
					  );
		
        $this->load->view('index',$data);
    }
	
	
	function savebin(){
	  $this->redir->set_last_index();
	  $error = array();
	  $this->load->model(array('partbin/mdl_partbin'));
	  $partbin_id = $this->input->post('partbin_id');
	 
	//print_r($this->input->post());
	// die();
	  $data=array(
				  "partbin_name"=>$this->input->post('bin_name'),
				  "partbin_desc"=>$this->input->post('bin_description'),
				  "sc_id" => $this->input->post('sc_id'),
				  
  				 );
	  if((int)$partbin_id==0){
		  $data["partbin_created_ts"]=date("Y-m-d H:i:s");
		  $data["partbin_created_by"]=$this->session->userdata('user_id');
		  if($this->mdl_partbin->save($data)){
				 $error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
		  }else{
			  $error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
		  }
	  }else{
	  	 
		  $data["partbin_last_mod_ts"]=date("Y-m-d");
		  $data["partbin_last_mod_by"]=$this->session->userdata('user_id');
		  if($this->mdl_partbin->save($data,$partbin_id)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
		  }else{
			  $error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
		  }
	  }
	  $this->load->view('dashboard/ajax_messages',$error);
	}
	
	
public function getpartbinlist(){
		
	 $this->redir->set_last_index();
        $this->load->model(array('partbin/mdl_partbin','mcb_data/mdl_mcb_data', 'utilities/mdl_html','servicecenters/mdl_servicecenters'));
	    $ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$currentpage = $this->input->post('currentpage');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('results_per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		
		 $result = $this->mdl_partbin->getpartbinlist($page);
	
		
        $lists = $result['list'];
		$config['total_rows'] = $result['total'];
		
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();

		
		$data = array(
						 'lists'=>$lists,"ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page,'config'=>$config
						  );
		
		
        $this->load->view('partbinlist',$data);        
    }
	
	public function getpartbindetail(){
		$partbin_id = $this->input->post('partbin_id');
		
		$this->load->model(array('partbin/mdl_partbin'));
		
		$grouparr = $this->mdl_partbin->getpartbindetail($partbin_id);
		
		$group=$grouparr[0];
	//print_r($grouparr);
		//die();
		echo json_encode($group);
	}
	
	function deletepartbin() {
		$partbin_id=$this->input->post('partbin_id');
		$this->load->model('partbin/mdl_partbin');
		$success = $this->mdl_partbin->delete(array('partbin_id'=>$partbin_id));
		if($success==true){
			$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_has_not_been_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	
	public function binindex(){
		$this->redir->set_last_index();
		
		$this->load->model(array('servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data', 'utilities/mdl_html','partbin/mdl_partbin','stocks/mdl_parts_stocks'));
		
		if($this->session->userdata('usergroup_id')==1){
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptions();
			array_unshift($scentersOptions, $this->mdl_html->option( '', 'All Store'));
		}else{
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptionsBySc($this->session->userdata('sc_id'));
		}

		$servicecenters=$this->mdl_html->genericlist( $scentersOptions, "sc_id",array('class'=>'validate[required] text-input','onchange'=>'getbinlist(this.value),getpartlist(this.value),showsearchbutton()'),'value','text','');
		
		$servicecenter_search = $this->mdl_html->genericlist( $scentersOptions, "sc_id_search",array('class'=>'validate[required] text-input','onchange'=>'getbinlist1(this.value)'),'value','text','');
		
		if($this->session->userdata('usergroup_id')==1){
		$partbin_name = $this->mdl_partbin->getbinnamebyscid(0);
		}
		else
		{
			$partbin_name = $this->mdl_partbin->getbinnamebyscid($this->session->userdata('sc_id'));
			}
		array_unshift($partbin_name, $this->mdl_html->option( '', 'Select Bin'));
		$partbin=$this->mdl_html->genericlist( $partbin_name, "partbin_name",array('class'=>'validate[required] text-input'),'value','text','');
		$partbin_search=$this->mdl_html->genericlist( $partbin_name, "partbin_name_search",array('class'=>'validate[required] text-input'),'value','text','');
		
		
		if($this->session->userdata('usergroup_id')==1){
				$parts = $this->mdl_parts_stocks->getPartAssignmentOptions(0);
		}
		else
		{
			$parts = $this->mdl_parts_stocks->getPartAssignmentOptions($this->session->userdata('sc_id'));
			}
		array_unshift($parts, $this->mdl_html->option( '', 'Select Item'));
		$parts=$this->mdl_html->genericlist( $parts, "part",array('class'=>'validate[required] text-input'),'value','text','');
		//$partbin_search=$this->mdl_html->genericlist( $partbin_name, "partbin_name_search",array('class'=>'validate[required] text-input'),'value','text','');
		
		
		
		
		
		
		//$parts = $this->mdl_parts_stocks->getPartAssignmentOptions();
		
		
		
		$data= array(
					 'servicecenters'=> $servicecenters,
					 'servicecenter_search'=> $servicecenter_search,
					 'partbin'=>$partbin,
					 'partbin_search'=>$partbin_search,
					 'parts'=>$parts
					 );
		$this->load->view('binindex',$data);
		
		
		}
		
		public function partbindetail(){
			$this->redir->set_last_index();
			$this->load->model(array('servicecenters/mdl_servicecenters','partbin/mdl_partbin_details','company/mdl_company','partbin/mdl_partbin','parts/mdl_parts'));
			$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$currentpage = $this->input->post('currentpage');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('results_per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$list =  $this->mdl_partbin_details->getdetail($page);
        $purchases = $list['list'];
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		$data = array(
						  'lists' =>$list,"ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page,'config'=>$config
						  );
			$this->load->view('partbin_details',$data);
			}
			
			
	function binnamelist(){
		$sc_id = $this->input->post('sc_id');
		
		$this->load->model(array('partbin/mdl_partbin','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$bin = $this->mdl_partbin->getbinnamebyscid($sc_id);
		array_unshift($bin, $this->mdl_html->option( '', 'Select Bin'));
		$bin_select  =  $this->mdl_html->genericlist($bin,'partbin_name',array('class'=>'validate[required] select-one'),'value','text','');
		echo $bin_select;
		}
		
	function getpart(){
		$sc_id = $this->input->post('sc_id');
		$this->load->model(array('stocks/mdl_parts_stocks','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$part = $this->mdl_parts_stocks->getPartAssignmentOptions($sc_id);

		array_unshift($part, $this->mdl_html->option( '', 'Select Item'));
		$part_select  =  $this->mdl_html->genericlist($part,'part_name',array('class'=>'validate[required] select-one'),'value','text','');
		echo $part_select;
		}
		
	function savebindetail(){
		
		
		$this->load->model(array('partbin/mdl_partbin_details'));
		$sc_id = $this->input->post('sc_id');
		$partbin = $this->input->post('partbin');
		
		$part = $this->input->post('part');
		$pt = explode (':',$part);
		$part_id = $pt[0];
		$company = $pt[1];


		//$check = $this->mdl_partbin_details->checkbin($sc_id,$partbin);
		//if ($check == 1){
		//	echo 1;
		//	die();
		//	}
	//	else{
			
			$data= array(
						'sc_id'=>$sc_id,
						'part_id'=>$part_id,
						'company_id'=>$company,
						'partbin_id'=>$partbin
						 );

			
			$this->mdl_partbin_details->save($data);
			echo 0;
			//}
		}
			
	function getPartList(){
		$sc_id = $this->input->post('sc_id');
		$datas = array(
					  'sc_id'=>$sc_id
					  );
		$this->load->view('pop/part_search',$datas);
		
		}
	function gertPartSearchList(){
		
		$this->redir->set_last_index();
		$this->load->model(array('stocks/mdl_parts_stocks','company/mdl_company','parts/mdl_parts'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$currentpage = $this->input->post('currentpage');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('results_per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$list =  $this->mdl_parts_stocks->getPartToAllocateByScId($page);
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		//print_r($list);
		$data = array(
						  'lists' =>$list,"ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page,'config'=>$config
						  );
			$this->load->view('pop/partsearchlist',$data);
		}
		
	
	function getBinList(){
		$sc_id = $this->input->post('sc_id');
		$datas = array(
					  'sc_id'=>$sc_id
					  );
		$this->load->view('pop/bin_search',$datas);
		
		
		}
		
		
   function getBinSearchList(){
		
		$this->redir->set_last_index();
		$this->load->model(array('stocks/mdl_parts_stocks','partbin/mdl_partbin','servicecenters/mdl_servicecenters'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$currentpage = $this->input->post('currentpage');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('results_per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$list =  $this->mdl_partbin->getpartbinlist($page);
		$config['total_rows'] = $list['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		//print_r($list);
		$data = array(
						  'lists' =>$list,"ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page,'config'=>$config
						  );
			$this->load->view('pop/binsearchlist',$data);
		}
	
}
?>