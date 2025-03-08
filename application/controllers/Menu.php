<?php

class Menu extends CI_Controller{

    public function __construct() {
        parent::__construct();
        $this->load->model('Menu_Model');
    }
    
    public function add(){

        try {
            // $postData = json_decode(file_get_contents("php://input"), true);
            $postData = $this->input->post();

            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('info', 'Info', 'required');
            $this->form_validation->set_rules('type', 'Type', 'required');
            $this->form_validation->set_rules('price', 'Price', 'required');
            // print_r($postData); die();


            if($this->form_validation->run() === FALSE){
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            }
            // print_r($_FILES); die();
            if (!empty($_FILES['image']['name'])) {
                $config['upload_path']   = './uploads/menu_images/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
                $config['max_size']      = 4096; // 2MB
                $config['encrypt_name']  = TRUE; // Rename file for security
    
                $this->load->library('upload', $config);

    
                if (!$this->upload->do_upload('image')) {
                    return $this->api_response(400, 'false', $this->upload->display_errors(), null);
                } else {
                    $uploadData = $this->upload->data();
                    $postData['image'] = $uploadData['file_name'];  // Save image filename in DB
                }
            } else {
                $postData['image'] = null; // No image uploaded
            }

            $result = $this->Menu_Model->add($postData);
    
            return $this->api_response(200, 'true', 'Menu created successfully', $postData);

         } catch (Exception $e) {
             // Handle the exception here
                return $this->api_response(500, 'false', $e->getMessage(), null);
         }
    }

    public function get_all_menus(){
        try{
            $result = $this->Menu_Model->get_all();

            if(!$result){
                return $this->api_response(400, 'false', 'No menus found', null);
            }

            return $this->api_response(200, 'true', "Menus fetched successfully", $result);
        } catch (Exception $e) {
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    public function get(){
        try{
            $where = $this->input->get();

            $result = $this->Menu_Model->get($where);

            if(!$result){
                return $this->api_response(400, 'false', 'Menu not found', null);
            }

            return $this->api_response(200, 'true', "Menu fetched successfully", $result);

        } catch (Exception $e) {
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    public function update(){
        try{
            $postData = $this->input->post();

            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('name',  'Name', 'required');
            $this->form_validation->set_rules('info', 'Info', 'required');
            $this->form_validation->set_rules('type', 'Type', 'required');
            $this->form_validation->set_rules('price', 'Price', 'required');

            if($this->form_validation->run() === FALSE){
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            }

            $where['id'] = $postData['id'];
            $data['name'] = $postData['name'];
            $data['info'] = $postData['info'];
            $data['type'] = $postData['type'];
            $data['price'] = $postData['price'];

            if (!empty($_FILES['image']['name'])) {
                $config['upload_path']   = './uploads/menu_images/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size']      = 2048; // 2MB
                $config['encrypt_name']  = TRUE; // Rename file for security
    
                $this->load->library('upload', $config);
    
                if (!$this->upload->do_upload('image')) {
                    return $this->api_response(400, 'false', $this->upload->display_errors(), null);
                } else {
                    $uploadData = $this->upload->data();
                    $postData['image'] = $uploadData['file_name'];  // Save image filename in DB

                $data['image'] = $postData['image'];
            }
            } else {
                $postData['image'] = null; // No image uploaded
            }

            $result = $this->Menu_Model->update($where, $data);

            if(!$result){
                return $this->api_response(400, 'false', "Could not update the menu", $result);
            }
 
            return $this->api_response(200, 'true', "Menu updated successfully", $result);

        }catch(Exception $e){
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    public function delete(){
        try{
            $menu_id = $this->input->get();
            $menu = $this->Menu_Model->get($menu_id);
            if(!$menu){
                return $this->api_response(400, 'false', "Could not find the menu", null);
            }
            $result = $this->Menu_Model->delete($menu_id);
            return $this->api_response(200, 'true', "Menu deleted successfully", $result);
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