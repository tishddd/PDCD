<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurlTestController extends Controller
{

    public function testCurl()
    {
        // Initialize cURL session
        $ch = curl_init();
    
        // Set the URL to fetch
        curl_setopt($ch, CURLOPT_URL, "https://www.google.com");
    
        // Return the response instead of printing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
        // Enforce SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
        // Execute cURL session
        $output = curl_exec($ch);
    
        // Check for errors
        if (curl_errno($ch)) {
            $error_message = "cURL Error: " . curl_error($ch);
            curl_close($ch);
            return response($error_message, 500);  // Return error response
        }
    
        // Close cURL session
        curl_close($ch);
    
        // Return success message
        return response("Request was successful!", 200);
    }
    

//     public function testCurl()
// {
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, "https://www.google.com");
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
//     // Disable SSL certificate verification (for testing only)
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

//     $output = curl_exec($ch);

//     if (curl_errno($ch)) {
//         $error_message = "cURL Error: " . curl_error($ch);
//         curl_close($ch);
//         return response($error_message, 500);
//     }

//     curl_close($ch);
//     return response("Request was successful!", 200);
// }

    // public function testCurl()
    // {
    //     // Initialize cURL session
    //     $ch = curl_init();

    //     // Set the URL to fetch
    //     curl_setopt($ch, CURLOPT_URL, "https://www.google.com");

    //     // Return the response instead of printing it
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //     // Enforce SSL certificate verification
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

    //     // Execute cURL session
    //     $output = curl_exec($ch);

    //     // Check for errors
    //     if(curl_errno($ch)) {
    //         $error_message = "cURL Error: " . curl_error($ch);
    //         curl_close($ch);
    //         return response($error_message, 500);  // Return error response
    //     }

    //     // Close cURL session
    //     curl_close($ch);

    //     // Return success message
    //     return response("Request was successful!", 200);
    // }
}
