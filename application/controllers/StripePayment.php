<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'libraries/stripe-php/init.php');

class StripePayment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Payment_Model');
        $this->load->config('stripe');

        header("Access-Control-Allow-Origin: *"); 
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
    }

    // Create a Payment Intent and Save it
    public function create_payment_intent() {
        try {
            \Stripe\Stripe::setApiKey($this->config->item('stripe_secret'));

            $json_str = file_get_contents('php://input');
            $json_obj = json_decode($json_str, true);
            // throw new Exception($json_str);
            if (!isset($json_obj['user_id']) || !isset($json_obj['amount']) || !is_numeric($json_obj['amount'])) {
                throw new Exception("Invalid user ID or amount");
            }

            $amount = $json_obj['amount'] * 100; // Convert to cents
            $user_id = $json_obj['user_id'];

            // Create Stripe Payment Intent
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'usd',
            ]);

            // Save payment to database as pending
            $result = $this->Payment_Model->create_payment([
                'user_id' => $user_id,
                'amount' => $json_obj['amount'],
                'currency' => 'USD',
                'payment_status' => 'pending',
                'stripe_payment_intent_id' => $paymentIntent->id,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            echo json_encode(['clientSecret' => $paymentIntent->client_secret]);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // Confirm payment and update status
    public function confirm_payment() {
        try {
            \Stripe\Stripe::setApiKey($this->config->item('stripe_secret'));

            $json_str = file_get_contents('php://input');
            $json_obj = json_decode($json_str, true);

            if (!isset($json_obj['payment_intent_id'])) {
                throw new Exception("Invalid payment intent ID");
            }

            $paymentIntent = \Stripe\PaymentIntent::retrieve($json_obj['payment_intent_id']);

            if ($paymentIntent->status === 'succeeded') {
                $this->Payment_Model->update_payment_status(
                    $paymentIntent->id,
                    'success',
                    $paymentIntent->charges->data[0]->receipt_url ?? null,
                    $paymentIntent->charges->data[0]->payment_method ?? null
                );

                echo json_encode(['message' => 'Payment successful', 'receipt_url' => $paymentIntent->charges->data[0]->receipt_url]);
            } else {
                $this->Payment_Model->update_payment_status($paymentIntent->id, 'failed');
                echo json_encode(['error' => 'Payment failed']);
            }

        } catch (\Stripe\Exception\ApiErrorException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // refund payment
    public function refund_payment() {
        try {
            \Stripe\Stripe::setApiKey($this->config->item('stripe_secret'));
    
            $json_str = file_get_contents('php://input');
            $json_obj = json_decode($json_str, true);
    
            if (!isset($json_obj['payment_intent_id'])) {
                throw new Exception("Invalid payment intent ID");
            }
    
            // Retrieve the payment intent
            $paymentIntent = \Stripe\PaymentIntent::retrieve($json_obj['payment_intent_id']);
    
            // Check if the payment was successful before refunding
            if ($paymentIntent->status !== 'succeeded') {
                throw new Exception("Only successful payments can be refunded.");
            }

                    // print_r($paymentIntent); die();
    
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
    
                echo json_encode(['message' => 'Refund successful', 'refund_id' => $refund->id]);
            } else {
                echo json_encode(['error' => 'Refund failed.']);
            }
    
        } catch (\Stripe\Exception\ApiErrorException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
}
