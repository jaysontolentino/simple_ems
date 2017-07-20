<?php

    //User Model
    class User extends CI_Model {

        public function __construct() {
            parent::__construct();
        }

        //Add a user
        public function addUser($data) {
            $this->db->insert('ems_users', $data);
        }

        //get a user
        public function getUser($username) {
            return $this->db->get_where('ems_users',['username' => $username])->row();
        }
    }