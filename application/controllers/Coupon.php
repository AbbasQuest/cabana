<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coupon extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Coupon_Model');
        $this->load->model('Coupon_Model');
        $this->load->library('form_validation'); // Load Form Validation Library
    }

    // 🟢 Fetch All Coupons
    public function get_coupons() {
        try{
            $coupons = $this->Coupon_Model->get_coupons();
            if(!$coupons){
                return $this->api_response(400, 'false', "No Cupons found", null);
            }
            
            return $this->api_response(200, 'true', "Coupon fetched successfully", $coupons);
        }catch(Exception $e){
            return $this->api_response(500, 'false', $e->getMessage(), null);

        }
    }

    // 🟢 Fetch Single Coupon by ID
    public function get_coupon() {
        try{
            $id = $this->input->get();
            $coupon = $this->Coupon_Model->get_coupon($id);
            if(!$coupon){
                return $this->api_response(400, 'false', "No Cupons found", null);
            }
            
            return $this->api_response(200, 'true', "Coupon fetched successfully", $coupon);
        }catch(Exception $e){
            return $this->api_response(500, 'false', $e->getMessage(), null);

        }
    }

    // 🟢 Create Coupon with Validation
    public function create() {
        try{
            $postData = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('code', 'Coupon Code', 'required|is_unique[coupons.code]');
            $this->form_validation->set_rules('discount_type', 'Discount Type', 'required|in_list[fixed,percentage]');
            $this->form_validation->set_rules('discount_value', 'Discount Value', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('valid_from', 'Valid From', 'required|callback_valid_date');
            $this->form_validation->set_rules('valid_to', 'Valid To', 'required|callback_valid_date');
            $this->form_validation->set_rules('usage_limit', 'Usage Limit', 'required|integer|greater_than[0]');
            $this->form_validation->set_rules('type', 'Coupon Type', 'required|in_list[general,personal]');
    
            if ($this->form_validation->run() == FALSE) {
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            } else {
                $result = $this->Coupon_Model->create_coupon($postData);
                if ($result) {
                    return $this->api_response(201, 'true', "Coupon created successfully", null);
                } else {
                    return $this->api_response(400, 'false', "Failed to create coupon", null);
                }
            }

        }catch(Exception $e){
            return $this->api_response(500, 'false', $e->getMessage(), null);

        }
       
    }

    // Create apply coupon
    public function check(){
        try{
            $postData = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('code', 'Coupon Code', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required');
            if ($this->form_validation->run() == FALSE) {
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            }
            $coupon = $this->Coupon_Model->get_coupon(array('code'=>$postData['code']));

            if(!$coupon){
                return $this->api_response(200, 'false', "Coupon is invalid!", null);
            }

            // check validity
            $dateToday = date('Y-m-d');
            if (strtotime($coupon[0]->valid_from) > strtotime(date("Y-m-d")) || strtotime($coupon[0]->valid_to) < strtotime(date("Y-m-d"))) {
                return $this->api_response(200, 'false', "Coupon is not valid!", null);
            }

            // print_r($coupon);
            if($coupon[0]->type == 'general'){
                // check if the coupon is not used totally

                if($coupon[0]->total_used >= $coupon[0]->usage_limit){
                    return $this->api_response(200, 'false', "Coupon is used upto is maximum limit!", null);
                }

                // check if this user has already used this coupon x times;

                $couponsUsed = $this->Coupon_Model->coupon_usage(array('email'=> $postData['email'], 'coupon_id' => $coupon[0]->id));

                if($couponsUsed && Count($couponsUsed) >= $coupon[0]->limit_per_user){
                    return $this->api_response(200, 'false', "You have already used the coupon!", null);
                }

         

                return $this->api_response(200, 'true', "Successfull!", $coupon[0]);
            }else{             
                // check if the coupon is valid for this user

                if($coupon[0]->user_id !== $postData['user_id']){
                    return $this->api_response(200, 'false', "Coupon is invalid!", null);
                }

                // if($coupon[0]->total_used >= $coupon[0]->usage_limit){
                //     return $this->api_response(400, 'false', "Coupon is used upto is maximum limit!", null);
                // }

                // check if this user has already used this coupon x times;

                $couponsUsed = $this->Coupon_Model->coupon_usage(array('user_id'=> $postData['user_id'], 'coupon_id' => $coupon[0]->id));

                if($couponsUsed && Count($couponsUsed) >=1){
                    return $this->api_response(400, 'false', "You have already used the coupon!", null);
                }

                
                return $this->api_response(200, 'true', "Successfull!", null);
            }
            

        }catch(Exception $e){
            return $this->api_response(500, 'false', $e->getMessage(), null);

        }
    }

    // add coupon
    public function apply(){
        try{     
            $postData = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('code', 'Coupon Code', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required');
            if ($this->form_validation->run() == FALSE) {
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            }
            $coupon = $this->Coupon_Model->get_coupon(array('code'=>$postData['code']));

            if(!$coupon){
                return $this->api_response(200, 'false', "Coupon is invalid!", null);
            }

            $used = $coupon[0]->total_used + 1;
            $result = $this->Coupon_Model->update_coupon(array('id' => $coupon[0]->id), array('total_used'=> $used));
            if(!$result){
                return $this->api_response(200, 'false', "Cannot update Coupon", null);
            }
            
            $create_coupon_usage = $this->Coupon_Model->create_coupon_usage(array("email"=>$postData['email'], "coupon_id"=>$coupon[0]->id));

            if(!$create_coupon_usage){
                return $this->api_response(200, 'false', "Cannot add Coupon", null);
            }
        }catch(Exception $e){
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    // 🟢 Update Coupon with Validation
    public function update_coupon() {
        try{
            $postData = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('id', 'ID', 'required');
            $this->form_validation->set_rules('discount_value', 'Discount Value', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('valid_from', 'Valid From', 'required|callback_valid_date');
            $this->form_validation->set_rules('valid_to', 'Valid To', 'required|callback_valid_date');
            $this->form_validation->set_rules('usage_limit', 'Usage Limit', 'required|integer|greater_than[0]');
            $this->form_validation->set_rules('type', 'Coupon Type', 'required|in_list[general,personal]');
            $this->form_validation->set_rules('limit_per_user', 'Limit Per User', 'required');
    
            if ($this->form_validation->run() == FALSE) {
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            } else {

                $where['id'] = $postData['id'];
                $result = $this->Coupon_Model->get_coupon($where);

                if(!$result){
                    return $this->api_response(400, 'false', 'Coupon not found', null);
                }

                $data['discount_value'] = $postData['discount_value'];
                $data['valid_from'] = $postData['valid_from'];
                $data['valid_to'] = $postData['valid_to'];
                $data['usage_limit'] = $postData['usage_limit'];
                $data['type'] = $postData['type'];
                $data['limit_per_user'] = $postData['limit_per_user'];

                $result = $this->Coupon_Model->update($where, $data);

                if ($result) {
                    return $this->api_response(201, 'true', "Coupon updated successfully", null);
                } else {
                    return $this->api_response(400, 'false', "Failed to create coupon", null);
                }
            }

        }catch(Exception $e){
            return $this->api_response(500, 'false', $e->getMessage(), null);

        }
    }


    public function delete(){
        try{
            $postData = json_decode(file_get_contents("php://input"), true);

            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE) {
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            }
            $where['id'] = $postData['id'];
            $coupon = $this->Coupon_Model->get_coupon($where);
            if(!$coupon){
                return $this->api_response(400, 'false', "Could not find the coupon", null);
            }
            $result = $this->Coupon_Model->delete($where);

            if(!$result){
                return $this->api_response(400, 'false', "Could not delete the coupon", null);
            }
            return $this->api_response(200, 'true', "Coupon deleted successfully", $result);
        } catch (Exception $e) {
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }
    // 🟢 Fetch Coupon Usage
    public function get_coupon_usage($coupon_id) {
        $usage = $this->Coupon_Model->get_coupon_usage($coupon_id);
        echo json_encode(['status' => 'success', 'data' => $usage]);
    }

    // 🟢 Apply Coupon with Validation
    public function apply_coupon() {
        $this->form_validation->set_rules('code', 'Coupon Code', 'required');
        $this->form_validation->set_rules('user_id', 'User ID', 'required|integer');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
        } else {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->Coupon_Model->apply_coupon($data['code'], $data['user_id']);
            echo json_encode($result);
        }
    }

    // ✅ Custom Validation for Date Format
    public function valid_date($date) {
        if (!DateTime::createFromFormat('Y-m-d', $date)) {
            $this->form_validation->set_message('valid_date', 'The {field} field must be in YYYY-MM-DD format.');
            return FALSE;
        }
        return TRUE;
    }

    private function api_response($status, $success, $message, $data){
        $this->output->set_status_header($status);
        $this->output->set_content_type('application/json');
        return $this->output->set_output(json_encode(['success' => $success, 'message' => $message, 'data'=> $data]));
    }
}
