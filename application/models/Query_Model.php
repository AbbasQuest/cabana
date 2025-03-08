<?php

class Query_Model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }

    public function add($data){
        $query = $this->db->insert('queries', $data);
        return $query;
    }

    public function get_all(){
        $query = $this->db->get('queries');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }
}

?>