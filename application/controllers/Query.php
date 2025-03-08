<?php

class Query extends CI_Controller{

    public function __construct() {
        parent::__construct();
        $this->load->model('Query_Model');
    }
    
    public function add(){

        try {
            $postData = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('phone', 'Phone', 'required');
            $this->form_validation->set_rules('message', 'Message', 'required');


            if($this->form_validation->run() === FALSE){
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            }

            $result = $this->Query_Model->add($postData);
    
            return $this->api_response(200, 'true', 'Your query was submitted successfully', $result);

         } catch (Exception $e) {
             // Handle the exception here
                return $this->api_response(500, 'false', $e->getMessage(), null);
         }
    }

    public function get_all(){
        try{
            $result = $this->Query_Model->get_all();

            if(!$result){
                return $this->api_response(400, 'false', 'No queries found', null);
            }

            return $this->api_response(200, 'true', "Queries fetched successfully", $result);
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