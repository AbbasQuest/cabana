<?php

class Order_Items extends CI_Controller{

    public function __construct() {
        parent::__construct();
        $this->load->model('Order_Items_Model');
    }
    
    public function add(){

        try {
            $postData = json_decode(file_get_contents("php://input"), true);

            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('order_id', 'Order ID', 'required');
            $this->form_validation->set_rules('menu_id', 'Menu ID', 'required');
            $this->form_validation->set_rules('quantity', 'Quantity', 'required');
            $this->form_validation->set_rules('price', 'Price', 'required');
 
            if($this->form_validation->run() === FALSE){
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            }

            $result = $this->Order_Items_Model->add($postData);
    
            return $this->api_response(200, 'true', 'Order items created successfully', $result);

         } catch (Exception $e) {
             // Handle the exception here
                return $this->api_response(500, 'false', $e->getMessage(), null);
         }
    }

    public function get_all(){
        try{
            $result = $this->Order_Items_Model->get_all();

            if(!$result){
                return $this->api_response(400, 'false', 'No order items found', null);
            }

            return $this->api_response(200, 'true', "Order items fetched successfully", $result);
        } catch (Exception $e) {
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    public function get(){
        try{
            $where = $this->input->get();

            $result = $this->Order_Items_Model->get($where);

            if(!$result){
                return $this->api_response(400, 'false', 'Order items not found', null);
            }

            return $this->api_response(200, 'true', "Order items fetched successfully", $result);

        } catch (Exception $e) {
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    public function update(){
        try{
            $postData = json_decode(file_get_contents("php://input"), true);

            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('order_id', 'Order ID', 'required');
            $this->form_validation->set_rules('menu_id', 'Menu ID', 'required');
            $this->form_validation->set_rules('quantity', 'Quantity', 'required');
            $this->form_validation->set_rules('price', 'Price', 'required');

            if($this->form_validation->run() === FALSE){
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            }

            $where['id'] = $postData['id'];
            $data['order_id'] = $postData['order_id'];
            $data['menu_id'] = $postData['menu_id'];
            $data['quantity'] = $postData['quantity'];
            $data['price'] = $postData['price'];

            $result = $this->Order_Items_Model->update($where, $data);

            if(!$result){
                return $this->api_response(400, 'false', "Could not update the order items ", $result);
            }
 
            return $this->api_response(200, 'true', "Order items updated successfully", $result);

        }catch(Exception $e){
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    public function delete(){
        try{
            $order_id = $this->input->get();
            $order = $this->Order_Items_Model->get($order_id);
            if(!$order){
                return $this->api_response(400, 'false', "Could not find the order items", null);
            }
            $result = $this->Order_Items_Model->delete($order_id);
            return $this->api_response(200, 'true', "Order items deleted successfully", $result);
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