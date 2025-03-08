<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller{
    
    public function __construct(){
        parent::__construct();
    }

    public function generate_token($user_id) {
        $secret_key = 'cabanaboats'; // Use a strong secret key
        $issued_at = time();
        $expiration_time = $issued_at + (60 * 60); // Token expires in 1 hour

        $payload = array(
            'user_id' => $user_id,
            'iat' => $issued_at,
            'exp' => $expiration_time
        );

        // Convert payload to JSON and base64 encode it
        $payload_encoded = base64_encode(json_encode($payload));

        // Create a signature
        $signature = hash_hmac('sha256', $payload_encoded, $secret_key, true);
        $signature_encoded = base64_encode($signature);

        // Generate the token
        return $payload_encoded . "." . $signature_encoded;
    }

    public function validate_token($token) {
        $secret_key = 'cabanaboats';

        // Split the token
        $token_parts = explode('.', $token);
        if (count($token_parts) !== 2) {
            return false;
        }

        $payload_encoded = $token_parts[0];
        $signature_encoded = $token_parts[1];

        // Verify signature
        $expected_signature = base64_encode(hash_hmac('sha256', $payload_encoded, $secret_key, true));
        if ($expected_signature !== $signature_encoded) {
            return false;
        }

        // Decode payload
        $payload = json_decode(base64_decode($payload_encoded), true);
        
        // Check expiration time
        if ($payload['exp'] < time()) {
            return false; // Token expired
        }

        return $payload; // Return decoded payload
    }

}

?>