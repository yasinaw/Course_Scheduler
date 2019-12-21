<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct(){
		parent::__construct();		
		$this->load->model('MyModel');

	}

	function index(){
		$data['err_message'] = "";
		if($this->session->userdata('role') == null){
			$this->load->view('login', $data);
		}else{ 
			redirect(base_url("SiropCont"));
		}
		
	}

	function aksi_login(){
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$where = array(
			'username_user' => $username,
			'password_user' => $password
			);
        
		$cek["login"] = $this->MyModel->cek_login('user',$where)->result();
        $cekjmlh = $this->MyModel->cek_login('user',$where)->num_rows();
		if($cekjmlh==1){
            foreach($cek["login"] as $c){     
                $data_session = array(
                    'username' => $username,
                    'status' => "login",
                    'id' => $c->id_students,
                    'email' => $c->email_students
                    );
            }

			$this->session->set_userdata($data_session);
			redirect(base_url("SiropCont"));

		}
        else{
			$data['message'] = "USERNAME / PASSWORD SALAH";
			$this->load->view('login', $data);
			
		}
	}
    
    //untuk melakukan add acc
    public function create_acc()
	{
        $where = array(
			'username_user' => $this->input->post('username')
			);
        $cekjmlh = $this->MyModel->cek_login('user',$where)->num_rows();
        if ($cekjmlh > 0){
            $data['message'] = "Username telah terpakai";
			$this->load->view('login', $data);
        } else {
            $data = array(
            'name_students'       => $this->input->post('name'),
            'email_students'	  => $this->input->post('email'),
            'address_students'	  => $this->input->post('address')
            );
            $this->MyModel->addData('students',$data);
            $data['students'] = $this->MyModel->getDataLast('students', 'id_students'); 
            $data2 = array(
                'username_user'       => $this->input->post('username'),
                'password_user'		  => $this->input->post('password'),
                'id_students'         => $data['students'][0]->id_students
            );

            $this->MyModel->addData('user', $data2);

            $data['message'] = "Account Berhasil Ditambahkan";
			$this->load->view('login', $data);
        }
        
	}

	function logout(){
		$this->session->sess_destroy();
		redirect(base_url());
	}
}