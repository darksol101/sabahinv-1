<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Parts extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("parts",  $this->mdl_mcb_data->setting('default_language'));
		$this->_post_handler();
	}

	function index() {
		$this->redir->set_last_index();
		$this->load->model(array('parts/mdl_parts','brands/mdl_brands','products/mdl_products','productmodel/mdl_productmodel','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$ustatuslist=$this->mdl_mcb_data->getStatusOptions('ustatus');
		$brand_status=$this->mdl_html->genericlist( $ustatuslist, 'brand_status' );
		$product_status=$this->mdl_html->genericlist( $ustatuslist, 'product_status' );
		$brandOptions = $this->mdl_brands->getBrandOptions();
		array_unshift($brandOptions, $this->mdl_html->option( '', 'Select Brand'));
		$brand_select = $this->mdl_html->genericlist($brandOptions,'brand_select',array('onchange'=>'getProductBybrand($(this).val());','class'=>'validate[required] select-one'),'value','text','');

		$productOptions=array();
		array_unshift($productOptions, $this->mdl_html->option( '', 'Select Product'));
		$product_select = $this->mdl_html->genericlist($productOptions,'product_select',array('class'=>'validate[required] text-input','onchange'=>'getModelsByProduct(this.value)'),'value','text','');

		//select box for models
		$modelOptions = $this->mdl_productmodel->getModelOptionsByProduct('');
		array_unshift($modelOptions, $this->mdl_html->option( '', 'Select Model'));
		$model_select = $this->mdl_html->genericlist($modelOptions,'model_select',array('class'=>'validate[required] select-one'),'value','text','');

		$data = array(
					  'brand_select'=>$brand_select,
					  'product_select'=>$product_select,
					  'model_select'=>$model_select
		);
		$this->load->view('index',$data);
	}

	function getpartlist(){
		$this->redir->set_last_index();
		$this->load->model(array('parts/mdl_parts','products/mdl_products','brands/mdl_brands','productmodel/mdl_productmodel','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$brands=$this->mdl_parts->getParts($page);
		$config['total_rows'] = $brands['total'];
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();

		$data=array(
					"parts"=>$brands['list'],
					"ajaxaction"=>$ajaxaction,
					"page"=>$page,
					"navigation"=>$navigation
		);
		$this->load->view('partslist', $data);
	}
	/*function getproductsbybrand()
	{
		$this->load->model(array('products/mdl_products','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$active = (int)$this->input->post('active');
		$productOptions = $this->mdl_products->getProductsByBrand($this->input->post('brand_id'));
		array_unshift($productOptions,$this->mdl_html->option( '', 'Select Product'));
		$productlist = $this->mdl_html->genericlist($productOptions,'product_select',array('class'=>'validate[required] text-input','onchange'=>'getModelsByProduct(this.value)'),'value','text',$active);
		echo $productlist;
	}*/
	function getmodelsbyproduct(){
		$this->redir->set_last_index();
		$this->load->model(array('productmodel/mdl_productmodel','mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
		$product_id = (int)$this->input->post('product_id');
		$active = $this->input->post('active');
		$modelOptions = $this->mdl_productmodel->getModelOptionsByProduct($product_id);
		array_unshift($modelOptions, $this->mdl_html->option( '', 'Select Model'));
		$model_select = $this->mdl_html->genericlist($modelOptions,'model_select',array('class'=>'validate[required] select-one'),'value','text',$active);
		echo $model_select;
		die();
	}
	function getpartdetails(){
		$this->redir->set_last_index();
		$this->load->model(array('parts/mdl_parts','productmodel/mdl_productmodel','products/mdl_products','brands/mdl_brands'));
		$part_id = (int)$this->input->post('id');
		$part=$this->mdl_parts->getPartDetails($part_id);
		echo json_encode($part);
	}

	function savepart(){
		$this->redir->set_last_index();
		$error = array();
		$this->load->model(array('parts/mdl_parts','products/mdl_products'));
		$part_id = $this->input->post('part_id');
		$part_number = $this->input->post('part_number');
		$part_desc = $this->input->post('description');
		
		$part_customer_price = $this->input->post('part_customer_price');
		$part_sc_price = $this->input->post('part_sc_price');
		$part_landing_price = $this->input->post('part_landing_price');
		
		$data['part_number'] = $part_number;
		$data['part_desc'] = $part_desc;
		$data['part_customer_price'] = $part_customer_price;
		$data['part_sc_price'] = $part_sc_price;
		$data['part_landing_price'] = $part_landing_price;

		if((int)$part_id==0){
			$data["part_created_ts"]=date("Y-m-d H:i:s");
			$data["part_created_by"]=$this->session->userdata('user_id');
			if($this->mdl_parts->save($data)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
			}else{
				$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
			}
		}else{
			$data["part_last_mod_ts"]=date("Y-m-d");
			$data["part_last_mod_by"]=$this->session->userdata('user_id');
			if($this->mdl_parts->save($data,$part_id)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
			}else{
				$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_saved'));
			}
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}

	function deletepart() {
		$this->load->model(array('parts/mdl_parts','products/mdl_products'));
		$brand_id=$this->input->post('part_id');
		if($this->mdl_parts->delete(array('part_id'=>$brand_id))){
			$error = array('type'=>'warning','message'=>$this->lang->line('this_record_has_been_deleted'));
		}else{
			$error = array('type'=>'failure','message'=>$this->lang->line('this_record_can_not_be_deleted'));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function getbrandproductlist(){
		$this->load->model(array('products/mdl_products','productmodel/mdl_productmodel','brands/mdl_brands','parts/mdl_model_parts','utilities/mdl_html'));
		$brands = $this->mdl_brands->getBrandOptions();
		$part_number = $this->input->post('part_number');
		
		$parts_assign_brand = $this->mdl_model_parts->getModelParts($part_number);

		$parts_assign_brands = array();
		foreach($parts_assign_brand as $brand){
			$parts_assign_brands[] = $brand->brand_id;
		}
		$ajaxaction = $this->input->post('ajaxaction');
		$data = array(
					  'brands'=>$brands,
					  'parts_assign_brands'=>$parts_assign_brands,
					  'ajaxaction'=>$ajaxaction,
					  'part_number'=>$part_number
		);
		$this->load->view('modelassign',$data);
	}
	function getproductsbybrand()
	{
		$this->redir->set_last_index();
		$this->load->model(array('brands/mdl_brands','products/mdl_products','productmodel/mdl_productmodel','parts/mdl_model_parts'));

		$brands = $this->input->post('brand_id');
		$products = $this->mdl_products->getProductsByBrands($brands);
		$part_number = $this->input->post('part_number');
		$parts_assign_product = $this->mdl_model_parts->getModelParts($part_number);
		$parts_assign_products = array();
		foreach($parts_assign_product as $product){
			$parts_assign_products[] = $product->product_id;
		}
		$ajaxaction = $this->input->post('ajaxaction');
		$data = array(
					  'ajaxaction'=>$ajaxaction,
					  'parts_assign_products'=>$parts_assign_products,
					  'products'=>$products
		);
		$this->load->view('listproductsbybrand',$data);
	}
	function getmodelsbyproducts(){
		$this->redir->set_last_index();
		$this->load->model(array('brands/mdl_brands','products/mdl_products','productmodel/mdl_productmodel','parts/mdl_model_parts'));
		$product_id = $this->input->post('product_id');
		$part_number = $this->input->post('part_number');
		$models = $this->mdl_productmodel->getDistinctModelsByProducts($product_id);
		$parts_assign_model = $this->mdl_model_parts->getModelParts($part_number);
		$parts_assign_models = array();
		foreach($parts_assign_model as $model){
			$parts_assign_models[] = $model->model_number;
		}
		$data = array(
					  'parts_assign_models'=>$parts_assign_models,
					  'models'=>$models
		);
		$this->load->view('listmodelsbyproduct',$data);
	}
	function savemodelspart(){
		$this->redir->set_last_index();
		$error = array();
		$this->load->model(array('parts/mdl_parts','parts/mdl_model_parts'));
		$part_number = $this->input->post('part_number');
		$arr_model = $this->input->post('model');
		if (count($arr_model)>0 && is_array($arr_model)){
			$arr = array();
			$str = '';
			foreach($arr_model as $row){
				$arr[] = '("'.$part_number.'","'.$row.'")';
			}
			$str = implode(",",$arr);
			$sql = 'REPLACE INTO '.$this->mdl_model_parts->table_name.'(part_number,model_number) VALUES '.$str.';';
			$result = $this->db->query($sql);
			if($result>0){
				$error = array('type'=>'success','message'=>sprintf($this->lang->line('models_has_been_added'),$part_number));
			}else{
				$error = array('type'=>'failure','message'=>sprintf($this->lang->line('models_could_not_be_added'),$part_number));
			}
		}else{
			$this->mdl_model_parts->delete(array('part_number'=>$part_number));
			$error = array('type'=>'failure','message'=>sprintf($this->lang->line('models_has_been_removed'),$part_number));
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}
	function generatetemplateexl(){
		$this->load->library('parser');
		$this->load->helper('file');
		$data = array();

		//$data = $this->parser->parse('exl_template',$data,TRUE);
		$data = file_get_contents("uploads/temp/excel_template.xls");
		$this->load->helper('download');
		$name = 'excel_template_'.date("Y_m_d_H_i_s").'.xls';
		/*
		 **keep track of downloads
		 */
		$this->load->library('user_agent');
		$this->load->model('downloads/mdl_downloads');
		$download_data = array(
						  'download_session'=>$this->session->userdata('session_id'),
						  'download_type'=>'excel_template',
						  'user_agent'	=> $this->agent->agent_string(),
						  'user_ip'=>$this->input->ip_address(),
						  'download_by'=>$this->session->userdata('user_id'),
						  'download_ts'=>date('Y-m-d H:i:s')
		);
		$this->mdl_downloads->save($download_data);
		/*
		 **ends here
		 */
		force_download($name, $data);
		
	}
	function uploadform()
	{
		$this->redir->set_last_index();
		$this->load->view('uploadform');
	}
	function upload()
	{
		$this->load->model(array('utilities/mdl_html'));
		$config['upload_path'] = './uploads/temp/';
		$config['allowed_types'] = 'xls|xlsx';
		$config['file_name'] = $this->session->userdata('session_id').'_'.date('Y_m_d_H_i_s');
		//$config['file_name'] = 'excel_file';
		$this->load->library('upload', $config);
		$file_name = 'excel_file';
		if ( ! $this->upload->do_upload($file_name))
		{
			$error = array('error' => $this->upload->display_errors());
		}
		else
		{
			$arr = $this->upload->data();
			$data = array('upload_data' => $arr);
			$this->load->library('spreadsheet_excel_reader');
			$path = str_replace("/","\\",$arr['full_path']);
			$this->spreadsheet_excel_reader->read($path);
			$rows = $this->spreadsheet_excel_reader->sheets[0]['cells'];
			$row_count = count($this->spreadsheet_excel_reader->sheets[0]['cells']);
			$this->load->model(array('parts/mdl_parts'));
			$parts = $this->mdl_parts->getPartOptions();
			
			$part_arr = array();
			foreach($parts as $part){
				$part_arr[] = trim($part->text," ");
			}
			
			$data = array(
						  'rows'=>$rows,
						  'row_count'=>$row_count,
						  'part_arr'=>$part_arr
						  );
			@unlink($path);
			
			$this->load->view('excel_list',$data);
		}
	}
	function get($params = NULL) {
		return $this->mdl_users->get($params);
	}
	function savep()
	{
		
			$this->load->model(array('parts/mdl_parts'));
			$part_no = $this->input->post('part_no');
			$part_dsc = $this->input->post('part_dsc');
			$part_lp = $this->input->post('part_lp');
			$part_cp = $this->input->post('part_cp');
			$part_sp = $this->input->post('part_sp');
			$task_select = $this->input->post('task_select');
			$i=0;
			$cnt = 0;
			$error = array();
			foreach($task_select as $task){
				if($task<3 && $task>=0){
					if($task==1){
						$data = array(
									  'part_number'=>$this->db->escape_like_str($part_no[$i]),
									  'part_desc'=>$this->db->escape_like_str($part_dsc[$i]),
									  'part_landing_price'=>$part_lp[$i],
									  'part_customer_price'=>$part_cp[$i],
									  'part_sc_price'=>$part_sp[$i]
									  );
						if($this->db->insert($this->mdl_parts->table_name,$data)){
							//echo $this->db->last_query();
						}else{
						}
					}else{
						$data = array(
									  'part_desc'=>$part_dsc[$i],
									  'part_landing_price'=>$part_lp[$i],
									  'part_customer_price'=>$part_cp[$i],
									  'part_sc_price'=>$part_sp[$i]
									  );
						$this->db->where('part_number', $part_no[$i]);
						$this->db->update($this->mdl_parts->table_name, $data); 						
					}
				}
				$i++;
			}
			//$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_uploaded'));
			redirect('parts');
			//$this->load->view('dashboard/ajax_messages',$error);
	}
	function _post_handler(){
		if($this->input->post('save_xldata')){
			$this->load->model(array('parts/mdl_parts'));
			$part_no = $this->input->post('part_no');
			$part_dsc = $this->input->post('part_dsc');
			$part_lp = $this->input->post('part_lp');
			$part_cp = $this->input->post('part_cp');
			$part_sp = $this->input->post('part_sp');
			$task_select = $this->input->post('task_select');
			$i=0;
			foreach($task_select as $task){
				if($task<3 && $task>=0){
					if($task==1){
						$data = array(
									  'part_number'=>$part_no[$i],
									  'part_desc'=>$part_dsc[$i],
									  'part_landing_price'=>$part_lp[$i],
									  'part_customer_price'=>$part_cp[$i],
									  'part_sc_price'=>$part_sp[$i]
									  );
						$this->db->insert($this->mdl_parts->table_name,$data);
					}else{
						$data = array(
									  'part_desc'=>$part_dsc[$i],
									  'part_landing_price'=>$part_lp[$i],
									  'part_customer_price'=>$part_cp[$i],
									  'part_sc_price'=>$part_sp[$i]
									  );
						$this->db->where('part_number', $part_no[$i]);
						$this->db->update($this->mdl_parts->table_name, $data); 
					}
				}
				$i++;
			}
			redirect('parts');
		}
	}
}
?>