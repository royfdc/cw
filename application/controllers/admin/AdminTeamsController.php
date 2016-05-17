<?php

class AdminTeamsController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->session_data = $this->session->userdata('logged_in');
        $this->load->model('Team');
        $this->load->library('alert');
    }
    
    public function index() {
        if ( $this->session->has_userdata('logged_in') && $this->session->userdata('logged_in')) {
            $data['pagetitle'] = 'Admin-teams';
            $data['username_admin_account']  = $this->session_data['ADMIN_USERNAME'];
            $data['page_header'] = 'Admin Teams';
            
            $data['form'] = array(
                'name' => true,
                'position' => true,
                'action' => 'admin-add-team-exec'
            );
            $this->load->view('admin/header/head', $data);
            $this->load->view('admin/header/header-bar');
            $this->load->view('admin/header/menu-bar');
            $this->load->view('admin/contents/form-content');
            $this->load->view('admin/footer/footer');
        } else {
            redirect(base_url().'admin');
        }
    }
    
    public function add_exec() {  
        $config['upload_path'] = 'image/teams';
        $config['allowed_types'] = 'gif|jpg|png';
        $this->load->library('upload', $config);
        $this->form_validation->set_rules($this->validation());
        if  ($this->form_validation->run() == false) {
            $this->index();
        } else {
            $to_save = array(
                'team_name' => $this->input->post('name'),
                'team_position' => $this->input->post('position'),
                'team_description' => $this->input->post('description'),
                'team_image' => $this->input->post('image'),
                'created' => date('Y-m-d H:i:s'),
            );
            $response = $this->Team->insert($to_save);
            if (!$response['created']) {
                $this->session->set_flashdata('error', $this->alert->show('Cannot add Team', 0));
            } else {
                $this->session->set_flashdata('success', $this->alert->show('Add success', 1));
            }
            redirect(base_url().'admin/admin-add-team');
            exit();
        }
    }
    
    
    public function edit_exec() {
        
        $config['upload_path'] = 'image/teams';
        $config['allowed_types'] = 'gif|jpg|png';
        $this->load->library('upload', $config);
        $this->form_validation->set_rules($this->validation());
        if  ($this->form_validation->run() == false) {
            $this->edit($_POST['id']);
        } else {
            $to_update = array(
                'team_name' => $this->input->post('name'),
                'team_position' => $this->input->post('position'),
                'team_description' => $this->input->post('description'),
                'modified' => date('Y-m-d H:i:s')
            );
            $id = $this->input->post('id');
            if ($_POST['image'] != ''){
                $to_update['team_image'] = $_POST['image'];
            }
            $response = $this->Team->update($to_update, $id);
            if (!$response['updated']) {
                $this->session->set_flashdata('error', $this->alert->show('Cannot update team', 0));
            } else {
                $this->session->set_flashdata('success', $this->alert->show('Update success', 1));
            }
            redirect(base_url().'admin/admin-edit-team/'.$id);
            exit();
        }
        
    }
    
    private function validation() {
        $validate = array(
            array(
                'field' => 'name',
                'label' => 'Name',
                'rules' => 'required'
            ),
            array(
                'field' => 'position',
                'label' => 'Position',
                'rules' => 'required'
            ),
            array(
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'required'
            ),
            array(
                'field' => 'image',
                'label' => 'Image',
                'rules' => 'callback_handle_upload'
            )
        );
        return $validate;
    }
    
    
    function handle_upload() {
        if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
            if ($this->upload->do_upload('image')) {
                // set a $_POST value for 'image' that we can use later
                $upload_data    = $this->upload->data();
                $_POST['image'] = $upload_data['file_name'];
                return true;
            } else {
                // possibly do some clean up ... then throw an error
                $this->form_validation->set_message('handle_upload', $this->upload->display_errors());
                return false;
            }
        } else {
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                $_POST['image'] = '';
                return true;
            } else {
                // throw an error because nothing was uploaded
                $this->form_validation->set_message('handle_upload', "You must upload an image!");
                return false;
            }
        }
    }
    
    
    public function view(){
        if ( $this->session->has_userdata('logged_in') && $this->session->userdata('logged_in')) {
            $data['pagetitle'] = 'Admin-teams';
            $data['username_admin_account']  = $this->session_data['ADMIN_USERNAME'];
            $data['all_teams'] = $this->Team->get_all();
            $data['action_status_link'] = 'admin-status-team';
            $data['action_delete_link'] = 'admin-delete-team';
            $this->load->view('admin/header/head', $data);
            $this->load->view('admin/header/header-bar');
            $this->load->view('admin/header/menu-bar');
            $this->load->view('admin/contents/view-teams');
            $this->load->view('admin/modal/status-modal');
            $this->load->view('admin/modal/delete-modal');
            $this->load->view('admin/footer/footer');
        } else {
            redirect(base_url().'admin');
        }
    }
    
    public function edit($id) {
        if ( $this->session->has_userdata('logged_in') && $this->session->userdata('logged_in')) {
            $data['pagetitle'] = 'Admin-teams';
            $data['username_admin_account']  = $this->session_data['ADMIN_USERNAME'];
            $data['team'] = $this->Team->single($id);
            $this->load->view('admin/header/head', $data);
            $this->load->view('admin/header/header-bar');
            $this->load->view('admin/header/menu-bar');
            $this->load->view('admin/contents/edit-teams');
            $this->load->view('admin/footer/footer');
        } else {
            redirect(base_url().'admin');
        }
    }
    
    public function change_status(){
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $status = ($status == 0) ? 1 : 0;
        $response = $this->Team->change_status($id, $status);
        if (!$response['changed']) {
            $this->session->set_flashdata('error', $this->alert->show('Cannot change team status', 0));
        } else {
            $this->session->set_flashdata('success', $this->alert->show('Success change status.', 1));
        }
        redirect(base_url().'admin/admin-view-team');
        exit();
    }
    
    public function delete() {
        if ( $this->session->has_userdata('logged_in') && $this->session->userdata('logged_in')) {
            $id = $this->input->post('id');
            $response = $this->Team->delete($id);
            if (!$response['deleted']) {
                $this->session->set_flashdata('error', $this->alert->show('Cannot delete team', 0));
            } else {
                $this->session->set_flashdata('success', $this->alert->show('Succecss delete!', 1));
            }
            redirect(base_url().'admin/admin-view-team');
        } else {
            redirect(base_url().'admin');
        }
        exit();
    }
    
}