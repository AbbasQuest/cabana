<?php

class User_Model extends CI_Model{
    
    public function __construct() {
        parent::__construct();
    }

    public function add($data){
        $query = $this->db->insert('user', $data);
        if(!$query){
            return $query;
        }else{
            $last_id = $this->db->insert_id();
            return $last_id;
        }
    }

    public function get($where){
        $this->db->where($where);
        $query = $this->db->get('user');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function update($where, $data){
        $this->db->where($where);
        $query = $this->db->update('user', $data);
        return $query;
    }
}

?>