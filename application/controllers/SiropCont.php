<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SiropCont extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('MyModel');
		$this->load->helper('text');
        $this->load->helper('form');
        $this->load->library('image_lib');
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->load->helper('url');
        $this->load->helper('download');
	}
    
    //menampilkan halaman dashboard
	public function index()
	{
        $data['schedule'] = $this->MyModel->schedule($this->session->userdata('id'));
        $date = time(); //Current date 
        $weekDay = date('w', strtotime('+1 day',$date));
        $data['tomorrow'] = $this->MyModel->tomorrow($weekDay+1, $this->session->userdata('id'));

		$this->load->view('dashboard', $data);
	}
    
    //menampilkan halaman profile
    public function profile()
	{
        $data['profile'] = $this->MyModel->getDataStudents($this->session->userdata('id'));   
		$this->load->view('profile', $data);
	}
    
    //untuk melakukan update data profile
    public function update_profile()
	{
        $data = array(
            'name_students'       => $this->input->post('name'),
            'email_students'	  => $this->input->post('email'),
            'address_students'	  => $this->input->post('address')
        );
        $data2 = array(
            'username_user'       => $this->input->post('username'),
            'password_user'		  => $this->input->post('password')
        );
                
        $this->MyModel->updateData('students', 'id_students', $this->session->userdata('id'), $data);
        $this->MyModel->updateData('user', 'id_students', $this->session->userdata('id'), $data2);
        
		redirect('SiropCont/profile');
	}
    
    //menampilkan halaman mycourses
    public function mycourses()
	{
        $data['mycourses'] = $this->MyModel->getDataMyCourses($this->session->userdata('id'));
        $data['othercourses'] = $this->MyModel->getDataOtherCourses($this->session->userdata('id'));
        $data['day'] = $this->MyModel->getData('day');
        $data['class'] = $this->MyModel->getDataWhere('classrooms', 'status_class', '1');
		$this->load->view('mycourses', $data);
	}
    
    //menambah daftar course
    public function add_courses()
	{
        $data = array(
            'name_courses'          => $this->input->post('name_courses'),
            'teacher_courses'		=> $this->input->post('teacher_courses'),
            'start_time_courses'    => $this->input->post('start_time_courses'),
            'end_time_courses'	=> $this->input->post('end_time_courses'),
            'id_class'		        => $this->input->post('id_class')
        );
        $this->MyModel->addData('courses', $data);
        $data['courses'] = $this->MyModel->getDataLast('courses', 'id_courses'); 
        $data2 = array(
            'id_courses'      => $data['courses'][0]->id_courses,
            'id_day'          => $this->input->post('day')
        );     
        $this->MyModel->addData('courses_day', $data2);

		redirect('SiropCont/mycourses');
	}
    
    //melakukan add
    public function addto_mycourses()
	{
        $data = array(
            'id_students'         => $this->input->post('id_students'),
            'id_courses'		  => $this->input->post('id_courses')
        );
                
        $this->MyModel->addData('students_courses', $data);
        
		redirect('SiropCont/mycourses');
	}
    
    //menghapus data pada mycourses
    public function del_mycourses($id_courses, $id_students)
	{
        $data = array(
            'id_students'         => $id_students,
            'id_courses'		  => $id_courses
        );
        $this->MyModel->deleteData2('students_courses', $data);
                
		redirect('SiropCont/mycourses');
	}
    
    //menampilkan halaman classroom
    public function classroom()
	{
        $data['class'] = $this->MyModel->getData('classrooms');      
		$this->load->view('classroom', $data);
	}
    
    //melakukan add data 
    public function add_class()
	{
        $data = array(
            'name_class'         => $this->input->post('name_class')
        );
                
        $this->MyModel->addData('classrooms', $data);
        
		redirect('SiropCont/classroom');
	}
    
    //menghapus data 
    public function del_class($id)
	{
        $this->MyModel->deleteData('classrooms', 'id_class', $id);           
		redirect('SiropCont/classroom');
	}
    
    //mengirim email
    function sendMail() {
        $targetemail = $this->session->userdata('email');
        $isi = $this->input->post('isi');
        
       $ci = get_instance();

                $ci->load->library('email');

                $config['protocol'] = "smtp";
                $config['smtp_host'] = "smtp.gmail.com";
                $config['smtp_port'] = "465";
                $config['smtp_user'] = "[CHANGE HERE]";
                $config['smtp_pass'] = "[CHANGE HERE]";
                $config['smtp_crypto'] = "ssl";
                $config['smtp_timeout'] = "4";
                $config['charset'] = "utf-8";
                $config['mailtype'] = "html";
                $config['newline'] = "\r\n";
                $config['wordwrap'] = TRUE;

                $ci->email->initialize($config);
                $ci->email->set_newline("\r\n");
                $ci->email->from('[CHANGE HERE]', '[CHANGE HERE]');
                $ci->email->to($targetemail);
                $ci->email->subject('Courses Remainder');
                $ci->email->message($isi);
        if ($this->email->send()) {
            redirect('SiropCont/shipment');
        } else {
            show_error($this->email->print_debugger());
        }
    }
    
    //logout 
    function logout()
	{  
		$this->session->sess_destroy();
		redirect('login');
	}
}
