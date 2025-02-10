<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Exception;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; 



class EventController extends Controller
{

    
    public function index()
    {
        $Events = Event::orderBy('created_at', 'desc')->get(); // Fetch events in descending order
        return response()->json($Events); // Return as JSON
    }
    
    
    public function createEvent(Request $request)
    {
        try {
            // Validate the input data
            $validatedData = $request->validate([
                'event_category' => 'required|string|max:255',
                'event_title' => 'required|string|max:255',
                'event_location' => 'required|string|max:255',
                'event_venue' => 'required|string|max:255',
                'event_start_time' => 'required|date',
                'event_end_time' => 'required|date',
                'event_date' => 'required|date',
                'event_description' => 'nullable|string',
                'event_card' => 'nullable|file|max:10240',  // Validate for file size up to 10MB
                'event_code' => 'required|string|unique:events,event_code|max:100',
                'notifications_whatsapp' => 'boolean',
                'notifications_email' => 'boolean',
                'notifications_sms' => 'boolean',
            ]);

            // Ensure the directory exists (optional if storage:link has been run)
            $directory = public_path('storage/event_cards');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);  // Create directory with appropriate permissions
            }

            // Handle the file upload if present
            $imagePath = null;  // Default if no file is uploaded
            if ($request->hasFile('event_card')) {
                $file = $request->file('event_card');

                // Generate a unique name for the file to avoid overwriting
                $imageName = time() . '-' . $file->getClientOriginalName();

                // Move the file to the directory
                $file->move($directory, $imageName);

                // Store the file path in the database
                $imagePath = 'storage/event_cards/' . $imageName;
                $validatedData['event_card'] = $imagePath;
            }

            // Create the event in the database
            $event = Event::create($validatedData);

            // Return a success response with the event data and image path
            return response()->json([
                'status' => 'success',
                'message' => 'Event created successfully',
                'event' => $event,
                'image_path' => $imagePath,  // Optionally return the image path in the response
            ], 201);
        } catch (\Exception $e) {
            // Log the exception with detailed information
            Log::error('Error creating event: ' . $e->getMessage(), ['exception' => $e]);

            // Return a custom error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the event. Please try again later.',
                'error' => $e->getMessage(),  // Optionally return the error message for debugging
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $event = Event::find($id);

            if (!$event) {
                return response()->json(['message' => 'event not found'], 404);
            }

            return response()->json($event);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving the event',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function showEventPageById(Request $request)
    {
        // Retrieve query parameters
        $eventData = $request->only([
            'event_id',
            'event_code',
            'event_category',
            'event_title',
            'event_location',
            'event_venue',
            'event_start_time',
            'event_end_time',
            'event_status',
            'created_at',
        ]);

        // Optional: Fetch additional details from the database
        // $event = Event::find($eventData['event_id']);

        // Pass data to a view
        return view('pages.events.show', ['event' => $eventData]);
    }

   // Import Str for passcode generation


   public function uploadExcel(Request $request)
   {
       try {
           $request->validate([
               'excel_file' => 'required|file|mimes:xls,xlsx',
               'event_id' => 'required|integer|exists:events,event_id',
           ]);
   
           $eventId = $request->input('event_id');
           $file = $request->file('excel_file');
   
           // Parse Excel data
           $data = Excel::toArray([], $file, null, \Maatwebsite\Excel\Excel::XLSX);
           $rows = $data[0];
   
           $membersToInsert = [];
   
           foreach ($rows as $row) {
               if (count($row) >= 4) {
                   $passCode = strtoupper(Str::random(2)) . rand(10, 99); // Generate two letters + two numbers
   
                   $membersToInsert[] = [
                       'event_id' => $eventId,
                       'pass_code' => $passCode, // Auto-generated passcode
                       'member_name' => $row[0],
                       'status' => $row[1],
                       'total' => $row[2],
                       'member_phone' => $row[3],
                       'scans' => 0,       // Default value
                       'scanned' => 0,     // Default value
                       'created_at' => now(),
                       'updated_at' => now(),
                   ];
               }
           }
   
           if (count($membersToInsert) > 0) {
               \DB::table('event_members')->insert($membersToInsert);
           }
   
           return response()->json([
               'success' => true,
               'message' => 'File processed and members inserted successfully.',
               'data' => $membersToInsert,
           ]);
       } catch (\Illuminate\Validation\ValidationException $e) {
           return response()->json([
               'success' => false,
               'message' => 'Validation error.',
               'errors' => $e->errors(),
           ], 422);
       } catch (\Exception $e) {
           return response()->json([
               'success' => false,
               'message' => 'An unexpected error occurred.',
               'error' => $e->getMessage(),
           ], 500);
       }
   }
   


    // public function uploadExcel(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'excel_file' => 'required|file|mimes:xls,xlsx',
    //             'event_id' => 'required|integer|exists:events,event_id', // Validate event_id is present and exists in the events table
    //         ]);
    
    //         $eventId = $request->input('event_id'); // Get the event_id from the request
    
    //         $file = $request->file('excel_file');
    
    //         // Parse Excel data and ensure all values are returned as raw strings (preserving numbers exactly as they appear)
    //         $data = Excel::toArray([], $file, null, \Maatwebsite\Excel\Excel::XLSX); 
    
    //         // Assuming the data is in the first sheet
    //         $rows = $data[0];
    
    //         // Prepare an array for inserting data into the database
    //         $membersToInsert = [];
    
    //         foreach ($rows as $row) {
    //             // Ensure that the row has the necessary columns (member_name, status, total, member_phone)
    //             if (count($row) >= 4) {
    //                 $membersToInsert[] = [
    //                     'event_id' => $eventId,  // Use the event_id passed from the controller
    //                     'member_name' => $row[0],  // Assuming member_name is in the first column
    //                     'status' => $row[1],       // Assuming status is in the second column
    //                     'total' => $row[2],        // Assuming total is in the third column
    //                     'member_phone' => $row[3], // Assuming member_phone is in the fourth column
    //                     'created_at' => now(),     // Set current timestamp for created_at
    //                     'updated_at' => now(),     // Set current timestamp for updated_at
    //                 ];
    //             }
    //         }
    
    //         // Insert the data into the database if we have any rows to insert
    //         if (count($membersToInsert) > 0) {
    //             \DB::table('event_members')->insert($membersToInsert); // Insert all members at once
    //         }
    
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'File processed and members inserted successfully.',
    //             'data' => $membersToInsert, // Optionally return the inserted data for confirmation
    //         ]);
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation error.',
    //             'errors' => $e->errors(),
    //         ], 422);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An unexpected error occurred.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }
    
    public function newUpdateMethod(Request $request, $id)
{
    try {
        // Find the event by ID
        $event = Event::findOrFail($id);

        // Validate only fields that are present in the request
        $validatedData = $request->validate([
            'event_title' => 'sometimes|string|max:255',
            'event_category' => 'sometimes|string|max:255',
            'event_location' => 'sometimes|string|max:255',
            'event_venue' => 'sometimes|string|max:255',
            'event_start_time' => 'sometimes|date',
            'event_end_time' => 'sometimes|date',
            'event_status' => 'sometimes|in:Upcoming,Ongoing,Completed',
        ]);

        // Update only the provided fields
        $event->update($validatedData);

        return response()->json(['message' => 'Event updated successfully!', 'event' => $event], 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['error' => 'Event not found!'], 404);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['error' => 'Validation failed!', 'errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        return response()->json(['error' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
    }
}


}
