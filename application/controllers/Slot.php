<?php

class Slot extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Slot_Model');
    }

    public function add(){
        try {
            $postData = json_decode(file_get_contents("php://input"), true);


            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('start', 'Start', 'required');
            $this->form_validation->set_rules('end', 'End', 'required');
            $this->form_validation->set_rules('duration', 'Duration', 'required');
            $this->form_validation->set_rules('boats', 'Boats', 'required');
     
            if($this->form_validation->run() === FALSE){
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            }
    
    
                $result = $this->Slot_Model->add($postData);
    
                return $this->api_response(200, 'true', 'User created successfully', $result);
    
            } catch (Exception $e) {
                 // Handle the exception here
                return $this->api_response(500, 'false', $e->getMessage(), null);
            }
    }

    public function get_all(){
        try{
            $result = $this->Slot_Model->get_all();

            if(!$result){
                return $this->api_response(400, 'false', 'No slots found', null);
            }

            return $this->api_response(200, 'true', "Slots fetched successfully", $result);

        } catch (Exception $e) {
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    public function get(){
        try{
            $where = $this->input->get();

            $result = $this->Slot_Model->get($where);

            if(!$result){
                return $this->api_response(400, 'false', 'Slot not found', null);
            }

            return $this->api_response(200, 'true', "Slot fetched successfully", $result);

        } catch (Exception $e) {
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    public function update(){
        try{
            $postData = json_decode(file_get_contents("php://input"), true);

            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('id', 'Id', 'required');
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('start', 'Start', 'required');
            $this->form_validation->set_rules('end', 'End', 'required');
            $this->form_validation->set_rules('duration', 'Duration', 'required');
            $this->form_validation->set_rules('boats', 'Boats', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required|callback_validate_boolean');

            if($this->form_validation->run() === FALSE){
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            }

            $where['id'] = $postData['id'];
            $data['name'] = $postData['name'];
            $data['start'] = $postData['start'];
            $data['end'] = $postData['end'];
            $data['duration'] = $postData['duration'];
            $data['boats'] = $postData['boats'];
            $data['status'] = $postData['status'];

            $result = $this->Slot_Model->update($where, $data);

            if(!$result){
                return $this->api_response(400, 'false', "Could not update the slot", $result);
            }

            return $this->api_response(200, 'true', "Slot updated successfully", $result);

        }catch(Exception $e){
            // Handle the exception here
                return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    public function delete(){
        try{
            $slot_id = $this->input->get();
            $slot = $this->Slot_Model->get($slot_id);
            if(!$slot){
                return $this->api_response(400, 'false', "Could not find the slot", null);
            }
            $result = $this->Slot_Model->delete($slot_id);
            return $this->api_response(200, 'true', "Slot deleted successfully", $result);
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

    public function validate_boolean($value) {
        if ($value === '1' || $value === '0' || $value === 1 || $value === 0) {
            return true;
        }
    
        $this->form_validation->set_message('validate_boolean', 'The {field} field must be either 1 or 0.');
        return false;
    }
}

?>