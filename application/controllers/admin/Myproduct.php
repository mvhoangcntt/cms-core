<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Myproduct extends Admin_Controller
{

	public function __construct()
    {
        parent::__construct();
        $this->lang->load('myproduct');
        $this->load->model('myproduct_model');
        $this->_data = new Myproduct_model();
        $this->_name_controller = $this->router->fetch_class();
        $this->session->category_type = $this->_name_controller;
    }
    public function index(){
    	$data['heading_title'] = 'MV Hoàng';
        $data['heading_description'] = "Danh sách sản phẩm";
        $this->breadcrumbs->push('Home', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        $data['size'] = $this->_data->list_size_datatable();// lấy ra cho filter
    	$data['main_content'] = $this->load->view($this->template_path . $this->_name_controller. '/index', $data, TRUE);
        // var_dump($data); exit();
        $this->load->view($this->template_main, $data);
    }
    public function ajax_list()
    {
        $input_post = $this->input->post();
        $params['start']    = $input_post['start'];
        $params['length']   = $input_post['length'];
        $params['search']   = $input_post['search']['value'];
        $params['order']    = $input_post['order'][0]['dir'];
        $params['columns']  = $input_post['order'][0]['column'];
        $params['catalog']  = $input_post['catalog'];
        $params['maker_id'] = $input_post['maker_id'];
        $params['size']     = $input_post['size'];
        $list   = $this->myproduct_model->get_product_datatable($params);
        // var_dump($list); exit();
        $data = array();
        if(!empty($list)) foreach ($list as $item) {
            $size = $this->myproduct_model->get_size($item->product_id);
            $s = '';
            foreach ($size as $key) {
                $s .= $key->text_size." ";
            }
            $row = array();
            $row[] = $item->product_id;
            $row[] = $item->product_id;
            $row[] = $item->name;
            $row[] = $item->content;
            $row[] = $item->catalog;
            $row[] = '<img style="width: 50px" src="../public/media/'.$item->thumbnail.'">';
            $row[] = $s;
            $row[] = $item->maker_id;
            $row[] = $item->price;
            $row[] = $item->created;
            $row[] = $item->total;
            //thêm action
            $action = '<div class="text-center">';
            $action .= '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="'.$this->lang->line('btn_edit').'" onclick="edit_form('."'".$item->product_id."'".')"><i class="glyphicon glyphicon-pencil"></i></a>';
            $action .= '&nbsp;<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="' . $this->lang->line('btn_remove') . '" onclick="delete_item('."'".$item->product_id."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
            $action .= '</div>';
            $row[] = $action;
            $data[] = $row;
        }

        $product = array(
         "data"            => $data,
         "recordsTotal"    => $this->myproduct_model->countALL($params),
         "recordsFiltered" => $this->myproduct_model->countALL($params),
         "draw"            => $input_post['draw'],
        );
        exit(json_encode($product));
    }
    
    public function ajax_add()
    {
        $data = $this->_convertData();
        $data['created'] = date("Y-m-d");
        $quantity   =  $data['quantity'];
        $text_size  =  $data['textsize'];
        // unset để thêm vào bảng product
        unset($data['quantity']);
        unset($data['textsize']);
        $insert_id = $this->_data->set_products($data);
        if($insert_id != ''){
            $this->convert_size($insert_id, $quantity, $text_size);
        }else{
            $message['type'] = 'warning';
            $message['message'] = 'Lỗi thêm sản phẩm !';
            exit(json_encode($message));
        }
    }

    public function ajax_edit($id)
    {
        $data       = $this->_data->get_json($id);
        $data->size = $this->_data->get_size_come_product($id);
        die(json_encode($data));
    }

    public function ajax_update($id){
        $data = $this->_convertData();
        $quantity   =  $data['quantity'];
        $text_size  =  $data['textsize'];
        unset($data['quantity']); // không có trong bảng
        unset($data['textsize']);
        if ($this->_data->update_products($data, $id)) 
        {
            $this->convert_size($id, $quantity, $text_size);
        }else{
            $message['type'] = 'warning';
            $message['message'] = 'Lỗi sửa sản phẩm !';
            exit(json_encode($message));
        }
    }

    public function convert_size($id, $quantity, $text_size){
      // xóa thông tin cũ
        $this->_data->delete_size($id);
        foreach ($quantity as $key_quantity => $value_quantity) {
            $size = array(
                "product_id"   => $id,
                "text_size"    => $text_size[$key_quantity],
                "quantity"     => $value_quantity,
            );
            if($this->_data->set_size($size)){
                $message['type'] = 'warning';
                $message['message'] = 'Lỗi thêm size !';
                exit(json_encode($message));
            }
        }
        $message['type'] = 'success';
        $message['message'] = 'Thành công !';
        exit(json_encode($message));
    }
    private function _convertData(){
        $this->_validate();
        $data = $this->input->post();
        return $data;
    }
    //Kiêm tra thông tin post lên
    private function _validate()
    {
        if($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $config = array(
                'name'   => array(
                    'field'  => 'name',
                    'label'  => 'name',
                    'rules'  => 'required|min_length[5]',
                    'errors' => array(
                        'required'  => 'Không được để trống !',
                        'min_length'=> 'Nhập độ dài lớn hơn 5 ký tự !'
                   ),
                ),
                'content'   => array(
                    'field'  => 'content',
                    'label'  => 'content',
                    'rules'  => 'required|min_length[5]',
                    'errors' => array(
                        'required'  => 'Không được để trống !',
                        'min_length'=> 'Nhập độ dài lớn hơn 5 ký tự !',
                   ),
                ),
                'catalog'   => array(
                    'field'  => 'catalog',
                    'label'  => 'price',
                    'rules'  => 'required',
                    'errors' => array(
                        'required'  => 'Không được để trống !',
                   ),
                ),
                'maker_id'   => array(
                    'field'  => 'maker_id',
                    'label'  => 'price',
                    'rules'  => 'required',
                    'errors' => array(
                        'required'  => 'Không được để trống !',
                   ),
                ),
                'price'   => array(
                    'field'  => 'price',
                    'label'  => 'price',
                    'rules'  => 'required|numeric',
                    'errors' => array(
                        'required'  => 'Không được để trống !',
                        'numeric'   => 'Không phải là số !'
                   ),
                ),
                'total'   => array(
                    'field'  => 'total',
                    'label'  => 'total',
                    'rules'  => 'required|numeric',
                    'errors' => array(
                        'required'  => 'Không được để trống !',
                        'numeric'   => 'Không phải là số !'
                   ),
                ),
            );

            $post = $this->input->post();
            foreach ($post['quantity'] as $key => $value) {
                $config['quantity['.$key.']'] = array(
                    'field'  => "quantity[".$key."]",
                    'label'  => 'quantity_'.$key,
                    'rules'  => 'required|numeric',
                    'errors' => array(
                        'required'  => 'Không được để trống !',
                        'numeric'   => 'Không phải là số !'
                    ),
                );
                $config['textsize['.$key.']'] = array(
                    'field'  => "textsize[".$key."]",
                    'label'  => 'textsize_'.$key,
                    'rules'  => 'required',
                    'errors' => array(
                        'required'  => 'Không được để trống !',
                    ),
                );
            }

            $result = array();
            foreach ($config as $value) {
                $this->form_validation->set_rules(
                    $value['field'],
                    $value['label'],
                    $value['rules'],
                    $value['errors']
                );
            }
           
            if ($this->form_validation->run() === false) {
                $message['type'] = "warning";
                $message['message'] = "Đã có lỗi sảy ra vui lòng kiểm tra lại !";
                $valid = [];
                foreach ($config as $key => $value) {
                    $valid[$key] = form_error($value['field']);
                }
                $message['validation'] = $valid;
                die(json_encode($message));
            }
        }
    }
    public function ajax_delete($id){
        if($this->_data->delete_product($id)){
            $message['type'] = 'success';
            $message['message'] = "Xóa thành công !";
            die(json_encode($message));
        }else{
            $message['type'] = 'warning';
            $message['message'] = "Đã sảy ra lỗi !";
            die(json_encode($message));
        }
    }
}
