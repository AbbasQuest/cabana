<?php

class Order_Items_Model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }

    public function add($data){
        $query = $this->db->insert_batch('order_items', $data);
        if(!$query){
            return $query;
        }else{
            $last_id = $this->db->insert_id();
            return $last_id;
        }
    }

    public function get($where){
        $this->db->where($where);
        $query = $this->db->get('order_items');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function get_all(){
        $query = $this->db->get('order_items');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function update($where, $data){
        $this->db->where($where);
        $query = $this->db->update('order_items', $data);
        return $query;
    }

    public function delete($where){
        $this->db->where('id', $where['id']);
        $query = $this->db->delete('order_items');
        return $query;

    }
}

?>