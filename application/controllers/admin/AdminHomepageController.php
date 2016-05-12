<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AdminHomepageController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->session_data = $this->session->userdata('logged_in');
        //$data['firstname_admin_account'] = $session_data['ADMIN_LOGIN_FIRSTNAME'];
    }
    public function index() {
        if ( $this->session->has_userdata('logged_in') && $this->session->userdata('logged_in')) {
            $data['pagetitle'] = 'Admin-hompage';
            $data['username_admin_account']  = $this->session_data['ADMIN_USERNAME'];
            $this->load->view('admin/header/head', $data);
            $this->load->view('admin/header/header-bar');
            $this->load->view('admin/header/menu-bar');
            $this->load->view('admin/contents/homepage');
            $this->load->view('admin/footer/footer');
        } else {
            redirect(base_url().'admin');
        }
    }
}
