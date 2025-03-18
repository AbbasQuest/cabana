<?php
class Pricing_Model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }


    public function insert_multiple_documents($data) {
        $this->db->trans_begin(); // Start transaction
    
        foreach ($data as $row) {
            // Check if the date already exists
            $existing = $this->db->get_where('pricing', ['date' => $row['date']])->row();
    
            if ($existing) {
                // If date exists, rollback and return duplicate error
                $this->db->trans_rollback();
                return [
                    'status' => false,
                    'error' => "Duplicate entry for date: " . $row['date']
                ];
            }
    
            // Insert new row
            if (!$this->db->insert('pricing', $row)) {
                $this->db->trans_rollback(); // Rollback if any insert fails
                return [
                    'status' => false,
                    'error' => 'Database insert failed'
                ];
            }
        }
    
        $this->db->trans_commit(); // Commit transaction if everything is successful
    
        return ['status' => true]; // Success response
    }

    public function get_last(){
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get("pricing");
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }

    }
    
    public function get_all(){
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get("pricing");
        if($query->num_rows() > 0){
            return $query->result();
        }else{  
            return FALSE;
        }
    }

    public function get($where){
        $this->db->where($where);
        $query = $this->db->get('pricing');
        if($query->num_rows() > 0){
            return $query->result_array();
        }else{
            return FALSE;
        }
    }

    public function getPricingFromToday(){
        $this->db->select('*')
         ->from('pricing')
         ->where('date >=', date('Y-m-d'))
         ->order_by('date', 'ASC');

        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        }else{
            return FALSE;
        }

    }

    public function update($where, $data){
        $this->db->where($where);
        $query = $this->db->update('pricing', $data);
        return $query;
    }

    

}
?>