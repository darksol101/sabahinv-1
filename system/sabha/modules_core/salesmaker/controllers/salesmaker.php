<?php defined('BASEPATH') or die('Direct access script in not allowed');
class Salesmaker extends Admin_Controller{

	function __construct(){
		parent::__construct();
		$this->load->language('salesmaker',$this->mdl_mcb_data->get('salesmaker'));
	}
	

	public function index()
	{


	$this->redir->set_last_index();
	$this->load->model( array('salesmaker/mdl_Salesmaker','mcb_data/mdl_mcb_data','utilities/mdl_html','brands/mdl_brands','products/mdl_products','productmodel/mdl_productmodel','category/mdl_category','parts/mdl_parts'));
	
	/*select options*/

	//assign sale to
	$sale_maker_assign = $this->mdl_mcb_data->getStatusOptions('sale_maker_assign');
	array_unshift($sale_maker_assign, $this->mdl_html->option( '', 'Assign Sale Maker'));
	$sale_maker_assign = $this->mdl_html->genericlist($sale_maker_assign,'sale_maker_assign[]',array('onchange'=>'getAssignOptions(this.value);','class'=>'validate[required]'),'value','text','');
	
	$salestatus = $this->mdl_mcb_data->getStatusOptions('salemaker_status');
	$deductiontype = $this->mdl_mcb_data->getStatusOptions('sale_deduction_type');

	$salestatus=$this->mdl_html->genericlist( $salestatus, 'salestatus' );
	$deductiontype=$this->mdl_html->genericlist( $deductiontype, 'deductiontype',array('class'=>'validate[required]'));


	//get list of models
	//get list of products
	//get list of categories

	$brand_options=$this->mdl_brands->getBrandOptions();
	
	//array_unshift($modelOptions, $this->mdl_html->option( '', 'Select Model'));

	array_unshift($brand_options, $this->mdl_html->option( 'all', 'Select All'));
	
	$brand_select=$this->mdl_html->genericlist( $brand_options, 'brand_options[]', array('multiple'=>'multiple','class'=>'validate[required]'));
	
	//cate opts
	$cat_options = $this->mdl_category->getCategoryOptions();
	array_unshift($cat_options, $this->mdl_html->option( 'all', 'Select All'));

	$cat_options = $this->mdl_html->genericlist( $cat_options, 'cat_options[]', array('multiple'=>'multiple','class'=>'validate[required]'));

	$part_options = $this->mdl_parts->getPartOptions();

	array_unshift($part_options, $this->mdl_html->option( 'all', 'Select All'));
	
	$part_select = $this->mdl_html->genericlist($part_options, 'part_options[]', array('multiple'=>'multiple','class'=>'validate[required]'));
	$product_options=$this->mdl_products->getProductOptions();
	
	//array_unshift($product_options, $this->mdl_html->option( '', 'Select Product'));
		array_unshift($product_options, $this->mdl_html->option( 'all', 'Select All'));
	$product_select=$this->mdl_html->genericlist( $product_options, 'product_options[]', array('multiple'=>'multiple','class'=>'validate[required]'));



	$modelOptions = $this->mdl_productmodel->getModelOptions();
		array_unshift($modelOptions, $this->mdl_html->option( 'all', 'Select All'));
	//array_unshift($modelOptions, $this->mdl_html->option( '', 'Select Model'));
	$model_select = $this->mdl_html->genericlist($modelOptions,'model_select[]',array('multiple'=>'multiple','class'=>'validate[required]'));


	//sales status

	//sale deduction type

	//sale categories
	$data = array(
					'salestatus'=>$salestatus,
					'deductiontype'=>$deductiontype,
					'model_select'=>$model_select,
					'brand_select'=>$brand_select,
					'product_select'=>$product_select,
					'sale_maker_assign'=>$sale_maker_assign,
					'cat_select'=>$cat_options,
					'part_select'=>$part_select
				);
	
	

	$this->load->view('salesmaker/index',$data);

	}

	public function save_salesmaker()
		{
		
		
		$this->redir->set_last_index();
		
		$this->load->model(array('salesmaker/mdl_salesmaker','salesmaker/mdl_salesmakerlist'));

		$salesmaker_action = (int) $this->input->post('salesmaker_action');
		$sale_id = (int) $this->input->post('sale_id');

		/*$option = $this->input->post('sale_maker_options');
		


		$all = explode(',', $option);
		
		if($all[0]=='all'){

			$all=array();
			
			$all[0]='0';
		}
		*/

		$data['sale_name'] = $this->input->post('sale_name');
		//$data['sale_maker_assign'] = $this->input->post('sale_maker_assign');
		$data['sale_status'] = $this->input->post('salestatus');
		$data['sale_deduction_type'] = $this->input->post('deductiontype');
		$data['sale_deduction_value'] = $this->input->post('deduction_value');
		$data['sale_date_start'] = $this->input->post('issue_date');
		$data['sale_date_added'] = $this->input->post('issue_date');
		$data['sale_date_end'] = $this->input->post('expire_date');
		

		$sale_check = $this->mdl_salesmaker->check_if_exist('sale_name',$data['sale_name']);

		if($salesmaker_action){
		if($salesmaker_action==1 && $sale_id==0){
			if($sale_check==0){
				if($this->mdl_salesmaker->save($data)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_saved'));
				}
				else{
					$error = array('type'=>'failure','message'=>$this->lang->line('this_record_has_been_notsaved'));
				}
			}
			else{
					$error = array('type'=>'failure','message'=>$this->lang->line('duplicate_entry'));		
			}
		
		}
		else if($salesmaker_action==2 && $sale_id!=0){
			if($this->mdl_salesmaker->save($data,$sale_id)){
				$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_updated'));
			}
			else{
				$error = array('type'=>'failure','message'=>$this->lang->line('this_record_has_been_notsaved'));
			}
			
		}			
		}
		else{
			$error = array('type'=>'warning','message'=>'Nothing Occured');
		}

		


		$this->load->view('dashboard/ajax_messages',$error);

	}

	public function saveModelPart(){
		$this->load->model(array('salesmaker/mdl_salesmakerlist'));
		$maker_id = $this->input->post('maker_id');
		$parts = $this->input->post('part');

		$ss=$this->db->delete($this->mdl_salesmakerlist->table_name,array('maker_id'=>$maker_id));
		if($ss){
		for ($i=0; $i < count($parts); $i++) { 
				$data=array(
						'maker_id' => $maker_id,
						'part_id' => $parts[$i] 
							);

				$this->db->insert($this->mdl_salesmakerlist->table_name,$data);
			}
			$error = array('type'=>'success','message'=>'Salesmaker Assign');
		}
		else{
			$error = array('type'=>'failure','message'=>'Error Occured');
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}


public function getsalesmakerlist()
	{

		$this->redir->set_last_index();

		$this->load->model(array('parts/mdl_parts','salesmaker/mdl_salesmaker','products/mdl_products','brands/mdl_brands','productmodel/mdl_productmodel','mcb_data/mdl_mcb_data', 'utilities/mdl_html','parts/mdl_model_parts'));

		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');

		$makerlist=$this->mdl_salesmaker->getSalesMakerlist($page);

		$config['total_rows'] = $makerlist['total'];

		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();

		$data=array(
					"makerlist"=>$makerlist['list'],
					"ajaxaction"=>$ajaxaction,
					"page"=>$page,
					"navigation"=>$navigation,
					'config'=>$config
		);
	
		$this->load->view('salesmakerlist', $data);
	}



	public function getAssignedList()
	{
		
		$this->load->model(array('brands/mdl_brands','salesmaker/mdl_salesmaker','salesmaker/mdl_salesmakerlist','parts/mdl_parts'));
		//sale maker id
		$maker_id = $this->input->post('maker_id');
		$sale_name = $this->input->post('name');

		$maker_id_arr = $this->mdl_salesmakerlist->getSaleMakerPartList($maker_id);

		foreach ($maker_id_arr as $arr) {
			//$part_id_arr[] =$arr->part_id;
			//$model_id_arr[]=$arr->model_id;
			$brand_id_arr[]=$arr->brand_id;
			//$product_id_arr[]=$arr->product_id;
		}
		if(empty($brand_id_arr)){
			$brand_id_arr[]='0';
		}
		

			//$data['part_id'] = $part_id_arr;
			//$data['model_id'] = $model_id_arr;
			$data['brand_id'] = $brand_id_arr;
			//$data['product_id'] = $product_id_arr;
			
	
			
			$brands = $this->mdl_brands->getBrandOptions();
		
			$data['maker_id'] = $this->input->post('maker_id');
		
			$data['brands'] =$brands;
			$data['sale_name'] = $sale_name;

			$this->load->view('saleassigned', $data);

	}

function getmodelsbyproducts(){
	
		$this->redir->set_last_index();
		$this->load->model(array('salesmaker/mdl_salesmakerlist','brands/mdl_brands','products/mdl_products','productmodel/mdl_productmodel','parts/mdl_model_parts'));
		$product_id = $this->input->post('product_id');
		
		$models = $this->mdl_productmodel->getDistinctModelsByProducts($product_id);
		
		$maker_id = $this->input->post('maker_id');
		
		$maker_id_arr = $this->mdl_salesmakerlist->getSaleMakerPartList($maker_id);

		foreach ($maker_id_arr as $arr) {
			$model_id_arr[]=$arr->model_id;
		}
		

		if(empty($model_id_arr)){
			$model_id_arr[]='0';
		}

		$data['model_id'] = $model_id_arr;
			
		

		$data = array(
					  'models'=>$models,
					  'model_id' => $model_id_arr,
		);
		
		$this->load->view('listmodelbyproduct',$data);
	}


	function getpartbymodel(){
		
		$this->load->model(array('parts/mdl_parts_models','salesmaker/mdl_salesmakerlist'));
		$model_id = $this->input->post('model_id');
		$parts = $this->mdl_parts_models->getPartsByModel($model_id);
		
		$maker_id = $this->input->post('maker_id');
		$maker_id_arr = $this->mdl_salesmakerlist->getSaleMakerPartList($maker_id);

		foreach ($maker_id_arr as $arr) {
			$part_id_arr[] =$arr->part_id;
		}
		

		if(empty($part_id_arr)){
			$part_id_arr[]='0';
		}

		$data['part_id'] = $part_id_arr;
		

		$data = array(
					  'parts'=>$parts,
					  'part_id' => $part_id_arr,
					  );				

		$this->load->view('listpartbymodel',$data);
	}


	function delete() {
		$this->load->model(array('salesmaker/mdl_salesmaker','salesmaker/mdl_salesmakerlist'));
		$id=$this->input->post('id');
		if($this->db->delete($this->mdl_salesmaker->table_name, array('maker_id' => $id))){
			
			$this->db->delete($this->mdl_salesmakerlist->table_name, array('maker_id'=>$id));

			$error = array('type'=>'warning','message'=>'deleted');
		}else{
			$error = array('type'=>'failure','message'=>'cannot delete');
		}
		$this->load->view('dashboard/ajax_messages',$error);
	}

	function getproductsbybrand()
	{
		$this->redir->set_last_index();
		$this->load->model(array('salesmaker/mdl_salesmakerlist','brands/mdl_brands','products/mdl_products','productmodel/mdl_productmodel','parts/mdl_model_parts'));
		$brands = $this->input->post('brand_id');
		$products = $this->mdl_products->getProductsByBrands($brands);
		
		$maker_id = $this->input->post('maker_id');
		
		$maker_id_arr = $this->mdl_salesmakerlist->getSaleMakerPartList($maker_id);

		foreach ($maker_id_arr as $arr) {
			$product_id_arr[]=$arr->product_id;
		}
		
		if(empty($product_id_arr)){
			$product_id_arr[]='0';
		}

		

		$ajaxaction = $this->input->post('ajaxaction');
		
		$data = array(
					  'ajaxaction'=>$ajaxaction,
					  'products'=>$products,
					  'product_id'=>$product_id_arr
		);
		
		$this->load->view('listproductsbybrand',$data);
	}


}