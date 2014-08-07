<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Happycall extends Admin_Controller {
	 public function __construct() {
		parent::__construct();
		$this->load->language("happycallreport", $this->mdl_mcb_data->setting('default_language'));
		
		}
	function index()
	{
	$this->happycall();
	}
	
	function happycall()
	{
		$this->redir->set_last_index();
		$this->load->model(array('utilities/mdl_html','servicecenters/mdl_servicecenters','brands/mdl_brands','products/mdl_products'));
		$servicecenterOptions = $this->mdl_servicecenters->getServiceCentersOptions();
		$arr = array();
		$arr[] = '0';
		foreach($servicecenterOptions as $v){
			$arr[] = $v;
		}
		
		$servicecenter_select = $this->mdl_html->genericlist( $servicecenterOptions, "sc_id",array('class'=>'validate[required] text-input','multiple'=>'multiple','style'=>'height:120px;'),'value','text',$arr);
		$calltypeOptions = $this->mdl_mcb_data->getStatusOptions('calltype');

		$calltypeOptions[0]->text = 'None';
		$arr_ctype = array();
		foreach($calltypeOptions as $c){
			$arr_ctype[] = $c->value;
		}
		$calltype_select  =  $this->mdl_html->genericlist($calltypeOptions,'call_type',array('class'=>'validate[required] select-one','multiple'=>'multiple','style'=>'height:120px;'),'value','text',$arr_ctype);

		//select box for brand
		$brandOptions = $this->mdl_brands->getBrandOptions();
		//array_unshift($brandOptions, $this->mdl_html->option( '0', 'Select Brand'));
		$brand_select = $this->mdl_html->genericlist($brandOptions,'brand_id',array('onchange'=>'getProductBybrand($(this).val());','class'=>'validate[required] select-one','multiple'=>'multiple','style'=>'height:120px;'),'value','text',0);
		//brand select box ends here

		//select box for products
		$productOptions = $this->mdl_products->getProductOptions(0);
		$product_select = $this->mdl_html->genericlist($productOptions,'product_id',array('class'=>'validate[required] select-one','multiple'=>'multiple','style'=>'height:120px;'),'value','text',0);

		$data = array(
					'servicecenter_select'=>$servicecenter_select,
					'calltype_select'=>$calltype_select,
					'brand_select'=>$brand_select,
					'product_select'=>$product_select
		);
				
		$this->load->view('reports/happycallreport/index',$data);
		
		}
	
	
	function happycallreport()
	{	
		$this->load->helper('url');
		$this->load->library('ajaxpagination');
		$fromdate=$this->input->post('fromdate');
		$todate=$this->input->post('todate');
		$this->redir->set_last_index();
		$this->load->model(array('callcenter/mdl_call_happy',
								 'callcenter/mdl_call_happy_question',
								 'callcenter/mdl_calls_happy_answer',
								 'mcb_data/mdl_mcb_data'
								 ,'callcenter/mdl_callcenter','users/mdl_users'));
		
		
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		
		$results=$this->mdl_call_happy->getHappyCallList($page);		
		$config['total_rows'] = $results['count'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();
		
		$questions = $this->mdl_call_happy_question->getQuestionsreport();
		$question_count=$questions['total'];	
			
		$data = array(
					  'results'=>$results['result'],
					  'questions'=>$questions,
					  'fromdate'=>$fromdate,
					  'todate'=>$todate,
					  'page'=>$page,
					  'config'=>$config,
					  'navigation'=>$navigation,
					  'question_count'=>$question_count
						  
					  );
		/*echo('<pre>');
		print_r($data);
		die();*/
		$this->load->view('reports/happycallreport/happycalllist',$data);
		
	}
	function exceldownload()
	{
		$this->load->view('reports/happycallreport/exporttoexcel');
	}
	
	function getproductsbybrands()
	{
		$this->load->model(array('brands/mdl_brands','products/mdl_products','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$active = (int)$this->input->post('active');
		$productOptions = $this->mdl_products->getProductOptionsByBrands($this->input->post('brand_ids'));
		//array_unshift($productOptions,$this->mdl_html->option( '0', 'Select Product'));
		$product_select = $this->mdl_html->genericlist($productOptions,'product_id',array('class'=>'validate[required] select-one','multiple'=>'multiple','style'=>'height:120px;'),'value','text',0);
		echo $product_select;
		die();
	}
	
	function create_excel()
	{		
		$fromdate=$this->input->post('fromdate');
		$todate=$this->input->post('todate');
		$this->load->model(array('callcenter/mdl_call_happy',
								 'callcenter/mdl_call_happy_question',
								 'callcenter/mdl_calls_happy_answer',
								 'mcb_data/mdl_mcb_data'
								 ,'callcenter/mdl_callcenter','users/mdl_users'));
		
		$results=$this->mdl_call_happy->getHappyCallListCreateExcel();		
		$questions = $this->mdl_call_happy_question->getQuestionsreport();

		$question_count=$questions['total'];
			
		$data = array(
					  'results'=>$results,
					  'questions'=>$questions,
					  'fromdate'=>$fromdate,
					  'todate'=>$todate,
					  'question_count'=>$question_count
					  					  
					  );
		/*echo('<pre>');
		print_r($data);
		die();*/
		$this->load->view('reports/happycallreport/happycalllist_excel',$data);
		
		
		}
	
	
}
	
?>