<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends Public_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('form_validation','hybridauth','ion_account','session'));
		$this->load->helper(array('url','form'));
		$this->load->model(array('contact_model','newsletter_model','account_model'));
		$this->_data 		= new Contact_model();
		$this->newsletter 	= new Newsletter_model();
		$this->account 		= new Account_model();
	}

	public function submit()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$this->load->library('email');
			$emailTo = $this->settings['email_admin'];
			$emailToBCC = '';
			$rules = array(
				array(
					'field' => 'email',
					'label' => 'Email',
					'rules' => 'trim|required|valid_email'
				),
				array(
					'field' => 'fullname',
					'label' => lang('text_fullname'),
					'rules' => 'required|trim'
				),
				array(
					'field' => 'phone',
					'label' => lang('text_phone'),
					'rules' => 'required|trim|min_length[10]|max_length[12]|regex_match[/^(09|012|08|016|03|05|07|08)\d{8,}/]'
				),
				array(
					'field' => 'content',
					'label' => lang('text_content'),
					'rules' => 'required|trim'
				)

			);
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == true) {
				$emailFrom = $this->input->post('email');
				$nameFrom = $this->input->post('fullname');
				$phone = $this->input->post('phone');
				$title = "Thông tin liên hệ";
				$content = $this->input->post('content');

				$contentHtml = '
				<h2>Dear ' . $this->settings['name'] . ' !</h2></br>

				<p>Họ và tên: ' . $nameFrom . '</p>
				<p>Email: ' . $emailFrom . '</p>
				<p>Số điện thoại: ' . $phone . '</p>
				<p>Nội dung: ' . $content . '</p>
				';
				$data = $this->input->post();
				$this->email->from($emailFrom, $nameFrom);

				$this->email->to($emailTo);
				if (!empty($emailToCC)) $this->email->cc($emailToCC);
				if (!empty($emailToBCC)) $this->email->bcc($emailToBCC);

				$this->email->subject($title);
				$this->email->message($contentHtml);
				if ($this->email->send() &&  $this->_data->save($data, 'contact')) {
					$message['type'] = 'success';
					$message['message'] = lang('sign_up_for_s');
					die(json_encode($message));
				} else {
					$message['type'] = 'warning';
					$message['message'] = lang('mess_send_unsuccess');
					die(json_encode($message));
				}
			}else{
				$message['type'] = "warning";
				$message['message'] = lang('mess_validation');
				$valid = array();
				if(!empty($rules)) foreach ($rules as $item){
					if(!empty(form_error($item['field']))) $valid[$item['field']] = form_error($item['field']);
				}
				$message['validation'] = $valid;
				die(json_encode($message));
			}
		}
	}


}
