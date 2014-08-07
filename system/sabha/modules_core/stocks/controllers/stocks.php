<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
class Stocks extends Admin_Controller {
	function __construct() {
		parent::__construct();
		$this->load->language("stocks",  $this->mdl_mcb_data->setting('default_language'));
	}
	function index(){

		$this->redir->set_last_index();
		redirect('stocks/stocklist');
	}

	function stocklist($orderlevel=false){

		$this->redir->set_last_index();
		$this->load->model(array('servicecenters/mdl_servicecenters','mcb_data/mdl_mcb_data', 'utilities/mdl_html','company/mdl_company','parts/mdl_parts'));
		if($this->session->userdata('usergroup_id')==1){
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptions();
			array_unshift($scentersOptions, $this->mdl_html->option( '', 'All Store'));
		}else{
			$scentersOptions=$this->mdl_servicecenters->getServiceCentersOptionsBySc($this->session->userdata('sc_id'));
		}
		
		$company_options = $this->mdl_company->getCompanyOptions();
		
		array_unshift($company_options, $this->mdl_html->option( '', 'Select Company'));
		$company_options = $this->mdl_html->genericlist($company_options,'select_company',array('class'=>' select-one'),'value','text','');
		
		$servicecenters=$this->mdl_html->genericlist( $scentersOptions, "sc_id",array('class'=>'validate[required] text-input'),'value','text','');
		$data = array(
					   'servicecenters'=>$servicecenters,
					   'company_options'=>$company_options
		);


		if($orderlevel && $orderlevel=='orderlevel'){
			$array = array(
				'orderlevel' => true,
			);

			$this->session->set_userdata($array);

			$this->load->view('orderlevel/index',$data);
		}else{
			$this->session->unset_userdata('orderlevel');

			$this->load->view('stocklist/index',$data);
		}
		
	}
	// with no order level

	function showstocklist(){
		$this->load->model(array('servicecenters/mdl_servicecenters','stocks/mdl_parts_stocks','mcb_data/mdl_mcb_data','stocks/mdl_stocks', 'utilities/mdl_html'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$currentpage = $this->input->post('currentpage');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('results_per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		
		if($this->session->userdata('orderlevel')){

			$orderlevel=true;
		}
		else
			$orderlevel=false;
			
		$stocklist = $this->mdl_parts_stocks->getStocksList($page,$orderlevel);
		
		
        $stockdata = $stocklist['list'];
		$config['total_rows'] = $stocklist['total'];
		
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();

		
		$data = array(
						 'stocklist'=>$stockdata,"ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page,'config'=>$config
						  );
		
		$this->load->view('stocklist/stocklist',$data);
	}
	
	function showstocklistOL(){
		$this->load->model(array('servicecenters/mdl_servicecenters','stocks/mdl_parts_stocks','mcb_data/mdl_mcb_data','stocks/mdl_stocks', 'utilities/mdl_html'));
		$ajaxaction=$this->input->post('ajaxaction');
		$this->load->library('ajaxpagination');
		$currentpage = $this->input->post('currentpage');
		$config['base_url'] = base_url();
		$config['per_page'] = $this->mdl_mcb_data->get('results_per_page');
		$config['next_link'] = '&raquo;';
		$config['prev_link'] = '&laquo;';
		$page['limit'] = $config['per_page'];
		$page['start'] = $this->input->post('currentpage');
		$order_level=true;
		$stocklist = $this->mdl_parts_stocks->getStocksList($page,$order_level);
		
		
        $stockdata = $stocklist['list'];
		$config['total_rows'] = $stocklist['total'];
		
		$this->ajaxpagination->cur_page=$this->input->post('currentpage');
		
		$this->ajaxpagination->initialize($config);
		$navigation = $this->ajaxpagination->create_links();

		
		$data = array(
					'stocklist'=>$stockdata,"ajaxaction"=>$ajaxaction,'navigation'=>$navigation,'page'=>$page,'config'=>$config
					);
		

		$this->load->view('orderlevel/index',$data);
	}
	

	function stockdetails(){
		$this->redir->set_last_index();
		$this->load->model(array('servicecenters/mdl_servicecenters'));
		$sc_id = $this->uri->segment(3);
		$sc_name = '';
		if($sc_id){
			$arr = $this->mdl_servicecenters->get_ScByid($sc_id);
			$sc_name = $arr[0]->sc_name;
		}
		$data = array(
				 	  'sc_name'=>$sc_name
					  );
		$this->load->view('stockdetails/stockdetails',$data);
	}


	function showstockdetails(){
		$this->load->model(array('servicecenters/mdl_servicecenters','purchase/mdl_purchase_details','orders/mdl_order_parts','stocks/mdl_stocks','mcb_data/mdl_mcb_data', 'utilities/mdl_html','company/mdl_company','parts/mdl_parts'));
		
		$stocklist_details = $this->mdl_stocks->getStocklist();
	
		$data = array(
					  'stocklist_details'=>$stocklist_details
					  );
		$this->load->view('stockdetails/details',$data);
	}
	
	function showstocklistbyscid(){
		$this->load->model(array('servicecenters/mdl_servicecenters','stocks/mdl_parts_stocks','mcb_data/mdl_mcb_data','stocks/mdl_stocks', 'utilities/mdl_html'));
		$sc_id = $this->session->userdata('sc_id');
		$stocklist= $this->mdl_parts_stocks->getStocksListbyscid($sc_id);
		//$stocklist_transit= $this->mdl_stocks->stockTransit($stocklist);
		
		$data = array(
					 'stocklist'=>$stocklist
					 );
		$this->load->view('partallocation/stocklist',$data);
	}
	
	function excel(){

			$this->session->set_userdata('sc_id_stock',$this->input->post('sc_id'));
			$this->session->set_userdata('searchtxt_stock',$this->input->post('searchtxt'));
			$this->session->set_userdata('company_id_stock',$this->input->post('company'));

	}
	function create_excel()
	{

		ini_set("memory_limit","256M");
		$this->load->model(array('stocks/mdl_parts_stocks','servicecenters/mdl_servicecenters','company/mdl_company','partallocation/mdl_partallocation'));
		$this->load->plugin('to_excel');
		$this->load->helper('calls');


		$list = $this->mdl_parts_stocks->getStocksdownload();
		$data = '';

		if($list->num_rows()==0){
			redirect('stocks');
		}



		foreach($list->result() as $row){
			$row->allocated_quantity =  $this->mdl_partallocation->allocatedquantity($row->sc_id,$row->part_number,$row->company_id);
			$row->available_quantity = $row->stock_quantity + $row->allocated_quantity;
			unset($row->sc_id);
			unset($row->company_id);
			$data[]=$row;
			
		}

		foreach ($data as $dd) {

			if($dd->part_id){
				unset($dd->part_id);
				unset($dd->allocated_quantity);
			}

			if($dd->parts_in_transit == '0'){
				$dd->parts_in_transit = "";
			}

		}


				
		if($this->session->userdata('orderlevel')){
			$this->load->library('excel');
			//activate worksheet number 1
			$this->excel->setActiveSheetIndex(0);
			//name the worksheet
			$this->excel->getActiveSheet()->setTitle('purchase order');
			//set cell A1 content with some text
			$this->excel->getActiveSheet()->setCellValue('A1', 'S.No');
			$this->excel->getActiveSheet()->setCellValue('B1', 'Item Number');
			$this->excel->getActiveSheet()->setCellValue('C1', 'Item Description');
			$this->excel->getActiveSheet()->setCellValue('D1', 'Quantity');
			$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
			$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
			$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
			$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

			 			
 		/*foreach ($data as $dd) {
 				$dd->s_no = $i++;
                $dd->item_number = $dd->part_number; 
                $dd->item_desc = $dd->part_desc;
                $dd->company =$dd->company_title;
                $dd->quantity = ($dd->order_level_max - $dd->available_quantity);
                unset($dd->sc_name);
                unset($dd->order_level_max);
                unset($dd->parts_in_transit);
                unset($dd->stock_quantity);
                unset($dd->part_number);
                unset($dd->part_desc);
                unset($dd->company_title);
                unset($dd->allocated_quantity);
                unset($dd->order_level);
                unset($dd->available_quantity);
        }*/

		        $i=2;
		    	foreach ($data as $d) {
		    		$this->excel->getActiveSheet()->setCellValue('A' . $i, $i-1)
			                              ->setCellValue('B' . $i, $d->part_number)
			                              ->setCellValue('C' . $i, $d->part_desc)
			                              ->setCellValue('D' . $i, (int) ($d->order_level_max - $d->available_quantity));
			    $i++;
		    	}
    


$filename='lowstock_purchase_order_'.date('Y_m_d_H_i_s').'.xls'; //save our workbook as this file name
header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache
             
//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
//if you want to save it as .XLSX Excel 2007 format
$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
//force user to download the Excel file without writing it to server's HD
$objWriter->save('php://output');
die();

		

            

            //Item Number	Item description	Company	Quantity

			/*$fields = array('Store','Item Number','Company','Item description','Available Quantity','Parts in transit','Allocated_quantity','Order Level','Total Quantity');*/

			$fields = array('S.No.','Item Number','Item description','Company','Quantity');


		}
		else{

			$fields = array('Store','Item Number','Company','Item description','Available Quantity','Parts in transit','Total Quantity');
		}

		$this->load->helper('download');
		$data = convert_to_table($data,$fields);
		
		$name = 'parts_'.date("Y_m_d_H_i_s").'.xls';
		
		/*
		 **keep track of downloads
		 */
		
		$this->load->library('user_agent');
		$this->load->model('downloads/mdl_downloads');
		$download_data = array(
						  'download_session'=>$this->session->userdata('session_id'),
						  'download_type'=>'calls',
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


	
	
}
?>