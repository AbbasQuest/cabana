<?php

class Booking_Model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }

    public function add($data){
        $query = $this->db->insert('bookings', $data);
        if(!$query){
            return $query;
        }else{
            $last_id = $this->db->insert_id();
            return $last_id;
        }
    }

    public function get($where){
        $sql = 'SELECT bookings.*, bookings.id AS book_id, "user".id AS cust_id, 
        "user".first_name, "user".last_name 
        FROM bookings 
        INNER JOIN "user" ON "user".id = bookings.customer 
        WHERE bookings.date = ? 
        ORDER BY book_id DESC';

        $query = $this->db->query($sql, [$where['date']]);

        if ($query->num_rows() > 0) {  // For PostgreSQL, check `rowCount()`
        return $query->result();
        } else {
        return FALSE;
        }
    }

    public function get_all(){
        $sql = 'SELECT bookings.*, bookings.id AS book_id, "user".id AS cust_id, "user".country, "user".first_name, "user".last_name 
                FROM bookings 
                INNER JOIN "user" ON "user".id = bookings.customer
                WHERE bookings.date >= CURRENT_DATE';
    
        $query = $this->db->query($sql);
        if ($query && $query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }
    
    
    // public function get_all(){
    //     $sql = 'SELECT bookings.*, bookings.id as book_id, "user".id as cust_id, "user".country, "user".first_name, "user".last_name FROM bookings 
    //     INNER JOIN "user" ON "user".id = bookings.customer
    //     ORDER BY book_id DESC';

    //     $query = $this->db->query($sql);
    //     if($query->num_rows() > 0){
    //         return $query->result_array();
    //     }else{
    //         return FALSE;
    //     }
    // }

    public function get_bookings($where){
        $this->db->where($where);
        $query = $this->db->get('bookings');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    public function get_booking_details($where){
        $this->db->select('bookings.*, bookings.id AS book_id,  user.first_name, user.last_name, user.email, user.phone');
        $this->db->from('bookings');
        $this->db->join('user', 'bookings.customer = user.id', 'left'); // Use 'inner' if user is always required
        $this->db->where(array("bookings.id" => $where['id']));
        
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    
    }

    public function getBookingPassengerCount($where) {
        $sql = "SELECT SUM(passengers) AS passenger_count FROM bookings WHERE date = ?";
        $query = $this->db->query($sql, [$where['date']]);
        
        if ($query->num_rows() > 0) {
            return $query->row()->passenger_count; // Return the count directly
        } else {
            return 0; // Return 0 instead of FALSE for better handling
        }
    }

    public function update($where, $data){
        $this->db->where($where);
        $query = $this->db->update('bookings', $data);
        return $query;
    }




}

?>