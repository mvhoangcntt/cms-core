<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends Public_Controller
{
    protected $cid = 0;
    protected $_data;
    protected $_data_category;
    protected $_lang_code;
    protected $_all_category;

    public function __construct()
    {
        parent::__construct();
        //tải model
        $this->load->model(['category_model', 'post_model']);
        $this->_data = new Post_model();
        $this->_data_category = new Category_model();
        //$this->session->category_type = 'post';
        //Check xem co chuyen lang hay khong thi set session lang moi
        if ($this->input->get('lang'))
            $this->_lang_code = $this->input->get('lang');
        else
            $this->_lang_code = $this->session->public_lang_code;

        if(!$this->cache->get('_all_category_'.$this->session->public_lang_code)){
            $this->cache->save('_all_category_'.$this->session->public_lang_code,$this->_data_category->getAll($this->session->public_lang_code,1),60*60*30);
        }
        $this->_all_category = $this->cache->get('_all_category_'.$this->session->public_lang_code);
    }

    public function category($id, $page = 1)
    {
        $oneItem = $this->_data_category->getById($id,'', $this->_lang_code);
        if (empty($oneItem)) show_404();

        if ($this->input->get('lang')) {
            redirect(getUrlCateNews(['slug' => $oneItem->slug, 'id' => $oneItem->id]));
        }
        $data['category'] = $oneItem;
        $data['oneParent'] = $oneParent = $this->_data_category->_recursive_one_parent($this->_all_category,$id);
        /*Lay list id con của category*/
        $this->_data_category->_recursive_child_id($this->_all_category,$id);
        $data['list_cate'] = $this->_data_category->getListChildLv1($this->_all_category,$id);
        $listCateId = $this->_data_category->_list_category_child_id;

        $data['cateNew_Event'] = $this->cateNew_Event();
        /*Lay list id con của category*/
        $limit = 8;
        $params = array(
            'is_status' => 1, //0: Huỷ, 1: Hiển thị, 2: Nháp
            'lang_code' => $this->_lang_code,
            'category_id' => $id,
            'limit' => $limit,
            'page' => $page
        );
        $data['data'] = $this->_data->getData($params);
        $data['total'] = $this->_data->getTotal($params);
        if(empty($listCateId)) $data['data'] = null;
        /*Pagination*/
        $this->load->library('pagination');
        $paging['base_url'] = getUrlCateNews(['slug' => $oneItem->slug, 'id' => $oneItem->id, 'page' => 1]);
        $paging['first_url'] = getUrlCateNews(['slug' => $oneItem->slug, 'id' => $oneItem->id]);
        $paging['total_rows'] = $data['total'];
        $paging['per_page'] = $limit;
        $paging['attributes'] = array('class'=>"");
        $this->pagination->initialize($paging);
        $data['pagination'] = $this->pagination->create_links();
        $data['max_page'] = round($data['total'] / $limit) + 1;
        /*Pagination*/

        //add breadcrumbs
        $this->breadcrumbs->push(" <i class='fa fa-home'></i>", base_url());
        if($oneParent->id != 0) $this->breadcrumbs->push($oneParent->title, getUrlCateNews($oneParent));
        $this->breadcrumbs->push($oneItem->title, getUrlCateNews($oneItem));
        $data['breadcrumb'] = $this->breadcrumbs->show();
        //SEO Meta
        $data['SEO'] = [
            'meta_title' => !empty($oneItem->meta_title) ? $oneItem->meta_title : $oneItem->title,
            'meta_description' => !empty($oneItem->meta_description) ? $oneItem->meta_description : $oneItem->description,
            'meta_keyword' => !empty($oneItem->meta_title) ? $oneItem->meta_keyword : '',
            'url' => getUrlCateNews($oneItem),
            'image' => getImageThumb($oneItem->thumbnail, 400, 200)
        ];
        if(!empty($oneItem->style)) $layoutView = '-'.$oneItem->style;
        else $layoutView = '';
        $data['main_content'] = $this->load->view($this->template_path . 'news/category'.$layoutView, $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function detail($id)
    {
        $oneItem = $this->_data->getById($id,'', $this->_lang_code);
        if (empty($oneItem)) show_404();
        //Check xem co chuyen lang hay khong thi redirect ve lang moi
        if ($this->input->get('lang')) {
            redirect(getUrlNews(['slug' => $oneItem->slug, 'id' => $oneItem->id]));
        }
        $data['oneItem'] = $oneItem;
        $data['oneCategory'] = $oneCategory = $this->_data->getOneCateIdById($id);
        $data['oneParent'] = $oneCategoryParent = $this->_data_category->_recursive_one_parent($this->_all_category,$data['oneCategory']->id);
        if(!empty($data['oneParent'])){
            $data['list_category_child'] = $this->_data_category->getCategoryChild($data['oneParent']->id,$this->session->public_lang_code);
        }
        /*Get news related*/
        $this->_data_category->_recursive_child_id($this->_all_category,$data['oneCategory']->id);
        $listCateId = $this->_data_category->_list_category_child_id;
        $params = array(
            'is_status' => 1, //0: Huỷ, 1: Hiển thị, 2: Nháp
            'lang_code' => $this->_lang_code,
            'limit' => 5,
            'not_in' => $id,
        );
        $data['list_news'] = $this->_data->getData($params);
        $params = array_merge($params, ['category_id' => $listCateId]);
        $data['list_related'] = $this->_data->getData($params);
        //add breadcrumbs
        $this->breadcrumbs->push(" <i class='fa fa-home'></i>", base_url());
        $this->_data_category->_recursive_parent($this->_all_category, $oneCategory->id);
        if(!empty($this->_data_category->_list_category_parent)) foreach (array_reverse($this->_data_category->_list_category_parent) as $item){
            $this->breadcrumbs->push($item->title, getUrlCateNews($item));
        }
        $this->breadcrumbs->push($oneItem->title, getUrlNews($oneItem));
        $data['breadcrumb'] = $this->breadcrumbs->show();
        //SEO Meta
        $data['SEO'] = [
            'meta_title' => !empty($oneItem->meta_title) ? $oneItem->meta_title : $oneItem->title,
            'meta_description' => !empty($oneItem->meta_title) ? $oneItem->meta_description : $oneItem->description,
            'meta_keyword' => !empty($oneItem->meta_title) ? $oneItem->meta_keyword : '',
            'url' => getUrlNews(['slug' => $oneItem->slug, 'id' => $oneItem->id]),
            'image' => getImageThumb($oneItem->thumbnail, 400, 200)
        ];
        if(!empty($oneCategoryParent->style)) $layoutView = '-'.$oneCategoryParent->style;
        else $layoutView = '';
        $data['main_content'] = $this->load->view($this->template_path . 'news/detail'.$layoutView, $data, TRUE);
        $this->load->view($this->template_main, $data);
    }
    public function uploadCV(){
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->form_validation->set_rules('fullname', lang('text_fullname'), 'trim|required');
            $this->form_validation->set_rules('email', "Email", 'trim|required|valid_email');
            $this->form_validation->set_rules('phone', lang('text_phone'), 'required|trim|min_length[9]|max_length[14]|regex_match[/^(09|012|08|016|03|05|07|08)\d{8,}/]');
            $this->form_validation->set_rules('file_cv', 'File CV', 'callback_valid_file_cv');
            // $this->form_validation->set_rules('file_letter', 'Cover letter', 'callback_valid_file_letter');
            if ($this->form_validation->run() == true) {
                
                $fileCV = $this->input->post('email').'_cv';
                $fileCV = preg_replace('/[^a-z0-9]/i', '_', $fileCV);
                $fileCoverLetter = '';
                if (!empty($this->input->post('file_letter'))) {
                    $fileCoverLetter = $this->input->post('email').'_coverletter';
                    $fileCoverLetter = preg_replace('/[^a-z0-9]/i', '_', $fileCoverLetter);
                }

                $fileNews = [
                    'file_cv' => $fileCV,
                    'file_letter' => $fileCoverLetter
                ];
                $fileNew = $this->do_upload($fileNews);
                $data = array(
                    'title' => $this->input->post('title'),
                    'fullname' => $this->input->post('fullname'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'address' => $this->input->post('address'),
                    'file_cv' => 'cv/'.$fileNew['file_cv']['file_name'],
                    'file_letter' => !empty($fileNew['file_letter']['file_name']) ? 'cv/'.$fileNew['file_letter']['file_name']: ''
                );
                $this->load->model('career_model');
                $ungtuyenModel = new Career_model();
                $result=$ungtuyenModel->save($data);
                if($result){
                    $message['type'] = 'success';
                    $message['message'] = lang('mess_send_career');
                    $this->sendMailApply($this->input->post());
                }else{
                    $message['type'] = 'error';
                    $message['message'] = "Lưu thất bại !";
                }

            }else{
                $valid["fullname"]    = form_error("fullname");
                $valid["email"]       = form_error("email");
                $valid["phone"]       = form_error("phone");
                $valid["file_cv"]     = form_error("file_cv");
                $valid["file_letter"] = form_error("file_letter");

                $message['validation'] = $valid;
                $message['type'] = 'warning';
                $message['message'] = lang('mess_validation');
            }
            die(json_encode($message));

        }
    }

    private function do_upload($rename = array()){
        $this->load->library('upload');
        $files = $_FILES;
        $data = array();
        $data['error'] = true;
        $data['message'] = '';
        foreach ($files as $key => $file){
            if(!empty($files[$key]['name'])){
                $_FILES['files']['name']= $files[$key]['name'];
                $_FILES['files']['type']= $files[$key]['type'];
                $_FILES['files']['tmp_name']= $files[$key]['tmp_name'];
                $_FILES['files']['error']= $files[$key]['error'];
                $_FILES['files']['size']= $files[$key]['size'];

                $this->upload->initialize($this->set_upload_options($rename[$key]));
                if (!$this->upload->do_upload($key)) {
                    $error = $this->upload->display_errors();
                    dump($error);
                    log_message('error',json_encode($error));
                    $message['type'] = 'error';
                    $message['message'] = $error;

                }
                $data[$key] = $this->upload->data();
            }
        }
        return $data;
    }

    private function set_upload_options($rename){
        $path = MEDIA_PATH.'/cv/';
        if(!is_dir($path)){
            mkdir($path, 0755, TRUE);
        }

        $config['upload_path']          = MEDIA_PATH.'/cv/';
        $config['file_name']            = $rename;
        $config['overwrite']            = TRUE;
        $config['allowed_types']        = '*';
        $config['max_size']             = 5000;
        return $config;
    }

    private function sendMailApply($data){
        $this->load->library('email');
        $emailTo = $data['email'];
        //$emailToBCC = '';

        $emailFrom = $this->settings['email_admin'];


        $message = '<strong>Thông tin ứng viên: </strong>' . "\n";

        $message .= "<p>Họ và tên: {$data['fullname']}</p>";

        $message .= '<p>Email: ' . $data['email'] . "</p>";

        $message .= '<p>Điện thoại: ' . $data['phone'] . "</p>";

        $message .= "<p>Trân trọng, </p>";

        $this->email->from($emailFrom, $this->settings['name']);

        $this->email->to($emailTo);
        if (!empty($emailToCC)) $this->email->cc($emailToCC);
        if (!empty($emailToBCC)) $this->email->bcc($emailToBCC);

        $this->email->subject(html_entity_decode('Job: ' . $data['title'] . ' - ' . $data['fullname'], ENT_QUOTES, 'UTF-8'));
        $this->email->message($message);
        if (!$this->email->send()) {
            $error = $this->email->print_debugger(array('headers'));
            log_message('error',$error);
        }
    }

    public function valid_file_cv(){
        $allowed_mime_type_arr = array('application/msword', 'application/vnd.ms-office','application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/msword', 'application/x-zip','application/pdf', 'application/force-download', 'application/x-download', 'binary/octet-stream','image/jpg','image/png','image/jpeg');
        if(!empty($_FILES['file_cv']['name'])){
            $mime = get_mime_by_extension($_FILES['file_cv']['name']);
            if(!in_array($mime, $allowed_mime_type_arr)){
                $this->form_validation->set_message('valid_file_cv', lang('valid_file_cv1'));
                return false;
            }
            if(isset($_FILES['file_cv']['size']) && $_FILES['file_cv']['size'] > 5128931){
                log_message('error','FILE SIZE: '.$_FILES['file_cv']['size']);
                $this->form_validation->set_message('valid_file_cv', lang('valid_file_cv2'));
                return false;
            }else return true;

        }else{
            $this->form_validation->set_message('valid_file_cv', lang('valid_file_cv3'));
            return false;
        }
        return true;
    }
    public function valid_file_letter(){
        $allowed_mime_type_arr = array('application/msword', 'application/vnd.ms-office','application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/msword', 'application/x-zip','application/pdf', 'application/force-download', 'application/x-download', 'binary/octet-stream');
        $mime = get_mime_by_extension($_FILES['file_letter']['name']);
        if(isset($_FILES['file_letter']['name']) && $_FILES['file_letter']['name']!=""){
            if(!in_array($mime, $allowed_mime_type_arr)){
                $this->form_validation->set_message('valid_file_letter', lang('valid_fileletter_cv1'));
                return false;
            }
            if(isset($_FILES['file_letter']['size']) && $_FILES['file_letter']['size'] > 5128931){
                $this->form_validation->set_message('valid_file_letter', lang('valid_fileletter_cv2'));
                return false;
            }else return true;
        }else{
            $this->form_validation->set_message('valid_file_letter', lang('valid_fileletter_cv3'));
            return false;
        }
        return true;
    }
    private function cateNew_Event(){
        $data = $this->_data_category->getListChildLv1($this->_all_category,119);
        return $data;
    }
    
}
