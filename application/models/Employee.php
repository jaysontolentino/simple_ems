<?php

    //User Model
    class Employee extends CI_Model {

        public function __construct() {
            parent::__construct();
        }

        //fetch all employees
        public function getAllEmployees($limit, $offset) {
            $this->db->limit($limit, $offset);
            return $this->db->get('ems_employee')->result();
        }

        //get employee by id
        public function getEmployee($id) {
             return $this->db->get_where('ems_employee',['id' => $id])->row();
        }

        //Add an Employee
        public function addEmployee($data) {
            $this->db->insert('ems_employee', $data);
            return true;
        }

        //get last inserted employee
        public function getLast() {
            $this->db->order_by('id', 'DESC');
            $this->db->limit(1);
            return $this->db->get('ems_employee')->row();
        }

        //update an employee
        public function updateEmployee($id, $data) {
            $this->db->where('id', $id);
            return $this->db->update('ems_employee', $data);
        }

        //delete employee
        public function delete($id) {
            $this->db->where('id', $id);
            return $this->db->delete('ems_employee');
        }
    }