<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends Public_Controller
{
    protected $_data;
    protected $_data_category;
    protected $_lang_code;
    protected $_all_category;

    public function __construct()
    {
        parent::__construct();
        //tải model
        $this->load->model(['category_model', 'post_model', 'account_model', 'location_model', 'course_model']);
        $this->_data = new Post_model();
        $this->account = new Account_model();
        $this->locationModel = new Location_model();
        $this->course = new Course_model();

        if ($this->input->get('lang'))
            $this->_lang_code = $this->input->get('lang');
        else
            $this->_lang_code = $this->session->public_lang_code;
    }
    public function index($slug = null, $page = 1){ //Slug là của category page
        $this->load->model(['page_model', 'category_model', 'post_model']);
        $pageModel = new Page_model();
        $id = $pageModel->slugToId($slug);
        $oneItem = $pageModel->getById($id, '', $this->session->public_lang_code);
        if (empty($oneItem)) show_404();
        if ($this->input->get('lang')) redirect(getUrlPage($oneItem));
        $data['oneItem'] = $oneItem;
        //add breadcrumbs
        $this->breadcrumbs->push(" <i class='fa fa-home'></i>", base_url());
        $this->breadcrumbs->push($oneItem->title, getUrlPage($oneItem));
        $data['breadcrumb'] = $this->breadcrumbs->show();
        $data['SEO'] = $this->blockSEO($oneItem, getUrlPage($oneItem));
        if (!empty($oneItem->style)) $layoutView = '-' . $oneItem->style;
        else $layoutView = '';
        $data['main_content'] = $this->load->view($this->template_path . 'page/page' . $layoutView, $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function _404(){
        $data['main_content'] = $this->load->view($this->template_path . 'page/_404', NULL, TRUE);
        $this->load->view($this->template_main, $data);
    }
}
