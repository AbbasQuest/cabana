<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'libraries/stripe-php/init.php');

class Booking extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model('Booking_Model');
        $this->load->model("Slot_Model");
        $this->load->model("Order_Items_Model");
        $this->load->model("Orders_Model");
        $this->load->model("User_Model");
        $this->load->model("Pricing_Model");
        $this->load->model("Menu_Model");

        
        $this->load->model('Payment_Model');
        $this->load->config('stripe');

        header("Access-Control-Allow-Origin: *"); 
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
    }

    public function get(){
        try{
            $where = $this->input->get();

            $result = $this->Booking_Model->get($where);
            if(!$result){
                return $this->api_response(400, 'false', 'Bookings not found', null);
            }
            $pricings = $this->Pricing_Model->get($where);
            $slots = json_decode($pricings[0]['slots']);

                     
            if (is_string($slots)) {
                $slots = json_decode($slots);
            }
            foreach($result as $res){
                foreach($slots as $slot){
                    if($slot->id === $res->slot){
                        $res->slot = $slot;
                    }
                }
            }

            return $this->api_response(200, 'true', "Bookings fetched successfully", $result);

        }catch(Exception $e){
              // Handle the exception here
              return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    public function get_all(){
        try{

            $result = $this->Booking_Model->get_all();
            

            if(!$result){
                return $this->api_response(400, 'false', 'Bookings not found', null);
            }
            $result = json_decode(json_encode($result));
            usort($result, function ($a, $b) {
                return strtotime($b->date) - strtotime($a->date);
            });

            return $this->api_response(200, 'true', "Bookings fetched successfully", $result);

        }catch(Exception $e){
              // Handle the exception here
              return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    public function add(){
        try {
            $postData = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('slot', "Slot", 'required');
            $this->form_validation->set_rules('passengers', 'Passenger', 'required');
            $this->form_validation->set_rules('date', 'Date', 'required');
            $this->form_validation->set_rules('stripe_payment_intent_id', 'Stripe Intent ID', 'required');

            if($this->form_validation->run() === FALSE){
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            }

            // lets check if any slots are available

            $where_booking['date'] = $postData['date'];
            $pricing = $this->Pricing_Model->get($where_booking);
            $slots = json_decode($pricing[0]['slots']);

            $thisSlot = array();
                   
            if (is_string($slots)) {
                $slots = json_decode($slots);
            }
            foreach($slots as $slot){
                if($postData['slot'] == $slot->id){
                    $thisSlot = $slot;
                }

            }

            $where_booking['slot'] = $postData["slot"];
            $where_booking['date'] = $postData['date'];

            $bookings = $this->Booking_Model->get($where_booking);

            // ==========================================


            if($bookings && count($bookings) >= $thisSlot->boats){
                // Handle the exception here
                return $this->api_response(404, 'false', 'No Slots available for this slot', null);
            }



            // fetch menu==============================



            // ========================================

            $boats = $postData['passengers'] / 10;
            // if($boats > 1){
                $boats = ceil($boats);
            // }
            $number_of_boats = $boats;

            $booking_id =  'BKCB' . strtoupper(uniqid());

            $result = $this->Booking_Model->add(array("booking_id"=>$booking_id, "slot"=>$postData['slot'], "customer" => $postData['customer'], "date" => $postData['date'], "boats" => $number_of_boats, "passengers" => $postData['passengers'], "discount" => $postData['discount'], "total" => $postData['total'], "pre_setup" => $postData['pre_setup'],"stripe_payment_intent_id" => $postData['stripe_payment_intent_id']));

                if (!$result) {
                    return $this->api_response(400, 'false', 'Booking could not be completed', null);
                }  

                //  create an order=====
                
                if(count($postData['order_items']) > 0){

                    $amount = 0;

                    foreach($postData['order_items'] as $items){
                        $amount = $amount + ($items['quantity'] * $items['price']);
                    }

                    $order = $this->create_order(array("booking"=> $result, "customer" => $postData['customer'], "amount" => $amount));

                    if (!$order) {
                        return $this->api_response(400, 'false', 'Order could not be completed', null);
                    } 
                    $array = array();
                    foreach($postData['order_items'] as $items){
                        $obj = $items;

                        $obj['order_id'] = $order;
                        array_push($array, $obj);
                    }
                    $orderItems = $this->create_order_items($array);

                    if (!$orderItems) {
                        return $this->api_response(400, 'false', 'Order Items could not be completed', null);
                    } 
    
                }

                return $this->api_response(200, 'true', 'Booking created successfully', $result);

            } catch (Exception $e) {
                // Handle the exception here
                return $this->api_response(500, 'false', $e->getMessage(), null);
            }
    }

    public function getBookingSlotsDetails(){
        try{
            $postData = json_decode(file_get_contents("php://input"), true);
            $where = $postData;
            // print_r($where);
            $bookings = $this->Booking_Model->get_booking_details($where);

            if(!$bookings){
                return $this->api_response(400, 'false', "Booking not found", null);
            }

            $wherePricing['date'] = $bookings[0]->date;
            $pricing = $this->Pricing_Model->get($wherePricing);
            // print_r($pricing);
            $slots = json_decode($pricing[0]['slots']);
            
            if (is_string($slots)) {
                $slots = json_decode($slots);
            }
            // print_r($slots);
            $book_id= (int)$bookings[0]->slot;
            foreach($slots as $slot){
                if($book_id === $slot->id){
                    $bookings[0]->slot = $slot;
                }
            }
            $ordersDetails = $this->Orders_Model->getBookingOrderDetails(array("booking_id"=> $bookings[0]->id));

            if($ordersDetails){
                $bookings[0]->order = $ordersDetails;
            }

            // print_r($bookings); die();

            // $result['booking_count'] = Count($bookings);
            // $passengersCount = 0;
            // $boatsBooked = 0;
            // foreach($bookings as $booking){
            //     $passengersCount = $passengersCount + $booking->passengers;
            //     $boatsBooked = $boatsBooked + $booking->boats;
            // }
            // $boatsAvailable = 0;
            // foreach($slots as $slot){
            //     $boatsAvailable = $boatsAvailable + $slot->boats;
            // }
            // $result['boatsBooked'] = $boatsBooked;
            // $result['boatsAvailable'] = $boatsAvailable;
            // $result['passengersCount'] = $passengersCount;
            // $result['bookingRatio'] = ($boatsBooked/$boatsAvailable)*100;



            
            return $this->api_response(200, 'true', "Successfull", $bookings[0]);    
        }catch(Exception $e){
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    public function checkSlots(){
        try{
            $postData = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('date', 'Date', 'required');

            if($this->form_validation->run() === FALSE){
                return $this->api_response(200, 'false', $this->form_validation->error_array(), null);
            }
            $slots = $this->Pricing_Model->get($postData);

            // print_r(json_decode($slots[0]['slots']));die();
            // if(!$slots){
            //     return $this->api_response(400, 'false', 'No slots available', null);
            // }

            if(!$slots || !$slots[0]['slots']){
                return $this->api_response(400, 'false', 'No slots available', null);
            }
            
            $bookings = $this->Booking_Model->get_bookings($postData);
            $slotArray = array();
            $thisSlots = json_decode($slots[0]['slots']);
            if (is_string($thisSlots)) {
                $thisSlots = json_decode($thisSlots);
            }
            
            foreach($thisSlots as $slot){
                $slotObject['id'] = $slot->id;
                $slotObject['name'] = $slot->name;
                $slotObject['start'] = $slot->start;
                $slotObject['end'] = $slot->end;
                $slotObject['duration'] = $slot->duration;
                $slotObject['boats'] = $slot->boats;
                $slotObject['count'] = 0;
                if($bookings){
                    foreach($bookings as $booking){
                        if($slot->id == $booking->slot){
                            $slotObject['count'] = $slotObject['count'] + $booking->boats;
                        }
                    }
                }

                array_push($slotArray, $slotObject);
            }
            $slots[0]['slots'] = json_encode($slotArray);

            return $this->api_response(200, 'true', "Bookings fetched successfully", $slots[0]);

        }catch(Exception $e){
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    public function checkPricing(){
        try{

            $postData = json_decode(file_get_contents("php://input"), true);
            $num_of_passenger = $postData['passengers'];
            $where['date'] = $postData['date'];
            $pricing = $this->Pricing_Model->get($where);
            if(!$pricing){
                return $this->api_response(400, 'true', "No pricings available for this date", null);
            }
            if($num_of_passenger <= 10){

                $date = new DateTime($postData['date']);
                $day =  $date->format('l');

                $data = array();

                if($day == "Saturday" || $day == "Sunday"){
                    $data['price'] = $pricing[0]['base_price_weekend'];
                    $data['discount'] = 0;
                    return $this->api_response(200, 'true', "Price fetched successfully", $data);
                } else {
                    $data['price'] = $pricing[0]['base_price_weekday'];
                    $data['discount'] = 0;
                    return $this->api_response(200, 'true', "Price fetched successfully", $data);
                }
            }else{
                
                $data = array();
                if($pricing[0]['discount_per_passenger'] > 0){
                    $extra_passengers = $num_of_passenger - 10;
                    $total_discount = $pricing[0]['discount_per_passenger'] * $extra_passengers;
                    
                    $data['price'] = $pricing[0]['price_per_person'] * $num_of_passenger;
                    $data['discount'] = $total_discount;

                    return $this->api_response(200, 'true', "Price fetched successfully", $data);
                }else{
                    $data['price'] = $pricing[0]['price_per_person'] * $num_of_passenger;
                    $data['discount'] = 0;
                    return $this->api_response(200, 'true', "Price fetched successfully", $data);
                }

            }

            // print_r($this->input->get());

        }catch(Exception $e){
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    public function getPrice(){
        try{
            $where = $this->input->get();
            $pricing = $this->Pricing_Model->get($where);

            if(!$pricing){
                return $this->api_response(400, 'false', "No Slots available for this date", null);
            }

            return $this->api_response(200, 'true', "Pricing fetched successfully", $pricing);
            // print_r($this->input->get());

        }catch(Exception $e){
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }
    public function getOrderDetails(){
        try{
            // print_r($this->input->get()); die();
            $where = $this->input->get();

            $result = $this->Orders_Model->getOrderDetails($where);

            

            if(!$result){
                return $this->api_response(400, 'false', 'Order details not found', null);
            }

            return $this->api_response(200, 'true', "Order details fetched successfully", $result);

        }catch(Exception $e){
              // Handle the exception here
              return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }
    public function getBookingsData(){
        try{
            $postData = json_decode(file_get_contents("php://input"), true);
            $where['date'] = $postData['date'];
            // print_r($where);
            $bookings = $this->Booking_Model->get_bookings($where);
            if(!$bookings){
                return $this->api_response(400, 'false', "Booking not found", null);
            }
            $pricing = $this->Pricing_Model->get($where);

            $slots = json_decode($pricing[0]['slots']);
                         
            if (is_string($slots)) {
                $slots = json_decode($slots);
            }

            $result['booking_count'] = Count($bookings);

            $passengersCount = 0;
            $boatsBooked = 0;
            foreach($bookings as $booking){
                $passengersCount = $passengersCount + $booking->passengers;
                $boatsBooked = $boatsBooked + $booking->boats;
            }
            $boatsAvailable = 0;
            foreach($slots as $slot){
                $boatsAvailable = $boatsAvailable + $slot->boats;
            }
            $result['boatsBooked'] = $boatsBooked;
            $result['boatsAvailable'] = $boatsAvailable;
            $result['passengersCount'] = $passengersCount;
            $result['bookingRatio'] = ($boatsBooked/$boatsAvailable)*100;



            
            return $this->api_response(200, 'true', "Successfull", $result);    
        }catch(Exception $e){
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    // checkin and checkout api

    public function checkin(){
        try{

            $postData = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('id', "Booking ID", 'required');
            $where['id'] = $postData['id'];
            $booking = $this->Booking_Model->get_bookings($where);
            if(!$booking){
                return $this->api_response(400, 'false', "Booking not found", null);
            }
            if($booking[0]->checkin){
                return $this->api_response(400, 'false', "Checkin already created", $booking[0]);
            }
            $data['checkin'] = time();
            $result = $this->Booking_Model->update($where, $data);
            if(!$result){
                return $this->api_response(400, 'false', "Checkin cannot be created", $result);
            }



            return $this->api_response(200, 'true', "Check in time stored successfully", $data);

        }catch(Exception $e){
              // Handle the exception here
              return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    public function checkout(){
        try{

            $postData = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('id', "Booking ID", 'required');
            $where['id'] = $postData['id'];
            $booking = $this->Booking_Model->get_bookings($where);
            if(!$booking){
                return $this->api_response(400, 'false', "Booking not found", null);
            }
            if($booking[0]->checkout){
                return $this->api_response(400, 'false', "checkout already created", $booking[0]);
            }

            $data['checkout'] = time();
            $result = $this->Booking_Model->update($where, $data);

            if(!$result){
                return $this->api_response(400, 'false', "checkout cannot be created", $result);
            }

            $updateStatus = $this->Booking_Model->update($where, array('status' => 'closed'));

            if(!$updateStatus){
                return $this->api_response(400, 'false', "Cannot update booking status", null);
            }


            return $this->api_response(200, 'true', "Check out time stored successfully", $data);

        }catch(Exception $e){
              // Handle the exception here
              return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    // change booking status

    public function changeBookingStatus(){
        try{
            $postData = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('id', "Booking ID", 'required');
            $this->form_validation->set_rules('status', "Status", 'required');

            $where['id'] = $postData['id'];
            $booking = $this->Booking_Model->get_bookings($where);

            if(!$booking){
                return $this->api_response(400, 'false', "Booking not found", null);
            }
            if($postData['status'] == "closed" && $booking[0]->status == 'cancelled'){
                return $this->api_response(400, 'false', "This booking cannot be closed.", null);              
            }

            if($postData['status'] == "cancelled" && $booking[0]->status == 'closed'){
                return $this->api_response(400, 'false', "This booking cannot be cancelled.", null);              
            }

            if($postData['status'] == "open" && $booking[0]->status == 'closed'){
                return $this->api_response(400, 'false', "This booking cannot be opened.", null);              
            }

            $result = $this->Booking_Model->update($where, array('status' => $postData['status']));

            if(!$result){
                return $this->api_response(400, 'false', "Cannot update booking status", null);
            }

            if($postData['status'] == 'cancelled'){
                $refundResult = $this->refund_payment($booking[0]->stripe_payment_intent_id);
            }

            
            return $this->api_response(200, 'true', "Booking status updated successfully", null);
            

        }catch(Exception $e){
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    public function reschedule(){
        try{

            $postData = json_decode(file_get_contents("php://input"), true);
            $this->form_validation->set_data($postData);
            $this->form_validation->set_rules('id', "Booking ID", 'required');
            $this->form_validation->set_rules('slot', "Slot", 'required');
            $this->form_validation->set_rules('date', 'Date', 'required');
            $where['id'] = $postData['id'];
            $booking = $this->Booking_Model->get_bookings($where);
            if(!$booking){
                return $this->api_response(400, 'false', "Booking not found", null);
            }
            if($booking[0]->checkout){
                return $this->api_response(400, 'false', "checkout already created", $booking[0]);
            }

        // lets check if any slots are available

        $where_booking['date'] = $postData['date'];
        $pricing = $this->Pricing_Model->get($where_booking);
        $slots = json_decode($pricing[0]['slots']);

        $thisSlot = array();
               
        if (is_string($slots)) {
            $slots = json_decode($slots);
        }
        foreach($slots as $slot){
            if($postData['slot'] == $slot->id){
                $thisSlot = $slot;
            }

        }

        $where_booking['slot'] = $postData["slot"];
        $where_booking['date'] = $postData['date'];

        $bookings = $this->Booking_Model->get($where_booking);

        // ==========================================


        if($bookings && count($bookings) >= $thisSlot->boats){
            // Handle the exception here
            return $this->api_response(404, 'false', 'No Slots available for this slot', null);
        }


     
        $result = $this->Booking_Model->update(array('id' => $postData['id']), $where_booking);

            if (!$result) {
                return $this->api_response(400, 'false', 'Booking could not be rescheduled', null);
            }  


            return $this->api_response(200, 'true', 'Booking rescheduled successfully', $result);


        }catch(Exception $e){
              // Handle the exception here
              return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

      // refund payment
      private function refund_payment($payment_intent_id) {
        try {
            \Stripe\Stripe::setApiKey($this->config->item('stripe_secret'));
    
            // $json_str = file_get_contents('php://input');
            // $json_obj = json_decode($json_str, true);
    
            if (!$payment_intent_id) {
                throw new Exception("Invalid payment intent ID");
            }
    
            // Retrieve the payment intent
            $paymentIntent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
    
            // Check if the payment was successful before refunding
            if ($paymentIntent->status !== 'succeeded') {
                throw new Exception("Only successful payments can be refunded.");
            }

            // Retrieve charge ID from payment intent
            $charge_id = $paymentIntent->latest_charge ?? null;
            if (!$charge_id) {
                throw new Exception("Charge ID not found for this payment.");
            }
    
            // Process the refund
            $refund = \Stripe\Refund::create([
                'charge' => $charge_id,
            ]);
    
            if ($refund->status === 'succeeded') {
                // Update the database payment status
                $this->Payment_Model->update_payment_status($paymentIntent->id, 'refunded');
                return true;
                // echo json_encode(['message' => 'Refund successful', 'refund_id' => $refund->id]);
            } else {
                // echo json_encode(['error' => 'Refund failed.']);
                
                return false;
            }
    
        } catch (\Stripe\Exception\ApiErrorException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    // user form
    private function create_user($data){
        try{
            $result = $this->User_Mode->add($data);
            return $result;
        }catch(Exception $e){
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    private function create_order($data){
        try{
            $result = $this->Orders_Model->add($data);
            return $result;
        }catch(Exception $e){
            // Handle the exception here
            return $this->api_response(500, 'false', $e->getMessage(), null);
        }
    }

    private function create_order_items($data){
        try{
            $result = $this->Order_Items_Model->add($data);
            return $result;
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