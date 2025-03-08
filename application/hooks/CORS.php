<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CORS {
    public function handle()
    {
        // Allow requests from your React app
        header("Access-Control-Allow-Origin: *"); // Allow all origins (*), or specify "https://your-react-app.com"
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE"); // Allowed HTTP methods
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

        // Handle Preflight OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
    }
}
?>