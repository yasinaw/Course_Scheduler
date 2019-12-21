<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MyModel extends CI_Model {
	
    //untuk melakukan login
    function cek_login($table,$where){		
			return $this->db->get_where($table,$where);
		}
    
    //untuk mendapatkan data pada suatu tabel
	function getData($table) {
 			$query = $this->db->get($table);
 			return $query->result();
 		}
    
    //untuk mendapatkan data pada suatu tabel order from last
	function getDataLast($table, $column) {
            $this->db->order_by($column, 'DESC');
 			$query = $this->db->get($table);
 			return $query->result();
 		}
    
    //untuk menambahkan suatu data kedalam suatu tabel
    function addData($table, $data) {
 			$this->db->insert($table, $data);  
 		}
    
    //untuk menghapus suatu data dalam suatu tabel
    function deleteData($table, $column, $where){
			 $this->db->where_in($column, $where);
			 $this->db->delete($table);
		}
    
    //untuk menghapus suatu data dalam suatu tabel
    function deleteData2($table, $where){
			 $this->db->where($where);
			 $this->db->delete($table);
		}
    
    //untuk melakukan update pada suatu data dalam suatu tabel
    function updateData($table, $column, $where, $data){
		    $this->db->where($column, $where);
		    return $this->db->update($table, $data);
		}
    
    //untuk mendapatkan data yg spesifik pada suatu tabel
    function getDataWhere($table, $column, $where) {
            $this->db->select('*');
            $this->db->from($table);
 			$this->db->where($column, $where);
            $this->db->where($column, $where);
            $this->db->order_by($column, 'DESC');
 			$query = $this->db->get();
 			return $query->result();
 		}
    
    //untuk menampilkan jadwal
    public function schedule($where){
        $this->db->select('day.name_day, courses.start_time_courses, courses.end_time_courses, courses.name_courses, courses.teacher_courses ');
		$this->db->from('courses');
        $this->db->join('courses_day', 'courses.id_courses = courses_day.id_courses');
        $this->db->join('day', 'day.id_day = courses_day.id_day');
        $this->db->join('students_courses', 'students_courses.id_courses = courses.id_courses');
        $this->db->join('students', 'students.id_students = students_courses.id_students');
        $this->db->where('students.id_students', $where);
        $this->db->order_by('day.id_day', 'ASC');
        $this->db->order_by('courses.start_time_courses', 'ASC');
        $hasil = $this->db->get();
		if($hasil->num_rows() > 0){
			return $hasil->result();
		}
		else {
			return array();
		}
    }
    
    //untuk menampilkan students data
    public function getDataStudents($where){
        $this->db->select('* ');
		$this->db->from('user');
        $this->db->join('students', 'students.id_students = user.id_students');
        $this->db->where('students.id_students', $where);
        $hasil = $this->db->get();
		if($hasil->num_rows() > 0){
			return $hasil->result();
		}
		else {
			return array();
		}
    }
    
    //untuk menampilkan mycourses
    public function getDataMyCourses($where){
        $this->db->select('*');
		$this->db->from('courses');
        $this->db->join('courses_day', 'courses.id_courses = courses_day.id_courses');
        $this->db->join('day', 'day.id_day = courses_day.id_day');
        $this->db->join('students_courses', 'students_courses.id_courses = courses.id_courses');
        $this->db->join('students', 'students.id_students = students_courses.id_students');
        $this->db->where('students.id_students', $where);
        $this->db->order_by('day.id_day', 'ASC');
        $this->db->order_by('courses.start_time_courses', 'ASC');
        $hasil = $this->db->get();
		if($hasil->num_rows() > 0){
			return $hasil->result();
		}
		else {
			return array();
		}
    }
    
    //untuk menampilkan courses lain
    public function getDataOtherCourses($where){
        $hasil = $this->db->query("SELECT * FROM courses JOIN courses_day on courses_day.id_courses = courses.id_courses JOIN day ON day.id_day = courses_day.id_day WHERE courses.id_courses NOT IN (SELECT id_courses FROM students_courses JOIN students ON students_courses.id_students = students.id_students WHERE students.id_students = $where) ");
        if($hasil->num_rows() > 0){
            return $hasil->result();
          }
        else {
            return array();
          }
    }
    
    //untuk menampilkan jadwal besok
    public function tomorrow($where, $where2){
        $this->db->select('day.name_day, courses.start_time_courses, courses.end_time_courses, courses.name_courses, courses.teacher_courses ');
		$this->db->from('courses');
        $this->db->join('courses_day', 'courses.id_courses = courses_day.id_courses');
        $this->db->join('day', 'day.id_day = courses_day.id_day');
        $this->db->join('students_courses', 'students_courses.id_courses = courses.id_courses');
        $this->db->join('students', 'students.id_students = students_courses.id_students');
        $this->db->where('courses_day.id_day', $where);
        $this->db->where('students.id_students', $where2);
        $this->db->order_by('day.id_day', 'ASC');
        $this->db->order_by('courses.start_time_courses', 'ASC');
        $hasil = $this->db->get();
		if($hasil->num_rows() > 0){
			return $hasil->result();
		}
		else {
			return array();
		}
    }
    
    //melakukan update suatu data pada suatu tabel
    function update_data($where,$data,$table){
		$this->db->where($where);
		$this->db->update($table,$data);
	}
}