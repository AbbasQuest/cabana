<?php

class Menu_Model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }

    public function add($data){
        $query = $this->db->insert('menu', $data);
        return $query;
    }

    public function get($where){
        $this->db->where($where);
        $query = $this->db->get('menu');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function get_all(){
        $query = $this->db->get('menu');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function update($where, $data){
        $this->db->where($where);
        $query = $this->db->update('menu', $data);
        return $query;
    }

    public function delete($where){
        $this->db->where('id', $where['id']);
        $query = $this->db->delete('menu');
        return $query;

    }
}

?>