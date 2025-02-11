<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use App\Models\Event;
use Illuminate\Support\Facades\Http;


class SendMessageController extends Controller
{
   
    
    public function whatsappMethod(Request $request)
    {
        return response()->json(['message' => 'WhatsApp message endpoint hit!']);
    }

    public function smsMethod2(Request $request)
    {
        try {
            // Retrieve all request data
            $data = $request->all();
    
            // Log the received data for debugging
            Log::info('Received SMS Data:', $data);
    
            // Validate that event_id is provided
            if (!isset($data['event_id'])) {
                return response()->json([
                    'message' => 'event_id is required!',
                    'received_data' => $data
                ], 400);
            }
    
            // Fetch event data using event_id
            $event = Event::where('event_id', $data['event_id'])->first();
    
            // Check if event exists
            if (!$event) {
                return response()->json([
                    'message' => 'Event not found!',
                    'event_id' => $data['event_id']
                ], 404);
            }
    
            // Construct the invitation message
            $invitationMessage = "Mpendwa {$data['name']},\n\n";
            $invitationMessage .= "Tunayo furaha kukualika kwenye {$event->event_title}, itakayofanyika tarehe {$event->event_date} katika {$event->event_venue}. ";
            $invitationMessage .= "Tukio hili litakuwa fursa nzuri ya kushiriki mazungumzo yenye tija, kufanya mawasiliano muhimu, na kuwa na wakati wa kufurahisha.\n\n";
            $invitationMessage .= "📍 Mahali: {$event->event_location}\n";
            $invitationMessage .= "🕒 Muda: {$event->event_start_time} - {$event->event_end_time}\n";
            $invitationMessage .= "📅 Tarehe: {$event->event_date}\n\n";
            $invitationMessage .= "Tutafurahi sana uwe mgeni wetu. Tafadhali thibitisha uwepo wako kwa kujibu ujumbe huu. ";
            $invitationMessage .= "Ikiwa una maswali yoyote, usisite kuwasiliana nasi.\n\n";
            $invitationMessage .= "Tunatarajia kukuona!\n\n";
            $invitationMessage .= "Kwa heshima,\n";
            $invitationMessage .= "[Jina Lako / Jina la Taasisi]\n";
            $invitationMessage .= "📞 [Maelezo ya Mawasiliano]";  
            
    
            // Return the invitation message along with event details
            return response()->json([
                'message' => 'Event found successfully!',
                'event' => $event,
                'invitation_message' => $invitationMessage
            ]);
    
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in smsMethod: ' . $e->getMessage());
    
            // Return a server error response
            return response()->json([
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function sendBoth(Request $request)
    {
        return response()->json(['message' => 'Both WhatsApp and SMS endpoint hit!']);
    }


    public function smsMethod(Request $request)
    {
        try {
            // Retrieve all request data
            $data = $request->all();
    
            // Log the received data for debugging
            Log::info('Received SMS Data:', $data);
    
            // Validate that event_id and phone number are provided
            if (!isset($data['event_id']) || !isset($data['phone'])) {
                return response()->json([
                    'message' => 'event_id and phone number are required!',
                    'received_data' => $data
                ], 400);
            }
    
            // Fetch event data using event_id
            $event = Event::where('event_id', $data['event_id'])->first();
    
            // Check if event exists
            if (!$event) {
                return response()->json([
                    'message' => 'Event not found!',
                    'event_id' => $data['event_id']
                ], 404);
            }
    
            // Format phone number (Ensure it starts with 255)
            $phone = $data['phone'];
            if (substr($phone, 0, 1) === '0') {
                $phone = '255' . substr($phone, 1);
            }
    
            // Construct the invitation message in Swahili
            $invitationMessage = "Mpendwa {$data['name']},\n\n";
            $invitationMessage .= "Tunayo furaha kukualika kwenye {$event->event_title}, itakayofanyika tarehe {$event->event_date} katika {$event->event_venue}. ";
            $invitationMessage .= "Tukio hili litakuwa fursa nzuri ya kushiriki mazungumzo yenye tija, kufanya mawasiliano muhimu, na kuwa na wakati wa kufurahisha.\n\n";
            $invitationMessage .= " Mahali: {$event->event_location}\n";
            $invitationMessage .= " Muda: {$event->event_start_time} - {$event->event_end_time}\n";
            $invitationMessage .= " Tarehe: {$event->event_date}\n\n";
            $invitationMessage .= "Tutafurahi sana uwe mgeni wetu. Tafadhali thibitisha uwepo wako kwa kujibu ujumbe huu. ";
            $invitationMessage .= "Ikiwa una maswali yoyote, usisite kuwasiliana nasi.\n\n";
            $invitationMessage .= "Tunatarajia kukuona!\n\n";
            $invitationMessage .= "Kwa heshima,\n";
            $invitationMessage .= "[Jina Lako / Jina la Taasisi]\n";
            $invitationMessage .= " [Maelezo ya Mawasiliano]";
    
            // API URL
            $apiUrl = "https://messaging-service.co.tz/api/sms/v1/text/single";
    
            // Headers
            $headers = [
                'Authorization' => 'Basic ZW5vY2tkZGQ6dGlzaDE5OTchIXE=', 
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json'
            ];
    
            // Payload
            $payload = [
                "from"      => "THIRDWAVE",
                "to"        => "255710056152",
                "text"      => $invitationMessage,
                "reference" => "xaioiofgt"
            ];
    
            // Send request
            $response = Http::withHeaders($headers)->post($apiUrl, $payload);
    
            // Log response
            Log::info('SMS API Response:', $response->json());
    
            return response()->json([
                'message'  => 'Invitation SMS sent successfully!',
                'response' => $response->json()
            ]);
    
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in smsMethod: ' . $e->getMessage());
    
            return response()->json([
                'message' => 'An unexpected error occurred.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
// ==========================================================================================

    public function sendToAllMember(Request $request)
    {
        try {
            // Retrieve data from the request
            $data = $request->validate([
                'id' => 'required|string',
                'name' => 'required|string',
                'phone' => 'required|string',
                'status' => 'nullable|string',
                'total' => 'nullable|string',
                'event.id' => 'required|string',
                'event.category' => 'required|string',
                'event.title' => 'required|string',
                'event.dateTime' => 'required|string',
                'event.venue' => 'required|string',
            ]);
    
            // Construct the message body
            $message = "Dear {$data['name']}, 
            You are invited to the '{$data['event']['title']}' event.
            Category: {$data['event']['category']}
            Date & Time: {$data['event']['dateTime']}
            Venue: {$data['event']['venue']}
            Status: {$data['status']}
            Total: {$data['total']}";
    
            // Log the message (For debugging)
            Log::info("Sending message to {$data['phone']}: $message");
    
            // Here, you can send the message via an SMS API, email, or any preferred method
            // Example: Send SMS function (Replace this with actual implementation)
            // SMSService::send($data['phone'], $message);
    
            return response()->json([
                'success' => true,
                'message' => "Message successfully sent to {$data['name']} ({$data['phone']})",
                'event_details' => [
                    'event_id' => $data['event']['id'],
                    'title' => $data['event']['title'],
                    'category' => $data['event']['category'],
                    'dateTime' => $data['event']['dateTime'],
                    'venue' => $data['event']['venue'],
                ]
            ], 200);
    
        } catch (\Exception $e) {
            Log::error("Error sending message: " . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => 'Failed to send the message.'
            ], 500);
        }
    }
    
    // public function sendToAllMember(Request $request)
    // {
    //     return response()->json(['message' => 'All Member WhatsApp and SMS endpoint hit!']);
    // }

// -----------------------------------------------------------via whatsapp --------------------------------------------------
 // Method to handle form submission and send a WhatsApp message via Twilio
 public function store(Request $request)
 {
     // Validate input
     $request->validate([
         'phone' => 'required|string',
         'message' => 'required|string',
     ]);
 
     // Retrieve Twilio credentials from .env
     $twilioSid = env('TWILIO_SID');
     $twilioAuthToken = env('TWILIO_AUTH_TOKEN');
     $twilioWhatsappNumber = 'whatsapp:' . env('TWILIO_WHATSAPP_NUMBER');
 
     // Format recipient phone number for WhatsApp
     $to = 'whatsapp:' . $request->phone;
     $messageContent = $request->message;
 
     try {
         // Create Twilio client
         $client = new Client($twilioSid, $twilioAuthToken);
 
         // Send the WhatsApp message
         $message = $client->messages->create($to, [
             'from' => $twilioWhatsappNumber,
             'body' => $messageContent
         ]);
 
         // Log message SID for debugging
         Log::info("WhatsApp message sent successfully. SID: " . $message->sid);
 
         return response()->json([
             'success' => true,
             'message' => 'Message sent successfully!',
             'sid' => $message->sid
         ], 200);
     } catch (\Exception $e) {
         // Log error for debugging
         Log::error("Error sending WhatsApp message: " . $e->getMessage());
 
         return response()->json([
             'success' => false,
             'error' => 'Error sending message: ' . $e->getMessage()
         ], 500);
     }}
// -------------------------------------------------------------nd via whatsapp -------------------------------------------------



//API

// public function smsMethod(Request $request)
// {
//     $request->validate([
//         'to' => 'required|array',
//         'text' => 'required|string',
//         'reference' => 'required|string'
//     ]);

//     $apiUrl = "https://messaging-service.co.tz/api/sms/v1/text/single";
//     $apiKey = "your-api-key-here"; // Replace with your actual API key

//     $payload = [
//         'from' => 'THIRDWAVE',
//         'to' => $request->to,
//         'text' => $request->text,
//         'reference' => $request->reference,
//     ];

//     $response = Http::withHeaders([
//         'Content-Type' => 'application/json',
//         'Accept' => 'application/json',
//         'Authorization' => 'Bearer ' . $apiKey,
//     ])->post($apiUrl, $payload);

//     if ($response->successful()) {
//         return response()->json(['message' => 'SMS sent successfully!', 'response' => $response->json()], 200);
//     }

//     return response()->json(['error' => 'Failed to send SMS', 'details' => $response->json()], $response->status());
// }


    
}
