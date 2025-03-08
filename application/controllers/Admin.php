<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model("Admin_Model");
        $this->load->helper(array('url', 'string'));
        $this->load->library(array('email', 'form_validation'));
    }

    public function login(){
        try{
            $postData = json_decode(file_get_contents("php://input"), true);

            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('email', "Email", 'required|valid_email');
            $this->form_validation->set_rules('password', "Password", 'required');

            if($this->form_validation->run() === FALSE){
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            }

            $data['email'] = $postData['email'];

            $data['password'] = md5($postData['password']);

            $admin = $this->Admin_Model->get($data);

            if(!$admin){
                return $this->api_response(400, 'false', 'Invalid details', null);
            }

            $token = $this->generate_token($admin[0]->email);

            $result['token'] = $token;
            $result['email'] = $admin[0]->email;
            $result['first_name'] = $admin[0]->first_name;
            $result['last_name'] = $admin[0]->last_name;

            return $this->api_response(200, 'true', 'Logged In Successfully', $result);

        }catch (Exception $e) {
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    public function editPassword(){
        try{
            $token = $this->input->get_request_header('Authorization', TRUE);
            $result = $this->validate_token($token);
            // print_r($result);die();
            if(!$result){
                return $this->api_response(400, 'false', 'Unauthorised request', null);
            }
            $postData = json_decode(file_get_contents("php://input"), true);

            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('old_password',  'Old Password', 'required');
            $this->form_validation->set_rules('new_password',  'New Password', 'required');

            if($this->form_validation->run() === FALSE){
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            }
            // print_r($result['user_id']); die();
            $checkUser = $this->Admin_Model->get(array("email" => $result['user_id'], "password"=>md5($postData['old_password'])));
            if(!$checkUser){
                return $this->api_response(400, 'false', "invalid password", null);
            }
            $where['email'] = $result['user_id'];
            $data['password'] = md5($postData['new_password']);

            $result = $this->Admin_Model->update($where, $data);

            if(!$result){
                return $this->api_response(400, 'false', "Could not change the password", $result);
            }
 
            return $this->api_response(200, 'true', "Password updated successfully", $result);

        }catch (Exception $e) {
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    public function generate_otp() {
        try{
            $postData = json_decode(file_get_contents("php://input"), true);
        
            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            
            if ($this->form_validation->run() == FALSE) {
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            }
    
            $data['email'] = $postData['email'];
    
            $admin = $this->Admin_Model->get($data);
    
            if(!$admin){
                return $this->api_response(400, 'false', 'Invalid details', null);
            }
            
            $email = $postData['email'];
            $otp = random_string('numeric', 6);
            
            // Save OTP to database
            $data = ['otp' => $otp];
            $where['email'] = $email;

            $result = $this->Admin_Model->update($where, $data);

            if(!$result){
                return $this->api_response(400, 'false', "Could not set the otp", $result);
            }

            $to = $email;
            $subject = "My subject";
            $txt = "Your OTP code is: $otp\nIt will expire in 10 minutes.";
            $headers = "From: ".$email . "\r\n";

            if (mail($to,$subject,$txt,$headers)) {
                return $this->api_response(200, 'true', 'OTP sent successfully.', null);
            } else {
                return $this->api_response(400, 'false', 'Failed to send OTP.', null);
            }
        } catch (Exception $e) {
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }       
    }

    public function verify_otp(){
        try{
            $postData = json_decode(file_get_contents("php://input"), true);

            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('email', "Email", 'required|valid_email');
            $this->form_validation->set_rules('otp', "otp", 'required');

            if($this->form_validation->run() === FALSE){
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            }

            $data['email'] = $postData['email'];

            $data['otp'] = $postData['otp'];

            $admin = $this->Admin_Model->get($data);

            if(!$admin){
                return $this->api_response(400, 'false', 'Invalid details', null);
            }

            $token = $this->generate_token($admin[0]->email);

            $result['token'] = $token;
            $result['email'] = $admin[0]->email;

            return $this->api_response(200, 'true', 'OTP verification Success', $result);

        }catch (Exception $e) {
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        } 
    }


    public function set_new_password(){
        try{
            $token = $this->input->get_request_header('Authorization', TRUE);
            $result = $this->validate_token($token);
            if(!$result){
                return $this->api_response(400, 'false', 'Unauthorised request', null);
            }
            $postData = json_decode(file_get_contents("php://input"), true);

            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('password',  'Password', 'required');

            if($this->form_validation->run() === FALSE){
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            }

            $where['email'] = $result['user_id'];
            $data['password'] = md5($postData['password']);

            $result = $this->Admin_Model->update($where, $data);

            if(!$result){
                return $this->api_response(400, 'false', "Could not change the password", $result);
            }
 
            return $this->api_response(200, 'true', "Password updated successfully", $result);

        }catch (Exception $e) {
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }






    private function api_response($status, $success, $message, $data){
        $this->output->set_status_header($status);
        $this->output->set_content_type('application/json');
        return $this->output->set_output(json_encode(['success' => $success, 'message' => $message, 'data'=> $data]));
    }


    
}

?>