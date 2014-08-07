<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');

class Parts extends Admin_Controller
{
    function __construct() {
        parent::__construct();
        $this->load->language("parts", $this->mdl_mcb_data->setting('default_language'));
        $this->_post_handler();
    }
    
    function index() {
        
        $this->redir->set_last_index();
        $this->load->model(array('parts/mdl_parts', 'brands/mdl_brands', 'products/mdl_products', 'productmodel/mdl_productmodel', 'mcb_data/mdl_mcb_data', 'utilities/mdl_html', 'parts/mdl_model_parts'));
        
        $ustatuslist = $this->mdl_mcb_data->getStatusOptions('ustatus');
        $brand_status = $this->mdl_html->genericlist($ustatuslist, 'brand_status');
        $product_status = $this->mdl_html->genericlist($ustatuslist, 'product_status');
        $brandOptions = $this->mdl_brands->getBrandOptions();
        
        array_unshift($brandOptions, $this->mdl_html->option('', 'Select Brand'));
        $brand_select = $this->mdl_html->genericlist($brandOptions, 'brand_select', array('onchange' => 'getProductBybrand($(this).val());', 'class' => 'validate[required] select-one'), 'value', 'text', '');
        
        /*partsize and partcolor*/
        $color_select = $this->mdl_mcb_data->getStatusOptions('part_color');
        $size_select = $this->mdl_mcb_data->getStatusOptions('part_size');
        
        array_unshift($color_select, $this->mdl_html->option('', 'Select Color'));
        $colorselect = $this->mdl_html->genericlist($color_select, 'part_color', array('onchange' => 'appendInPart(this.value)'), 'value', 'text', '');
        
        array_unshift($size_select, $this->mdl_html->option('', 'Select Size'));
        $sizeselect = $this->mdl_html->genericlist($size_select, 'part_size', array('onchange' => 'appendInPart(this.value)'), 'value', 'text', '');
        
        $productOptions = array();
        
        array_unshift($productOptions, $this->mdl_html->option('', 'Select Product'));
        $product_select = $this->mdl_html->genericlist($productOptions, 'product_select', array('class' => 'validate[required] text-input', 'onchange' => 'getModelsByProduct(this.value)'), 'value', 'text', '');
        
        //select box for models
        $modelOptions = $this->mdl_productmodel->getModelOptionsByProduct('');
        
        array_unshift($modelOptions, $this->mdl_html->option('', 'Select Model'));
        $model_select = $this->mdl_html->genericlist($modelOptions, 'model_select', array('class' => 'validate[required] select-one'), 'value', 'text', '');
        
        $part_unitsOptions = $this->mdl_mcb_data->getStatusOptions('part_units');
        $part_units = $this->mdl_html->genericlist($part_unitsOptions, 'unit', array('value', 'text', ''));
        
        $data = array('brand_select' => $brand_select, 'product_select' => $product_select, 'model_select' => $model_select, 'colorselect' => $colorselect, 'sizeselect' => $sizeselect, 'part_units' => $part_units);
        
        $this->load->view('index', $data);
    }
    
    //barcode functions
    
    private function barcode($part_number, $part_id) {
        
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        
        $foldername = "uploads/barcodes/" . $part_id . ".png";
        
        // Only the text to draw is required
        $barcodeOptions = array('text' => strtoupper($part_number), 'barHeight' => 20, 'factor' => 1.2);
        
        // No required options
        $rendererOptions = array();
        
        // Draw the barcode in a new image,
        $imageResource = Zend_Barcode::factory('code39', 'image', $barcodeOptions, $rendererOptions)->draw();
        
        imagepng($imageResource, $foldername, 9);
        imagedestroy($imageResource);
    }
    
    function getpartlist() {
        $this->redir->set_last_index();
        $this->load->model(array('parts/mdl_parts', 'products/mdl_products', 'brands/mdl_brands', 'productmodel/mdl_productmodel', 'mcb_data/mdl_mcb_data', 'utilities/mdl_html', 'parts/mdl_model_parts'));
        $ajaxaction = $this->input->post('ajaxaction');
        $this->load->library('ajaxpagination');
        $config['base_url'] = base_url();
        $config['per_page'] = $this->mdl_mcb_data->get('per_page');
        $config['next_link'] = '&raquo;';
        $config['prev_link'] = '&laquo;';
        $page['limit'] = $config['per_page'];
        $page['start'] = $this->input->post('currentpage');
        
        $brands = $this->mdl_parts->getParts($page);
        
        $config['total_rows'] = $brands['total'];
        $this->ajaxpagination->cur_page = $this->input->post('currentpage');
        $this->ajaxpagination->initialize($config);
        $navigation = $this->ajaxpagination->create_links();
        
        $data = array("parts" => $brands['list'], "ajaxaction" => $ajaxaction, "page" => $page, "navigation" => $navigation, 'config' => $config);
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
    
    function getmodelsbyproduct() {
        $this->redir->set_last_index();
        $this->load->model(array('productmodel/mdl_productmodel', 'mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
        $product_id = (int)$this->input->post('product_id');
        $active = $this->input->post('active');
        $modelOptions = $this->mdl_productmodel->getModelOptionsByProduct($product_id);
        array_unshift($modelOptions, $this->mdl_html->option('', 'Select Model'));
        $model_select = $this->mdl_html->genericlist($modelOptions, 'model_select', array('class' => 'validate[required] select-one'), 'value', 'text', $active);
        echo $model_select;
        die();
    }
    function getpartdetails() {
        $this->redir->set_last_index();
        $this->load->model(array('parts/mdl_parts', 'productmodel/mdl_productmodel', 'products/mdl_products', 'brands/mdl_brands'));
        $part_id = (int)$this->input->post('id');
        $part = $this->mdl_parts->getPartDetails($part_id);
        
        echo json_encode($part);
    }
    
    function savepart() {
        
        /*	dump($this->input->post());
         die();*/
        
        $this->redir->set_last_index();
        $error = array();
        $this->load->model(array('parts/mdl_parts', 'products/mdl_products'));
        
        $part_id = $this->input->post('part_id');
        $part_number = str_replace(" ", "", $this->input->post('part_number'));
        $part_init_no = $this->input->post('part_init_no');
        $part_desc = $this->input->post('description');
        $part_customer_price = $this->input->post('part_customer_price');
        $part_sc_price = $this->input->post('part_sc_price');
        $part_landing_price = $this->input->post('part_landing_price');
        $part_color = $this->input->post('part_color');
        $part_size = $this->input->post('part_size');
        $unit = $this->input->post('unit');
        
        $order_level = $this->input->post('order_level');
        $order_level_max = $this->input->post('order_level_max');
        

        /*if($order_level){
        $order_level = $order_level;
        }else{
        $order_level=5;
        }*/
        
        if ($part_customer_price == '') {
            $part_customer_price = 0.00;
        }
        
        if ($part_sc_price == '') {
            $part_sc_price = 0.00;
        }
        
        if ($part_landing_price == '') {
            $part_landing_price = 0.00;
        }
        
        $data['part_number'] = $part_number;
        $data['part_initial_no'] = $part_init_no;
        $data['part_desc'] = $part_desc;
        $data['part_customer_price'] = $part_customer_price;
        $data['part_sc_price'] = $part_customer_price;
        $data['part_landing_price'] = $part_customer_price;
        $data['part_color'] = $part_color;
        $data['part_size'] = $part_size;
        $data['order_level'] = $order_level;
        $data['order_level_max'] = $order_level_max;
        $data['unit'] = $unit;
        
        $partcheck = $this->mdl_parts->checkpart($part_number);
        
        if ((int)$part_id == 0) {
            $data["part_created_ts"] = date("Y-m-d H:i:s");
            $data["part_created_by"] = $this->session->userdata('user_id');

            
            if ($partcheck == 0) {
                if ($this->mdl_parts->save($data)) {
                    $id = $this->db->insert_id();
                    $this->barcode($data['part_number'], $id);
                    $error = array('type' => 'success', 'message' => $this->lang->line('this_record_has_been_saved'));
                }
            } else {
                $error = array('type' => 'failure', 'message' => $this->lang->line('this_record_can_not_be_saved'));
            }
        } else {
            $data["part_last_mod_ts"] = date("Y-m-d");
            $data["part_last_mod_by"] = $this->session->userdata('user_id');
            
            //echecking before edit
            $checkEdit = $this->db->select('part_number')->from($this->mdl_parts->table_name)->where('part_number', $part_number)->where('part_id <>', $part_id)->get()->num_rows();
            
            if ($checkEdit == 0) {
                if ($this->mdl_parts->save($data, $part_id)) {
                    $this->barcode($data['part_number'], $part_id);
                    $error = array('type' => 'success', 'message' => $this->lang->line('this_record_has_been_saved'));
                } else {
                    $error = array('type' => 'failure', 'message' => $this->lang->line('this_record_can_not_be_saved'));
                }
            } else {
                $error = array('type' => 'failure', 'message' => $this->lang->line('this_record_can_not_be_saved'));
            }
        }
        
        $this->load->view('dashboard/ajax_messages', $error);
    }
    
    function deletepart() {
        
        $this->load->model(array('parts/mdl_parts', 'products/mdl_products'));
        $brand_id = $this->input->post('part_id');
        
        if ($this->mdl_parts->delete(array('part_id' => $brand_id))) {
            
            if (file_exists('uploads/barcodes/' . $brand_id . '.png')) {
                unlink('uploads/barcodes/' . $brand_id . '.png');
            }
            
            $error = array('type' => 'warning', 'message' => $this->lang->line('this_record_has_been_deleted'));
        } else {
            $error = array('type' => 'failure', 'message' => $this->lang->line('this_record_can_not_be_deleted'));
        }
        $this->load->view('dashboard/ajax_messages', $error);
    }
    function getbrandproductlist() {
        
        $this->load->model(array('products/mdl_products', 'productmodel/mdl_productmodel', 'brands/mdl_brands', 'parts/mdl_model_parts', 'utilities/mdl_html'));
        $brands = $this->mdl_brands->getBrandOptions();
        $part_number = $this->input->post('part_number');
        $part_id = $this->input->post('part_id');
        
        //edited for model/part id process in table sst_prod_part_model
        $parts_assign_brand = $this->mdl_model_parts->getModelPartsWithId($part_id);
        
        //old one
        //$parts_assign_brand = $this->mdl_model_parts->getModelParts($part_number);
        
        $parts_assign_brands = array();
        $parts_assign_models = array();
        $parts_assign_products = array();
        
        foreach ($parts_assign_brand as $brand) {
            $parts_assign_brands[] = $brand->brand_id;
            $parts_assign_models[] = $brand->model_id;
            $parts_assign_products[] = $brand->product_id;
        }
        
        $ajaxaction = $this->input->post('ajaxaction');
        $data = array('brands' => $brands, 'parts_assign_brands' => $parts_assign_brands, 'ajaxaction' => $ajaxaction, 'part_number' => $part_number, 'part_id' => $part_id, 'parts_assign_models' => $parts_assign_models, 'parts_assign_products' => $parts_assign_products);
        
        $this->load->view('modelassign', $data);
    }
    function getproductsbybrand() {
        $this->redir->set_last_index();
        $this->load->model(array('brands/mdl_brands', 'products/mdl_products', 'productmodel/mdl_productmodel', 'parts/mdl_model_parts'));
        $brands = $this->input->post('brand_id');
        $products = $this->mdl_products->getProductsByBrands($brands);
        $part_number = $this->input->post('part_number');
        $part_id = $this->input->post('part_id');
        
        //$parts_assign_product = $this->mdl_model_parts->getModelParts($part_number);
        //with id
        $parts_assign_product = $this->mdl_model_parts->getModelPartsWithId($part_id);
        
        $parts_assign_products = array();
        foreach ($parts_assign_product as $product) {
            $parts_assign_products[] = $product->product_id;
        }
        $ajaxaction = $this->input->post('ajaxaction');
        $data = array('ajaxaction' => $ajaxaction, 'parts_assign_products' => $parts_assign_products, 'products' => $products);
        
        $this->load->view('listproductsbybrand', $data);
    }
    
    function getmodelsbyproducts() {
        $this->redir->set_last_index();
        
        $this->load->model(array('brands/mdl_brands', 'products/mdl_products', 'productmodel/mdl_productmodel', 'parts/mdl_model_parts'));
        $product_id = $this->input->post('product_id');
        $part_number = $this->input->post('part_number');
        $part_id = $this->input->post('part_id');
        
        $models = $this->mdl_productmodel->getDistinctModelsByProducts($product_id);
        
        //old
        //$parts_assign_model = $this->mdl_model_parts->getModelParts($part_number);
        //new
        
        $parts_assign_model = $this->mdl_model_parts->getModelPartsWithId($part_id);
        
        //$parts_assign_models = array();
        $part_assign_models_id = array();
        foreach ($parts_assign_model as $model) {
            
            //old
            //$parts_assign_models[] = $model->model_number;
            //new
            $part_assign_models_id[] = $model->model_id;
        }
        
        $data = array(
        
        //'parts_assign_models'=>$parts_assign_models,
        'part_assign_models_id' => $part_assign_models_id, 'models' => $models);
        
        $this->load->view('listmodelsbyproduct', $data);
    }
    
    function savemodelspart() {
        $this->redir->set_last_index();
        $error = array();
        $this->load->model(array('parts/mdl_parts', 'parts/mdl_model_parts'));
        
        $model_ids = $this->input->post('model');
        
        //$model_ids=$this->mdl_model_parts->getModelId($model_numbers);
        
        //checking and coverting NULL value to 0 in array $model_ids
        for ($i = 0; $i < count($model_ids); $i++) {
            if (is_null($model_ids[$i])) {
                $model_ids[$i] = '0';
            }
        }
        
        $part_number = $this->input->post('part_number');
        
        $part_id = $this->input->post('part_id');
        
        //old one
        //$arr_model = $this->input->post('model');
        $arr_model = $model_ids;
        
        if (count($arr_model) > 0 && is_array($arr_model)) {
            $arr = array();
            $str = '';
            
            foreach ($arr_model as $row) {
                $ss[] = $row;
                
                $arr[] = '("' . $part_id . '","' . $row . '")';
            }
            $str = implode(",", $arr);
            
            $this->db->where('part_id', $part_id);
            if ($this->db->delete($this->mdl_model_parts->table_name1)) {
                $sql = 'INSERT INTO ' . $this->mdl_model_parts->table_name1 . '(part_id,model_id) VALUES ' . $str . ';';
                $result = $this->db->query($sql);
            }
            
            if ($result > 0) {
                $error = array('type' => 'success', 'message' => sprintf($this->lang->line('models_has_been_added'), $part_number));
            } else {
                $error = array('type' => 'failure', 'message' => sprintf($this->lang->line('models_could_not_be_added'), $part_number));
            }
        } else {
            $this->mdl_model_parts->delete(array('part_id' => $part_id));
            $error = array('type' => 'failure', 'message' => sprintf($this->lang->line('models_has_been_removed'), $part_number));
        }
        $this->load->view('dashboard/ajax_messages', $error);
    }
    
    function generatetemplateexl() {
        $this->load->library('parser');
        $this->load->helper('file');
        $data = array();
        
        //$data = $this->parser->parse('exl_template',$data,TRUE);
        $data = file_get_contents("uploads/temp/excel_template.xls");
        $this->load->helper('download');
        $name = 'excel_template_' . date("Y_m_d_H_i_s") . '.xls';
        
        /*
         **keep track of downloads
        */
        $this->load->library('user_agent');
        $this->load->model('downloads/mdl_downloads');
        $download_data = array('download_session' => $this->session->userdata('session_id'), 'download_type' => 'excel_template', 'user_agent' => $this->agent->agent_string(), 'user_ip' => $this->input->ip_address(), 'download_by' => $this->session->userdata('user_id'), 'download_ts' => date('Y-m-d H:i:s'));
        $this->mdl_downloads->save($download_data);
        
        /*
         **ends here
        */
        force_download($name, $data);
    }
    function uploadform() {
        $this->redir->set_last_index();
        $this->load->view('uploadform');
    }

    public function barcodes_print()
    {
        $this->redir->set_last_index();
        $part_id = $this->input->post('part_id');
        $part_price = $this->input->post('part_price');
        $part_number = $this->input->post('part_number');

        $this->data = array(
            'part_id' => $part_id ,
            'part_price' => $part_price,
            'part_number' => $part_number
            );
    
        $this->load->view('barcodes',$this->data);
    }
    
    function upload() {
        ini_set('php_value max_input_vars', '3000');
        $this->load->model(array('utilities/mdl_html'));
        $config['upload_path'] = './uploads/temp/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['file_name'] = $this->session->userdata('session_id') . '_' . date('Y_m_d_H_i_s');
        
        //$config['file_name'] = 'excel_file';
        $this->load->library('upload', $config);
        $file_name = 'excel_file';
        
        if (!$this->upload->do_upload($file_name)) {
            $error = array('error' => $this->upload->display_errors());
            echo $error['error'];
            die();
        } else {
            $arr = $this->upload->data();
            
            $data = array('upload_data' => $arr);
            
            $this->load->library('spreadsheet_excel_reader');
            
            //$path = str_replace("/","\\",$arr['full_path']);
            $path = $arr['full_path'];
            
            $this->spreadsheet_excel_reader->read($path);
            
            $rows = $this->spreadsheet_excel_reader->sheets[0]['cells'];
            
            $row_count = count($this->spreadsheet_excel_reader->sheets[0]['cells']);
            
            $this->load->model(array('parts/mdl_parts'));
            
            $parts = $this->mdl_parts->getPartOptions();
            
            $part_arr = array();
            
            foreach ($parts as $part) {
                $part_arr[] = trim($part->text, " ");
            }
            
            $data = array('rows' => $rows, 'row_count' => $row_count, 'part_arr' => $part_arr);
            @unlink($path);
            
            $this->load->view('excel_list', $data);
        }
    }
    function get($params = NULL) {
        return $this->mdl_users->get($params);
    }
    
    function savep() {
        
        //dump($this->input->post()); die();
        
        $this->load->model(array('parts/mdl_parts'));
        
        $part_initial_no = $this->input->post('part_initial_no');
        $part_number = $this->input->post('part_number');
        $part_desc = $this->input->post('part_desc');
        $part_lp = $this->input->post('part_landing_price');
        $part_cp = $this->input->post('part_customer_price');
        $part_sp = $this->input->post('part_sc_price');
        $remarks = $this->input->post('remarks');
        $task_select = $this->input->post('task_select');
        
        $i = 0;
        $cnt = 0;
        $error = array();
        
        foreach ($task_select as $task) {
            if ($task < 3 && $task >= 0) {
                if ($task == 1) {
                  

                    $data = array(
                        'part_number' => $this->db->escape_like_str($part_number[$i]),
                        'part_initial_no' => $this->db->escape_like_str($part_initial_no[$i]), 
                        'part_desc' => $this->db->escape_like_str($part_desc[$i] . "," . $remarks[$i]),
                        'part_landing_price' => $part_lp[$i],
                        'part_customer_price' => $part_cp[$i], 
                        'part_sc_price' => $part_sp[$i],
                        'part_created_by' => $this->session->userdata('user_id'),
                        'part_created_ts' => date("Y-m-d H:i:s")
                        );
                    
                    try {
                        $this->db->insert($this->mdl_parts->table_name, $data);
                        $id = $this->db->insert_id();
                        $this->barcode($data['part_number'], $id);
                    }
                    catch(Exception $e) {
                        dump($e->message());
                    }
                    
                    /*if($this->db->insert($this->mdl_parts->table_name,$data)){
                    $id=$this->db->insert_id();
                    $this->barcode($data['part_number'],$id);
                    
                    }
                    else{
                    
                    }*/
                } else {
                    $data = array('part_desc' => $part_dsc[$i], 'part_landing_price' => $part_lp[$i], 'part_size' => $part_size[$i], 'order_level' => $order_level[$i], 'part_color' => $part_color[$i], 'part_customer_price' => $part_cp[$i], 'part_sc_price' => $part_sp[$i]);
                    
                    $this->db->where('part_number', $part_no[$i]);
                    $this->db->update($this->mdl_parts->table_name, $data);
                    $id = $this->db->insert_id();
                    $this->barcode($data['part_number'], $id);
                }
            }
            $i++;
        }
        
        //$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_uploaded'));
        redirect('parts');
        
        //$this->load->view('dashboard/ajax_messages',$error);
        
    }
    
    function _post_handler() {
        if ($this->input->post('save_xldata')) {
            $this->load->model(array('parts/mdl_parts'));
            $part_no = $this->input->post('part_no');
            $part_dsc = $this->input->post('part_dsc');
            $part_lp = $this->input->post('part_lp');
            $part_cp = $this->input->post('part_cp');
            $part_sp = $this->input->post('part_sp');
            $task_select = $this->input->post('task_select');
            $i = 0;
            foreach ($task_select as $task) {
                if ($task < 3 && $task >= 0) {
                    if ($task == 1) {
                        $data = array('part_number' => $part_no[$i], 'part_desc' => $part_dsc[$i], 'part_landing_price' => $part_lp[$i], 'part_customer_price' => $part_cp[$i], 'part_sc_price' => $part_sp[$i]);
                        $this->db->insert($this->mdl_parts->table_name, $data);
                    } else {
                        $data = array('part_desc' => $part_dsc[$i], 'part_landing_price' => $part_lp[$i], 'part_customer_price' => $part_cp[$i], 'part_sc_price' => $part_sp[$i]);
                        $this->db->where('part_number', $part_no[$i]);
                        $this->db->update($this->mdl_parts->table_name, $data);
                    }
                }
                $i++;
            }
            redirect('parts');
        }
    }
    
    function create_excel() {
        ini_set("memory_limit", "256M");
        $this->load->model(array('parts/mdl_parts'));
        $this->load->plugin('to_excel');
        $this->load->helper('calls');
        $list = $this->mdl_parts->getPartsDownload();
        $result = $list['result'];
        $data = '';
        if (count($result->result()) == 0) {
            redirect('parts');
        }
        foreach ($result->result() as $row) {
            unset($row->part_size);
            unset($row->part_color);
            $data[] = $row;
        }

     
        $fields = array('Item Number', 'Item description', 'Order Level', 'Landing Price', 'Customer Price', 'Store Price');
        $this->load->helper('download');
        $data = convert_to_table($data, $fields);
        $name = 'parts_' . date("Y_m_d_H_i_s") . '.xls';
        
        /*
         **keep track of downloads
        */
        
        $this->load->library('user_agent');
        $this->load->model('downloads/mdl_downloads');
        $download_data = array('download_session' => $this->session->userdata('session_id'), 'download_type' => 'calls', 'user_agent' => $this->agent->agent_string(), 'user_ip' => $this->input->ip_address(), 'download_by' => $this->session->userdata('user_id'), 'download_ts' => date('Y-m-d H:i:s'));
        $this->mdl_downloads->save($download_data);
        
        /*
         **ends here
        */
        force_download($name, $data);
    }
    
    function modelpartreport() {
        $this->redir->set_last_index();
        $this->load->model(array('products/mdl_products', 'productmodel/mdl_productmodel', 'brands/mdl_brands', 'utilities/mdl_html', 'parts/mdl_parts'));
        $brandOptions = $this->mdl_brands->getBrandOptions();
        
        $b = array();
        foreach ($brandOptions as $row) {
            $b[] = $row->value;
        }
        $brand_select = $this->mdl_html->genericlist($brandOptions, 'brand_id', array('onchange' => 'getProductBybrand($(this).val());', 'class' => 'validate[required] select-one', 'multiple' => 'multiple', 'style' => 'height:120px;'), 'value', 'text', $b);
        
        //select box for products
        
        $productOptions = $this->mdl_products->getProductOptionsByBrands(implode(",", $b));
        $p = array();
        foreach ($productOptions as $row1) {
            $p[] = $row1->value;
        }
        
        $product_select = $this->mdl_html->genericlist($productOptions, 'product_id', array('onchange' => 'getModelsByProducts($(this).val());', 'class' => 'validate[required] select-one', 'multiple' => 'multiple', 'style' => 'height:120px;'), 'value', 'text', $p);
        
        //Select For models
        
        $modelSelect = $this->mdl_productmodel->getModelsByProductsid(implode(",", $p));
        
        $m = array();
        foreach ($modelSelect as $row1) {
            $m[] = $row1->value;
        }
        
        $model_select = $this->mdl_html->genericlist($modelSelect, 'model_number[]', array('class' => 'validate[required] select-one', 'multiple' => 'multiple', 'style' => 'height:120px;'), 'value', 'text', $m);
        
        $data = array(
        'brand_select' => $brand_select, 'product_select' => $product_select, 'model_select' => $model_select);
        
        $this->load->view('tab_model', $data);
    }
    
    function getproductsbybrands() {
        $this->load->model(array('brands/mdl_brands', 'products/mdl_products', 'mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
        $active = (int)$this->input->post('active');
        $productOptions = $this->mdl_products->getProductOptionsByBrands($this->input->post('brand_ids'));
        
        //array_unshift($productOptions,$this->mdl_html->option( '0', 'Select Product'));
        $product_select = $this->mdl_html->genericlist($productOptions, 'product_id', array('onchange' => 'getModelsByProducts($(this).val());', 'class' => 'validate[required] select-one', 'multiple' => 'multiple', 'style' => 'height:120px;'), 'value', 'text', 0);
        echo $product_select;
        die();
    }
    
    function getmodelbyproduct() {
        $this->load->model(array('brands/mdl_brands', 'products/mdl_products', 'productmodel/mdl_productmodel', 'mcb_data/mdl_mcb_data', 'utilities/mdl_html'));
        $active = (int)$this->input->post('active');
        $productOptions = $this->mdl_productmodel->getModelsByProductsid($this->input->post('product_ids'));
        
        //array_unshift($productOptions,$this->mdl_html->option( '0', 'Select Product'));
        $product_select = $this->mdl_html->genericlist($productOptions, 'model_number[]', array('class' => 'validate[required] select-one', 'multiple' => 'multiple', 'style' => 'height:120px;'), 'value', 'text', 0);
        
        echo $product_select;
        die();
    }
    
    function getreportslist() {
        $this->redir->set_last_index();
        $this->load->model(array('parts/mdl_parts_models', 'mcb_data/mdl_mcb_data', 'productmodel/mdl_productmodel', 'brands/mdl_brands', 'products/mdl_products', 'parts/mdl_parts'));
        $ajaxaction = $this->input->post('ajaxaction');
        $this->load->library('ajaxpagination');
        $config['base_url'] = base_url();
        $config['per_page'] = $this->mdl_mcb_data->get('per_page');
        $config['next_link'] = '&raquo;';
        $config['prev_link'] = '&laquo;';
        $page['limit'] = $config['per_page'];
        $page['start'] = $this->input->post('currentpage');
        $parts = $this->mdl_parts_models->getParts($page);
        $config['total_rows'] = $parts['total'];
        $this->ajaxpagination->cur_page = $this->input->post('currentpage');
        $this->ajaxpagination->initialize($config);
        $navigation = $this->ajaxpagination->create_links();
        
        $data = array('parts' => $parts, "ajaxaction" => $ajaxaction, "page" => $page, "navigation" => $navigation, 'config' => $config);
        $this->load->view('parts/modelpartlist', $data);
    }
    
    function create_excel_modelassociation() {
        
        ini_set("memory_limit", "256M");
        $this->load->model(array('parts/mdl_parts_models', 'productmodel/mdl_productmodel', 'brands/mdl_brands', 'products/mdl_products', 'parts/mdl_parts'));
        $this->load->plugin('to_excel');
        $this->load->helper('calls');
        
        $list = $this->mdl_parts_models->getParts_excel();
        
        $result = $list['result'];
        
        if (count($result->result()) == 0) {
            redirect('parts/modelpartreport');
        }
        $data = array();
        
        $i = 1;
        $parts_models = $result->result();
        foreach ($parts_models as $row) {
            $details = new stdClass();
            $details->sn = $i;
            $details->brand_name = $row->brand_name;
            $details->product_name = $row->product_name;
            $details->model_number = $row->model_number;
            $details->part_number = $row->part_number;
            $details->part_desc = $row->part_desc;
            $details->sc_price = $row->part_sc_price;
            $details->cust_price = $row->part_customer_price;
            $data[] = $details;
            $i++;
        }
        
        $fields = array('SN', 'Brand', 'Product', 'Model', 'Item Number', 'Item description', 'Store Price', 'Customer Price');
        $this->load->helper('download');
        $data = convert_to_table($data, $fields);
        $name = 'parts_model_association_' . date("Y_m_d_H_i_s") . '.xls';
        
        /*
         **keep track of downloads
        */
        $this->load->library('user_agent');
        $this->load->model('downloads/mdl_downloads');
        $download_data = array('download_session' => $this->session->userdata('session_id'), 'download_type' => 'calls', 'user_agent' => $this->agent->agent_string(), 'user_ip' => $this->input->ip_address(), 'download_by' => $this->session->userdata('user_id'), 'download_ts' => date('Y-m-d H:i:s'));
        $this->mdl_downloads->save($download_data);
        
        /*
         **ends here
        */
        force_download($name, $data);
    }
    
    function generatetemplateexl_modelassociation() {
        $this->load->library('parser');
        $this->load->helper('file');
        $data = array();
        
        //$data = $this->parser->parse('exl_template',$data,TRUE);
        $data = file_get_contents("uploads/temp/excel_template_modelassociation.xls");
        $this->load->helper('download');
        $name = 'excel_template_model_association' . date("Y_m_d_H_i_s") . '.xls';
        
        /*
         **keep track of downloads
        */
        $this->load->library('user_agent');
        $this->load->model('downloads/mdl_downloads');
        $download_data = array('download_session' => $this->session->userdata('session_id'), 'download_type' => 'excel_template', 'user_agent' => $this->agent->agent_string(), 'user_ip' => $this->input->ip_address(), 'download_by' => $this->session->userdata('user_id'), 'download_ts' => date('Y-m-d H:i:s'));
        $this->mdl_downloads->save($download_data);
        
        /*
         **ends here
        */
        force_download($name, $data);
    }
    function uploadform_modelassociation() {
        $this->redir->set_last_index();
        $this->load->view('uploadform_modelassociation');
    }
    
    function upload_modelassociation() {
        $this->load->model(array('utilities/mdl_html'));
        $config['upload_path'] = './uploads/temp/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['file_name'] = $this->session->userdata('session_id') . '_' . date('Y_m_d_H_i_s');
        
        //$config['file_name'] = 'excel_file';
        $this->load->library('upload', $config);
        $file_name = 'excel_file';
        
        if (!$this->upload->do_upload($file_name)) {
            $error = array('error' => $this->upload->display_errors());
            dump($error);
            die();
        } else {
            
            $arr = $this->upload->data();
            
            $data = array('upload_data' => $arr);
            
            $this->load->library('spreadsheet_excel_reader');
            
            //$path = str_replace("/","\\",$arr['full_path']);
            
            $path = $arr['full_path'];
            
            $this->spreadsheet_excel_reader->read($path);
            
            $rows = $this->spreadsheet_excel_reader->sheets[0]['cells'];
            
            $row_count = count($this->spreadsheet_excel_reader->sheets[0]['cells']);
            
            $this->load->model(array('parts/mdl_parts_models','parts/mdl_model_parts'));
            $model_numbers = $this->mdl_model_parts->getModels();
           
            
            $parts = $this->mdl_parts_models->getPartModelOptions();
       
            //echo '<pre>';
            //print_r($parts);
            //die();
            //$part_arr = array();
            //foreach($parts as $part){
            //$part_arr[] = trim($part->text," ");
            
            //}
            
            $data = array('rows' => $rows, 'row_count' => $row_count, 'parts' => $parts, 'model_numbers'=>$model_numbers
            
            // 'part_arr'=>$part_arr
            );
            
            @unlink($path);
            
            $this->load->view('excel_list_modelassociation', $data);
        }
    }
    
    function savep_modelassociation() {
        $this->load->model(array('parts/mdl_parts_models', 'parts/mdl_parts', 'parts/mdl_model_parts'));
        $part_no = $this->input->post('part_no');
        $model_no = $this->input->post('model_no');


        $part_no = $this->mdl_parts->getPartsId($part_no);
        $model_no = $this->mdl_parts->getModelsId($model_no);

        for ($i=0; $i < count($model_no); $i++) { 
            if(empty($model_no[$i]) || $model_no[$i] == 'NULL' ){
                unset($model_no[$i]);
                unset($part_no[$i]);
            }
        }
        
        $task_select = $this->input->post('task_select');
        
       

        $i = 0;
        $cnt = 0;
        $error = array();
        foreach ($task_select as $task) {
            if ($task < 3 && $task >= 0) {
                
                //if task =1 the insert
                if ($task == 1) {
                    $data = array('part_id' => $this->db->escape_like_str($part_no[$i]), 'model_id' => $this->db->escape_like_str($model_no[$i]));
                    if ($this->db->insert($this->mdl_parts_models->table_name, $data)) {
                        
                        //echo $this->db->last_query();
                        
                    } else {
                    }
                } else {
                    
                    $sql = "REPLACE INTO " . $this->mdl_parts_models->table_name . " (part_id, model_id) VALUES ('" . $part_no[$i] . "','" . $model_no[$i] . "')";
                    $this->db->query($sql);
                    
                    /*$data = array(
                    'model_id'=>$model_no[$i]
                    );
                    $this->db->where('part_id', $part_no[$i]);
                    $this->db->update($this->mdl_parts_models->table_name, $data);
                    */
                }
            }
            $i++;
        }
        
        //$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_uploaded'));
        redirect('parts/modelpartreport');
        
        //$this->load->view('dashboard/ajax_messages',$error);
        
    }
    function _post_handler_modelassociation() {
        if ($this->input->post('save_xldata_modelassociation')) {
            
            $this->load->model(array('parts/mdl_parts_models'));
            $part_no = $this->input->post('part_no');
            $model_no = $this->input->post('model_no');
            $task_select = $this->input->post('task_select');
            $i = 0;
            $cnt = 0;
            $error = array();
            foreach ($task_select as $task) {
                if ($task < 3 && $task >= 0) {
                    
                    //if task =1 the insert
                    if ($task == 1) {
                        $data = array('part_number' => $this->db->escape_like_str($part_no[$i]), 'model_number' => $this->db->escape_like_str($model_no[$i]), 'part_created_by' => $this->session->userdata('user_id'), 'part_created_ts' => date("Y-m-d H:i:s"));
                        if ($this->db->insert($this->mdl_parts_models->table_name, $data)) {
                            
                            //echo $this->db->last_query();
                            
                        } else {
                        }
                    } else {
                        $data = array('model_number' => $part_no[$i], 'part_last_mod_by' => $this->session->userdata('user_id'), 'part_last_mod_ts' => date("Y-m-d H:i:s"));
                        $this->db->where('part_number', $part_no[$i]);
                        $this->db->where('model_number', $model_no[$i]);
                        $this->db->update($this->mdl_parts_models->table_name, $data);
                    }
                }
                $i++;
            }
            
            //$error = array('type'=>'success','message'=>$this->lang->line('this_record_has_been_uploaded'));
            redirect('parts');
            
            //$this->load->view('dashboard/ajax_messages',$error);
            
        }
    }
    
    function search() {
        
        $this->redir->set_last_index();
        $this->load->model(array('products/mdl_products', 'productmodel/mdl_productmodel', 'brands/mdl_brands', 'utilities/mdl_html', 'parts/mdl_parts'));
        $brandOptions = $this->mdl_brands->getBrandOptions();
        
        $b = array();
        foreach ($brandOptions as $row) {
            $b[] = $row->value;
        }
        $brand_select = $this->mdl_html->genericlist($brandOptions, 'brand_id', array('onchange' => 'getProductBybrand($(this).val());', 'class' => 'validate[required] select-one', 'multiple' => 'multiple', 'style' => 'height:120px;'), 'value', 'text', $b);
        
        //select box for products
        
        $productOptions = $this->mdl_products->getProductOptionsByBrands(implode(",", $b));
        $p = array();
        foreach ($productOptions as $row1) {
            $p[] = $row1->value;
        }
        
        $product_select = $this->mdl_html->genericlist($productOptions, 'product_id', array('onchange' => 'getModelsByProducts($(this).val());', 'class' => 'validate[required] select-one', 'multiple' => 'multiple', 'style' => 'height:120px;'), 'value', 'text', $p);
        
        //Select For models
        
        $modelSelect = $this->mdl_productmodel->getModelsByProductsid(implode(",", $p));
        
        $m = array();
        foreach ($modelSelect as $row1) {
            $m[] = $row1->value;
        }
        
        $model_select = $this->mdl_html->genericlist($modelSelect, 'model_number[]', array('class' => 'validate[required] select-one', 'multiple' => 'multiple', 'style' => 'height:120px;'), 'value', 'text', $m);
        
        $data = array(
        'brand_select' => $brand_select, 'product_select' => $product_select, 'model_select' => $model_select);
        $this->load->view('tab_search', $data);
    }

}
?>