<?php (defined('BASEPATH')) OR exit('no direct script access allowed');
class Vendors extends Admin_Controller{
    function __construct(){
        parent::__construct();
        $this->load->language('vendors',$this->mdl_mcb_data->setting('default_language'));
    }
    public function index(){  
   // print_r('m here'); die();
        $this->redir->set_last_index();
      
       $this->load->view('index');
    }		
		
	function savevendor(){
	  $this->redir->set_last_index();
	  $error = array();
	  $this->load->model(array('vendors/mdl_vendors'));
	  $vendor_id = $this->input->post('vendor_id');
	  $data=array(
				  "vendor_name"=>$this->input->post('vendor_name'),
				  "vendor_address"=>$this->input->post('vendor_address'),
				  "vendor_phone"=>$this->input->post('vendor_phone'),
				  "vendor_contact_person"=>$this->input->post('vendor_contact')
  				 );
	  if((int)$vendor_id==0){
		  $data["vendor_created_ts"]=date("Y-m-d H:i:s");
		  $data["vendor_created_by"]=$this->session->userdata('user_id');
		  if($this->mdl_vendors->save($data)){
				 $error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
		  }else{
			  $error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
		  }
	  }else{
		  $data["vendor_last_mod_ts"]=date("Y-m-d");
		  $data["vendor_last_mod_by"]=$this->session->userdata('user_id');
		  if($this->mdl_vendors->save($data,$vendor_id)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
		  }else{
			  $error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
		  }
	  }
	  $this->load->view('dashboard/ajax_messages',$error);
	}
	
	public function getvendorlist(){
		
        $this->redir->set_last_index();
        $this->load->model(array('vendors/mdl_vendors','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$currentpage = $this->input->post('currentpage');
		
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('results_per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
        $result = $this->mdl_vendors->getvenderlist($page);
	    $config['total_rows'] = $result['total'];
		
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();

		
		$data = array(
						 'lists'=>$result['list'],"ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page,'config'=>$config
						  );
		

       
        $this->load->view('vendorlist',$data);        
    }	
	
	public function getvendordetails(){
		$vendor_id = $this->input->post('vendor_id');
		$params=array(
					 "select"=>"vendor_id,vendor_name,vendor_address,vendor_contact_person,vendor_phone",
					 "where"=>array("vendor_id"=>$vendor_id),
					 "limit"=>1
					);
		$grouparr = $this->get($params);
		$group=$grouparr[0];
		echo json_encode($group);
	}
	function deletevendor() {
		$vendor_id=$this->input->post('vendor_id');
		$this->load->model('vendors/mdl_vendors');
		$success = $this->mdl_vendors->delete(array('vendor_id'=>$vendor_id));
		if($success==true){
			$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('vendor_not_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}    
    function get($params = NULL) {
		$this->load->model('vendors/mdl_vendors');
		return $this->mdl_vendors->get($params);
	}
	
	function create_excel(){
		$this->redir->set_last_index();
		ini_set("memory_limit","256M");
		$this->load->model(array('vendors/mdl_vendors','downloads/mdl_downloads'));
		$this->load->plugin('to_excel');
		$data = $this->mdl_vendors->getexcelDownload();
		$datas = array();
		$i=1;
		foreach ($data as $row){
			
			$row->sn= $i;
			$i++;
			$datas[]=$row;
			}
		
		$fields = array('S.N','Vendor Name','Vendor Phone','Vendor Address');
		$this->load->helper('download');
		$data = convert_to_table($datas,$fields);
		$name = 'vendor_list'.date("Y_m_d_H_i_s").'.xls';
		
		$this->load->library('user_agent');
		$download_data = array(
						  'download_session'=>$this->session->userdata('session_id'),
						  'download_type'=>'Vendor details report',
						  'user_agent'	=> $this->agent->agent_string(),
						  'user_ip'=>$this->input->ip_address(),
						  'download_by'=>$this->session->userdata('user_id'),
						  'download_ts'=>date('Y-m-d H:i:s')
		);
		$this->mdl_downloads->save($download_data);
		force_download($name, $data);	
	
}	
function generateexcel()
{	$this->load->library('session');
	$phnsearch=$this->input->post('phnsearch');
	$namesearch=$this->input->post('namesearch');
	$data=array('phnsearch_s'=>$phnsearch,
				'namesearch_s'=>$namesearch);
	$this->session->set_userdata($data);
		
}

}
?>