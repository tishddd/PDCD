<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use App\Models\Event;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class SendMessageController extends Controller
{


    //-------------------------------SEND WHATSAPP MESSAGE WITH CARD-------------------------------/


    // public function whatsappMethod(Request $request)
    // {
    //     try {
    //         $data = $request->all();

    //         $event_id = $data['event_id'] ?? null;
    //         $id = $data['id'] ?? null;
    //         $name = $data['name'] ?? null;
    //         $passcode = $data['passcode'] ?? null;
    //         $phone = $data['phone'] ?? null;
    //         $status = $data['status'] ?? null;
    //         $total = $data['total'] ?? null;
    //         $eventCardUrl = $data['event_card'] ?? null; // Image URL

    //         if (!$eventCardUrl) {
    //             return response()->json([
    //                 'message' => 'Event card image URL is missing',
    //                 'response' => 'Error'
    //             ], 400);
    //         }

    //         // ✅ Extract only the relative storage path
    //         $parsedUrl = parse_url($eventCardUrl, PHP_URL_PATH);
    //         $relativePath = str_replace('/storage/', '', $parsedUrl);
    //         $eventCardPath = storage_path('app/public/' . $relativePath);

    //         // ✅ Check if the file exists in storage
    //         if (!file_exists($eventCardPath)) {
    //             return response()->json([
    //                 'message' => "Event card image not found at: $eventCardPath",
    //                 'response' => 'Error'
    //             ], 404);
    //         }

    //         // Load the event card dynamically
    //         $cardImage = imagecreatefromstring(file_get_contents($eventCardPath));

    //         $cardWidth = imagesx($cardImage);
    //         $cardHeight = imagesy($cardImage);

    //         // Generate QR Code
    //         $qrData = "Passcode: $passcode";
    //         $qrCode = QrCode::format('png')->size(200)->margin(4)->generate($qrData);
    //         $qrImage = imagecreatefromstring($qrCode);

    //         // Resize QR Code to fit the bottom-left corner
    //         $qrSize = $cardWidth * 0.2; // 20% of the card width
    //         $qrResized = imagecreatetruecolor($qrSize, $qrSize);
    //         imagealphablending($qrResized, false);
    //         imagesavealpha($qrResized, true);
    //         imagecopyresampled($qrResized, $qrImage, 0, 0, 0, 0, $qrSize, $qrSize, imagesx($qrImage), imagesy($qrImage));

    //         // Merge QR Code onto the event card (Bottom-left corner)
    //         $qrX = 20; // 20px padding from left
    //         $qrY = $cardHeight - $qrSize - 20; // 20px padding from bottom
    //         imagecopy($cardImage, $qrResized, $qrX, $qrY, 0, 0, $qrSize, $qrSize);

    //         // Save final image
    //         ob_start();
    //         imagepng($cardImage);
    //         $finalImage = ob_get_clean();

    //         $fileName = 'event_card_qr_' . time() . '.png';
    //         Storage::disk('public')->put('cards/' . $fileName, $finalImage);

    //         // Free memory
    //         imagedestroy($qrImage);
    //         imagedestroy($qrResized);
    //         imagedestroy($cardImage);

    //         return response()->json([
    //             'message' => 'QR Code added to event card successfully',
    //             'data' => compact('event_id', 'id', 'name', 'passcode', 'phone', 'status', 'total', 'event_card'),
    //             'qr_card_path' => asset('storage/cards/' . $fileName) // Public URL of the saved card with QR code
    //         ]);
    //     } catch (\Exception $e) {
    //         Log::error('Error overlaying QR Code: ' . $e->getMessage());

    //         return response()->json([
    //             'message' => 'An error occurred while processing your request.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function whatsappMethod(Request $request)
    {
        try {
            $data = $request->all();
    
            $event_id = $data['event_id'] ?? null;
            $id = $data['id'] ?? null;
            $name = $data['name'] ?? null;
            $passcode = $data['passcode'] ?? null;
            $phone = $data['phone'] ?? null;
            $status = $data['status'] ?? null;
            $total = $data['total'] ?? null;
            $eventCardFilename = $data['event_card'] ?? null; // Filename only (e.g., "1738783082-card.jpg")
    
            if (!$eventCardFilename) {
                return response()->json([
                    'message' => 'Event card image filename is missing',
                    'response' => 'Error'
                ], 400);
            }
    
            // ✅ Use the correct path based on the filename
            $cardPath = storage_path('app/public/event_cards/' . $eventCardFilename);
    
            if (!file_exists($cardPath)) {
                return response()->json([
                    'message' => "Event card image not found at: $cardPath",
                    'response' => 'Error'
                ], 404);
            }
    
            // Load the event card dynamically
            $cardImage = imagecreatefromstring(file_get_contents($cardPath));
    
            $cardWidth = imagesx($cardImage);
            $cardHeight = imagesy($cardImage);
    
            // Generate QR Code
            $qrData = "Passcode: $passcode";
            $qrCode = QrCode::format('png')->size(200)->margin(4)->generate($qrData);
            $qrImage = imagecreatefromstring($qrCode);
    
            // Resize QR Code to fit the bottom-left corner
            $qrSize = $cardWidth * 0.2; // 20% of the card width
            $qrResized = imagecreatetruecolor($qrSize, $qrSize);
            imagealphablending($qrResized, false);
            imagesavealpha($qrResized, true);
            imagecopyresampled($qrResized, $qrImage, 0, 0, 0, 0, $qrSize, $qrSize, imagesx($qrImage), imagesy($qrImage));
    
            // Merge QR Code onto the event card (Bottom-left corner)
            $qrX = 20; // 20px padding from left
            $qrY = $cardHeight - $qrSize - 20; // 20px padding from bottom
            imagecopy($cardImage, $qrResized, $qrX, $qrY, 0, 0, $qrSize, $qrSize);
    
            // Save final image
            ob_start();
            imagepng($cardImage);
            $finalImage = ob_get_clean();
    
            $fileName = 'event_card_qr_' . time() . '.png';
            Storage::disk('public')->put('cards/' . $fileName, $finalImage);
    
            // Free memory
            imagedestroy($qrImage);
            imagedestroy($qrResized);
            imagedestroy($cardImage);
    
            return response()->json([
                'message' => 'QR Code added to event card successfully',
                'data' => compact('event_id', 'id', 'name', 'passcode', 'phone', 'status', 'total', 'eventCardFilename'),
                'qr_card_path' => asset('storage/cards/' . $fileName) // Public URL of the saved card with QR code
            ]);
        } catch (\Exception $e) {
            Log::error('Error overlaying QR Code: ' . $e->getMessage());
    
            return response()->json([
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    // public function whatsappMethod(Request $request)
    // {
    //     try {
    //         $data = $request->all();

    //         $event_id = $data['event_id'] ?? null;
    //         $id = $data['id'] ?? null;
    //         $name = $data['name'] ?? null;
    //         $passcode = $data['passcode'] ?? null;
    //         $phone = $data['phone'] ?? null;
    //         $status = $data['status'] ?? null;
    //         $total = $data['total'] ?? null;
    //         $event_card = $data['event_card'] ?? null;

    //         if (!$passcode) {
    //             return response()->json([
    //                 'message' => 'Passcode not found',
    //                 'response' => 'Error'
    //             ], 400);
    //         }

    //         $qrData = "Passcode: $passcode";

    //         $qrCode = QrCode::format('png')
    //             ->size(200)
    //             ->margin(4)
    //             ->generate($qrData);

    //         $image = imagecreatefromstring($qrCode);
    //         $qrWidth = imagesx($image);
    //         $qrHeight = imagesy($image);

    //         $backgroundWidth = $qrWidth + 40;
    //         $backgroundHeight = $qrHeight + 40;
    //         $background = imagecreatetruecolor($backgroundWidth, $backgroundHeight);

    //         $green = imagecolorallocate($background, 0, 128, 0);
    //         imagefill($background, 0, 0, $green);

    //         $x = ($backgroundWidth - $qrWidth) / 2;
    //         $y = ($backgroundHeight - $qrHeight) / 2;
    //         imagecopy($background, $image, $x, $y, 0, 0, $qrWidth, $qrHeight);

    //         ob_start();
    //         imagepng($background);
    //         $finalImage = ob_get_clean();

    //         $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($finalImage);

    //         // Save the image in Laravel storage (storage/app/public/cards/)
    //         $fileName = 'qr_card_' . time() . '.png';
    //         Storage::disk('public')->put('cards/' . $fileName, $finalImage);

    //         // Free memory
    //         imagedestroy($image);
    //         imagedestroy($background);

    //         return response()->json([
    //             'message' => 'QR Code generated successfully',
    //             'data' => compact('event_id', 'id', 'name', 'passcode', 'phone', 'status', 'total', 'event_card'),
    //             'response' => $qrCodeBase64,
    //             'qr_card_path' => asset('storage/cards/' . $fileName) // Get public URL for the stored file
    //         ]);
    //     } catch (\Exception $e) {
    //         Log::error('Error generating QR Code: ' . $e->getMessage());

    //         return response()->json([
    //             'message' => 'An error occurred while processing your request.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    //------------------------------------------end ------------------------------------------------------



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
            $invitationMessage = "Habari {$data['name']},\n\n";
            $invitationMessage .= "Familia ya Ekonia Damas Kambanga inapenda Kukushukuru Kwa Mchango wako mkubwa na moyo wa kipekee ulio uonesha katika kuwezesha {$event->event_category} ya \n";
            $invitationMessage .= "**{$event->event_title}**\n"; // Bold formatting
            $invitationMessage .= "Unakaribishwa Sana tarehe {$event->event_date} katika ukumbi wa {$event->event_venue}.\n\n";
            $invitationMessage .= "📍 Mahali: {$event->event_location}\n";
            $invitationMessage .= "⏰ Muda: {$event->event_start_time}\n";
            $invitationMessage .= "📅 Tarehe: {$event->event_date}\n";
            $invitationMessage .= "🔑 PassCode: {$data['passcode']}\n\n";
            $invitationMessage .= "Ikiwa unahitaji maelezo zaidi, usisite kuwasiliana nasi.\n\n";
            $invitationMessage .= "Tunatarajia kukuona!\n\n";
            $invitationMessage .= "Kwa heshima,\n";
            $invitationMessage .= "Enock damas / ThirdWave Software\n";
            $invitationMessage .= "0742758926";


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
                "to"        => $phone,
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
        }
    }
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
