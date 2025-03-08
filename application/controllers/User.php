<?php

class User extends CI_Controller{

    public function __construct() {
        parent::__construct();
        $this->load->model('User_Model');
    }


    public function checkUser(){
        
        try{
            $postData = json_decode(file_get_contents("php://input"), true);
    
            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    
            if ($this->form_validation->run() == FALSE) {
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            }
            $data = $postData;
    
            $result = $this->User_Model->get($data);

            if($result){
                return $this->api_response(200, 'true', 'User fetched successfully', $result[0]);
            }
    
            return $this->api_response(404, 'false', 'User not found', null);
        
        } catch (Exception $e) {
             // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
         
    
    }

    public function add(){


        try{
        $postData = json_decode(file_get_contents("php://input"), true);

        $this->form_validation->set_data($postData);
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('country', 'country', 'required');

        if ($this->form_validation->run() == FALSE) {
            $message = 'details not valid';
            $this->output->set_content_type('application/json');
            return $this->output->set_output(json_encode($message));
        }
        $data = $postData;

        $result = $this->User_Model->add($data);

        return $this->api_response(200, 'true', 'Slot created successfully', $result);
    
    } catch (Exception $e) {
         // Handle the exception here
        return $this->api_response(500, 'false', $e->getMessage(), null);
    }
}

    public function update(){

        try{
        $postData = json_decode(file_get_contents("php://input"), true);

        $this->form_validation->set_data($postData);
        $this->form_validation->set_rules('id', 'ID', 'required');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('country', 'country', 'required');

        if ($this->form_validation->run() == FALSE) {
            $message = 'details not valid';
            $this->output->set_content_type('application/json');
            return $this->output->set_output(json_encode($message));
        }
        $where['id'] = $postData['id'];
        $data['first_name'] = $postData['first_name'];
        $data['last_name'] = $postData['last_name'];
        $data['phone'] = $postData['phone'];
        $data['country'] = $postData['country'];

        $result = $this->User_Model->update($where, $data);

        return $this->api_response(200, 'true', 'User Updated successfully', $result);
    
    } catch (Exception $e) {
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