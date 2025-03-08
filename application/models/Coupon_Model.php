<?php
class Coupon_Model extends CI_Model {

    // 🟢 Fetch All Coupons
    public function get_coupons() {
        $query = $this->db->get('coupons');
        if($query->num_rows() > 0){
            return $query->result_array();
        }else{
            return FALSE;
        }
    }

    // 🟢 Fetch Single Coupon by ID
    public function get_coupon($where) {
        $this->db->where($where);
        $query = $this->db->get('coupons');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }

    // 🟢 Create Coupon
    public function create_coupon($data) {
        return $this->db->insert('coupons', $data);
    }


    // fetch coupons usage

    public function coupon_usage($data){
        $this->db->where($data);
        $query = $this->db->get('coupon_usage');
        if($query->num_rows() > 0){
            return $query->result();
        }else{
            return FALSE;
        }
    }


    public function update($where, $data){
        $this->db->where($where);
        $query = $this->db->update('coupons', $data);
        return $query;
    }

    
    public function delete($where){
        $this->db->where('id', $where['id']);
        $query = $this->db->delete('coupons');
        return $query;

    }

    // create coupon usage

    public function create_coupon_usage($data){
        return $this->db->insert('coupon_usage', $data);
    }

    // 🟢 Update Coupon
    public function update_coupon($id, $data) {
            $this->db->where($id);
            $query = $this->db->update('coupons', $data);
            return $query;
    }

    // 🟢 Delete Coupon
    public function delete_coupon($id) {
        return $this->db->where('id', $id)->delete('coupons');
    }

    // 🟢 Fetch Coupon Usage
    public function get_coupon_usage($coupon_id) {
        return $this->db->where('coupon_id', $coupon_id)->get('coupon_usage')->result_array();
    }

    // 🟢 Apply Coupon
    public function apply_coupon($code, $user_id) {
        // Fetch Coupon
        $coupon = $this->db->where('code', $code)->get('coupons')->row_array();

        if (!$coupon) {
            return ['status' => 'error', 'message' => 'Invalid or expired coupon'];
        }

        // Check if User has Already Used Coupon
        $usage = $this->db->where(['coupon_id' => $coupon['id'], 'user_id' => $user_id])->get('coupon_usage')->row_array();

        if ($usage) {
            if ($usage['usage_count'] >= $coupon['usage_limit']) {
                return ['status' => 'error', 'message' => 'You have already used this coupon the maximum allowed times'];
            }

            // Update Usage Count
            $this->db->set('usage_count', 'usage_count + 1', FALSE);
            $this->db->set('last_used', 'NOW()', FALSE);
            $this->db->where(['coupon_id' => $coupon['id'], 'user_id' => $user_id]);
            $this->db->update('coupon_usage');
        } else {
            // Insert New Usage Record
            $this->db->insert('coupon_usage', [
                'coupon_id' => $coupon['id'],
                'user_id' => $user_id,
                'usage_count' => 1
            ]);
        }

        return ['status' => 'success', 'message' => 'Coupon applied successfully', 'discount' => $coupon['discount_value']];
    }
}
