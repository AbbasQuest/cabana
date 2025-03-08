<?php

class Order extends CI_Controller{

    public function __construct() {
        parent::__construct();
        $this->load->model('Orders_Model');
    }
    
    public function add(){

        try {
            $postData = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('customer', 'Customer', 'required');
            $this->form_validation->set_rules('booking', 'Booking', 'required');
            $this->form_validation->set_rules('amount', 'Amount', 'required');
 
            if($this->form_validation->run() === FALSE){
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            }

            $result = $this->Orders_Model->add($postData);
    
            return $this->api_response(200, 'true', 'order created successfully', $result);

         } catch (Exception $e) {
             // Handle the exception here
                return $this->api_response(500, 'false', $e->getMessage(), null);
         }
    }

    public function get_all(){
        try{
            $result = $this->Orders_Model->get_all();

            if(!$result){
                return $this->api_response(400, 'false', 'No orders found', null);
            }

            return $this->api_response(200, 'true', "Orders fetched successfully", $result);
        } catch (Exception $e) {
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    public function get(){
        try{
            $where = $this->input->get();

            $result = $this->Orders_Model->get($where);

            if(!$result){
                return $this->api_response(400, 'false', 'Order not found', null);
            }

            return $this->api_response(200, 'true', "Order fetched successfully", $result);

        } catch (Exception $e) {
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    public function update(){
        try{
            $postData = json_decode(file_get_contents("php://input"), true);

            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('customer', 'Customer', 'required');
            $this->form_validation->set_rules('booking', 'Booking', 'required');
            $this->form_validation->set_rules('amount', 'Amount', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');

            if($this->form_validation->run() === FALSE){
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            }

            $where['id'] = $postData['id'];
            $data['customer'] = $postData['customer'];
            $data['booking'] = $postData['booking'];
            $data['amount'] = $postData['amount'];
            $data['status'] = $postData['status'];

            $result = $this->Orders_Model->update($where, $data);

            if(!$result){
                return $this->api_response(400, 'false', "Could not update the order", $result);
            }
 
            return $this->api_response(200, 'true', "Order updated successfully", $result);

        }catch(Exception $e){
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    public function delete(){
        try{
            $order_id = $this->input->get();
            $order = $this->Orders_Model->get($order_id);
            if(!$order){
                return $this->api_response(400, 'false', "Could not find the order", null);
            }
            $result = $this->Orders_Model->delete($order_id);
            return $this->api_response(200, 'true', "Order deleted successfully", $result);
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