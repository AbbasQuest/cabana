<?php

class Admin_Model extends CI_Model{
    public function __construct(){
        parent::__construct();
    }


    public function get($where){
        $this->db->where($where);
        $query = $this->db->get('admin');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function update($where, $data){
        $this->db->where($where);
        $query = $this->db->update('admin', $data);
        return $query;
    }
}

?>