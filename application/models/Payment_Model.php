<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Save payment intent before confirmation
    public function create_payment($data) {
        return $this->db->insert('payments', $data);
    }

    // Update payment after success or failure
    public function update_payment_status($intent_id, $status, $receipt_url = null, $payment_method = null) {
        $this->db->where('stripe_payment_intent_id', $intent_id);
        return $this->db->update('payments', [
            'payment_status' => $status,
            'stripe_receipt_url' => $receipt_url,
            'stripe_payment_method' => $payment_method,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    // Retrieve a payment by intent ID
    public function get_payment_by_intent($intent_id) {
        $this->db->where('stripe_payment_intent_id', $intent_id);
        return $this->db->get('payments')->row_array();
    }
}
