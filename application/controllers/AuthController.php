<?php
//This is the auth controller

defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {
    
    private $project_name = 'EMS';

    public function __construct() {
        parent::__construct();
        //check if already logged in the user
        if($this->session->userdata('username') !== NULL ) {
            redirect('/');
        }
    }

    //This is the login view
    public function index() {

        $data['project_name'] = $this->project_name;
        $data['title'] = $this->project_name.' | Login';
        $data['content_view'] = 'views/pages/login.php';
        
        $this->load->view('main', $data);
    }

    //This is the process for login
    public function loginProcess() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        if(empty($username) || empty($password)) {
            $this->session->set_flashdata('error_login','Username or Password must not empty');
            redirect('login');
        } else {
            $user = $this->user->getUser($username);
            if($user) {

                if($this->bcrypt->check_password($password, $user->password)) {
                    $userdata = [
                        'status'    => 'Online',
                        'id'        => $user->id,
                        'username'  => $user->username,
                        'name'      =>  $user->name
                    ];

                    $this->session->set_userdata($userdata);
                    redirect('/');

                } else {
                    $this->session->set_flashdata('error_login', 'Incorrect username or password');
                    redirect('login');
                }

            }else {
            $this->session->set_flashdata('error_login', 'Incorrect username or password');
            redirect('login');
            }
        }
    }






    //This method is for generate admin and encryption of password

    // public function generateAdmin() {
    //     $data = [
    //         'username' => 'admin',
    //         'password' => $this->bcrypt->hash_password('admin'),
    //         'name'     => 'Admin',
    //         ]; 
    //     $this->user->addUser($data);
    // }
}