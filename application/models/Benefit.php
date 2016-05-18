<?php


class Benefit extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function insert($data) {
        $response['added'] = ($this->db->insert('benefits', $data)) ? true : false;
        return $response;
    }
    
    public function get_all() {
        $select = array(
            'id',
            'benefit_title',
            'benefit_detail',
            'benefit_image',
            'benefit_status'
        );
        $this->db->select($select);
        $query = $this->db->get('benefits');
        return $query->result();
    }
    
    public function single($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('benefits');
        return $query->row();
    }


    public function single_data() {
        $sql = "SELECT id, benefit_title, benefit_description, benefit_detail, benefit_image FROM benefits WHERE benefit_status = ? order by created desc limit 1"; 
        $query = $this->db->query($sql, array(1));
        return $query->result();
    }
}
