<?php

class Orders_Model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }

    public function add($data){
        $query = $this->db->insert('orders', $data);
        if(!$query){
            return $query;
        }else{
            $last_id = $this->db->insert_id();
            return $last_id;
        }
    }

    public function get($where){
        $this->db->where($where);
        $query = $this->db->get('orders');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function getBookingOrderDetails($where){
        $sql = "SELECT orders.id as order_id,
                       order_items.id as order_item_id, order_items.quantity, order_items.price, 
                       menu.name as menu_name,
                       menu.image as menu_image,
                       menu.type as menu_category
                FROM orders
                INNER JOIN order_items ON orders.id = order_items.order_id 
                INNER JOIN menu ON order_items.menu_id = menu.id
                WHERE orders.booking = ?";
    
        $query = $this->db->query($sql, [$where['booking_id']]);
    
        if($query->num_rows() > 0){
            return $query->result();
        } else {
            return FALSE;
        }
    }

    public function getOrderDetails($where){
        $sql = "SELECT orders.id as order_id,
                       order_items.id as order_item_id, order_items.quantity, order_items.price, 
                       menu.name as menu_name,
                       menu.image as menu_image,
                       menu.type as menu_category
                FROM orders
                INNER JOIN order_items ON orders.id = order_items.order_id 
                INNER JOIN menu ON order_items.menu_id = menu.id
                WHERE orders.id = ?";
    
        $query = $this->db->query($sql, [$where['id']]);
    
        if($query->num_rows() > 0){
            return $query->result();
        } else {
            return FALSE;
        }
    }
    
    public function get_all(){
        $query = $this->db->get('orders');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function update($where, $data){
        $this->db->where($where);
        $query = $this->db->update('orders', $data);
        return $query;
    }

    public function delete($where){
        $this->db->where('id', $where['id']);
        $query = $this->db->delete('orders');
        return $query;

    }
}

?>