<?php

class Pricing extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model('Booking_Model');
        $this->load->model("Slot_Model");
        $this->load->model("Pricing_Model");
    }

    public function get_last_date(){
        try{
            $last_pricing = $this->Pricing_Model->get_last();

            if(!$last_pricing){
                return $this->api_response(400, 'false', 'Last Pricing not found', null);
            }

            return $this->api_response(200, 'true', "Bookings fetched successfully", $last_pricing);
            
        }catch(Exception $e){
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
      }
    }
    
    public function add() {
        try {
            $postData = json_decode(file_get_contents("php://input"), true);


            foreach($postData as &$postSlots){  // Add '&' to modify the original array
                $postSlots['slots'] = json_encode($postSlots['slots']); // Encode properly
            }
            unset($postSlots); 

            // print_r($postData); die();
            // print_r($postData); die();
            $result = $this->Pricing_Model->insert_multiple_documents($postData);
    
            if (!$result['status']) {
                return $this->api_response(400, 'false', $result['error'], null);
            }
    
            return $this->api_response(200, 'true', "Pricing added successfully", null);
            
        } catch (Exception $e) {
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    public function get_all(){
        try{
            $pricing = $this->Pricing_Model->get_all();

            if(!$pricing){
                return $this->api_response(400, 'false', 'Pricing not found', null);
            }
            // $newArray = array();
            // foreach($pricing as $pr){
            //     if($pr->status === "f"){
            //         array_push($newArray, $pr);
            //     }
            // }
            return $this->api_response(200, 'true', "Pricings fetched successfully", $pricing);
            
        }catch(Exception $e){
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
      }
    }

    public function getPricingFromToday(){
        try{
            $pricing = $this->Pricing_Model->getPricingFromToday();

            if(!$pricing){
                return $this->api_response(400, 'false', 'Pricing not found', null);
            }

            return $this->api_response(200, 'true', "Pricings fetched successfully", $pricing);
            
        }catch(Exception $e){
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
      }
        
    }

    public function update(){
        try{
            $postData = json_decode(file_get_contents("php://input"), true);

            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('id', 'Id', 'required');
            $this->form_validation->set_rules('base_price_weekday', 'Base Price Weekday', 'required');
            $this->form_validation->set_rules('base_price_weekend', 'Base price Weekend', 'required');
            $this->form_validation->set_rules('discount_per_passenger', 'Discount Per Passenger', 'required');
            $this->form_validation->set_rules('price_per_person', 'Price Per Person', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');

            if($this->form_validation->run() === FALSE){
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            }

            $where['id'] = $postData['id'];
            $pricing = $this->Pricing_Model->get($where);

            // print_r($where);
            // print_r($pricing);

            if(!$pricing){
                return $this->api_response(400, 'false', 'Pricing not found', null);
            }
            $date1 = new DateTime($pricing[0]['date']);
            $date2 = new DateTime(date("Y-m-d"));
            $diff = $date2->diff($date1);

   
            
            if($pricing[0]['date'] == date("Y-m-d") || $diff->format('%r%a') <= 0){
                return $this->api_response(400, 'false', 'Cannot change this slot pricing.', null);
            }


            $data['base_price_weekday'] = $postData['base_price_weekday'];
            $data['base_price_weekend'] = $postData['base_price_weekend'];
            $data['discount_per_passenger'] = $postData['discount_per_passenger'];
            $data['price_per_person'] = $postData['price_per_person'];
            $data['status'] = $postData['status'];
            $data['slots'] = $postData['slots'];

            $result = $this->Pricing_Model->update($where, $data);

            if(!$result){
                return $this->api_response(400, 'false', "Could not update the pricing", $result);
            }

            return $this->api_response(200, 'true', "Pricing updated successfully", $result);

        }catch(Exception $e){
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