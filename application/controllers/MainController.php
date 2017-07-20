<?php
//This is the main controller

defined('BASEPATH') OR exit('No direct script access allowed');

class MainController extends CI_Controller {
    
    private $project_name = 'EMS';

    public function __construct() {
        parent::__construct();

        //check the user if not logged in
        if($this->session->userdata('username') === NULL ) {
            redirect('login');
        }
    }

    public function index() {

        $data['project_name'] = $this->project_name;
        $data['title'] = $this->project_name.' | Home';
        $data['content_view'] = 'views/pages/home.php';
        
        $this->load->view('main', $data);
    }

    public function employee() {

        $config['base_url'] = site_url().'/employee';
        $config['total_rows'] = $this->db->count_all('ems_employee');
        $config['per_page'] = 5;
        $config['uri_segment'] = 2;

        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] ="</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tagl_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";   

        $this->pagination->initialize($config);

        $data['project_name'] = $this->project_name;
        $data['title'] = $this->project_name.' | Employee List';
        $data['content_view'] = 'views/pages/employee-list.php';

        //get all employee
        $data['employees'] = $this->employee->getAllEmployees($config['per_page'], $this->uri->segment(2));
        
        $this->load->view('main', $data);
    }

    public function add_employee() {
        $data['project_name'] = $this->project_name;
        $data['title'] = $this->project_name.' | Add Employee';
        $data['content_view'] = 'views/pages/employee-add.php';
        $data['emp_id'] = $this->generateEmployeeId();
        
        $this->load->view('main', $data);
    }

    public function add_employee_process() {

        $data = [
            'emp_id'      => $this->generateEmployeeId(),
            'emp_name'    => $this->input->post('emp_name'),
            'emp_bday'    => $this->input->post('emp_bday'),
            'emp_address' => $this->input->post('emp_address'),
            'emp_salary'  => $this->input->post('emp_salary')
        ];

        //print_r($data);

        if($this->employee->addEmployee($data)) {
            $this->session->set_flashdata('add-success', 'New Employee Added!');
            redirect('employee');
        } else {
            $this->session->set_flashdata('add-failed', 'Error on adding new employee.');
            redirect('add');
        }
    }

    public function update_employee($id) {

        $data['project_name'] = $this->project_name;
        $data['title'] = $this->project_name.' | Update Employee';
        $data['content_view'] = 'views/pages/employee-update.php';
        $data['employee'] =  $this->employee->getEmployee($id);
        
        $this->load->view('main', $data);
    }

    public function update_employee_process($id) {
        $data = [
            'emp_name'    => $this->input->post('update_emp_name'),
            'emp_bday'    => $this->input->post('update_emp_bday'),
            'emp_address' => $this->input->post('update_emp_address'),
            'emp_salary'  => $this->input->post('update_emp_salary')
        ];

        if($this->employee->updateEmployee($id, $data)) {
            $this->session->set_flashdata('update-success', ' Employee has been updated!');
            redirect('employee');
        } else {
            $this->session->set_flashdata('update-failed', 'Error on updating an employee.');
            redirect('update/'.$id);
        }
    }

    public function delete($id) {
        if($this->employee->delete($id)) {
            redirect('employee');
        }
    }


    public function logout() {
        $this->session->unset_userdata(['status','id','username','name']);
        redirect('login');
    }

    private function generateEmployeeId() {
        $last = $this->employee->getLast();
        $id = substr($last->emp_id, 3);
        $id += 1;
        return 'EMS'.$id;
    }
}