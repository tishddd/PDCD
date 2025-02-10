<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MemberController extends Controller
{
    /**
     * Store a newly created member in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

     
    public function index()
    {
        $Members = Member::all();  // Fetch all Members
  
        return response()->json($Members);  // Return data as JSON
    }
    public function store(Request $request)
    {
        try {
            // Step 1: Validate the incoming request data
            $validatedData = $request->validate([
                'event_id' => 'required|integer|exists:events,event_id',  // Ensure event_id exists in the events table
                'member_name' => 'required|string|max:255',
                'member_phone' => 'required|string|max:20',
                'member_email' => 'required|string|email|max:255|unique:event_members,member_email', // Ensure unique email for each member
                'rsvp_status' => 'required|in:Attending,Not Attending,Pending',
            ]);

            // Step 2: Use transaction for atomicity (Optional but recommended for consistency)
            DB::beginTransaction();

            // Step 3: Create a new member
            $member = Member::create($validatedData);

            // Step 4: Commit the transaction
            DB::commit();

            // Step 5: Return success response with the created member
            return response()->json([
                'status' => 'success',
                'message' => 'Member created successfully',
                'member' => $member,
            ], 201);

        } catch (ValidationException $e) {
            // Step 6: Catch validation errors and return the response
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            // Step 7: Catch any other errors (including database issues, etc.)
            DB::rollBack();  // Rollback the transaction in case of an error

            // Log the error for debugging
            Log::error('Error creating member: ' . $e->getMessage(), ['exception' => $e]);

            // Step 8: Return a generic error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the member. Please try again later.',
                'error' => $e->getMessage(),  // Optionally, return error details for debugging purposes
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $member = Member::findOrFail($id); // Fetch member by ID
            return response()->json([
                'status' => 'success',
                'data' => $member,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Member not found',
            ], 404);
        }
    }
    
    public function showByEvent($event_id)
    {
        try {
            $members = Member::where('event_id', $event_id)->get(); // Fetch members by event_id
            if ($members->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No members found for this event.',
                ], 404);
            }
    
            return response()->json([
                'status' => 'success',
                'data' => $members,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching members.',
            ], 500);
        }
    }
    

    public function update(Request $request, $id)
    {
        try {
            // Validate the incoming data, including the status field
            $validatedData = $request->validate([
                'status' => 'nullable|string|max:255',  // Make sure status is validated if present
                'total'=> 'nullable|string|max:255', 
                'event_id' => 'nullable|integer|exists:events,event_id',
                'member_name' => 'nullable|string|max:255',
                'member_phone' => 'nullable|string|max:20',
                'member_email' => 'nullable|string|email|max:255|unique:event_members,member_email,' . $id,
                'rsvp_status' => 'nullable|in:Attending,Not Attending,Pending',
            ]);
    
            // Find the member by ID and update it
            $member = Member::findOrFail($id);
            $member->update($validatedData);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Member updated successfully',
                'member' => $member,
            ], 200);
    
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
    
        } catch (\Exception $e) {
            Log::error('Error updating member: ' . $e->getMessage(), ['exception' => $e]);
    
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the member. Please try again later.',
            ], 500);
        }
    }
    
public function remove($id)
{
    try {
        // Step 1: Find the member by ID
        $member = Member::findOrFail($id);

        // Step 2: Delete the member
        $member->delete();

        // Step 3: Return a success response
        return response()->json([
            'status' => 'success',
            'message' => 'Member deleted successfully',
        ], 200);

    } catch (\Exception $e) {
        // Handle errors
        Log::error('Error deleting member: ' . $e->getMessage(), ['exception' => $e]);

        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while deleting the member. Please try again later.',
        ], 500);
    }
}
public function sum(Request $request)
{
    // Validate the request to ensure pass_code and number are provided
    $request->validate([
        'pass_code' => 'required|string', // pass_code must be a string
        'number' => 'required|numeric'   // number must be numeric
    ]);

    // Retrieve the pass_code and number from the request
    $passCode = $request->input('pass_code');
    $numberToAdd = $request->input('number');

    // Retrieve the current sum of 'scans' and the 'total' value for the given pass_code
    $eventMember = DB::table('event_members')
        ->where('pass_code', $passCode)
        ->first(['scans', 'total', 'scanned']); // Retrieve 'scans', 'total', and 'scanned' columns

    if (!$eventMember) {
        // If no record is found for the pass_code, return an error
        return response()->json([
            'success' => false,
            'message' => 'Invalid pass_code. No member found with the given pass_code.'
        ], 404);
    }

    // Cast 'scans' to an integer (as it's stored as varchar) and retrieve 'total'
    $currentScans = (int) $eventMember->scans;
    $maxTotal = (int) $eventMember->total;

    // Check if adding the number exceeds the maximum allowed total
    if (($currentScans + $numberToAdd) > $maxTotal) {
        return response()->json([
            'success' => false,
            'message' => 'The number is greater than the maximum allowed guests.',
            'current_scans' => $currentScans,
            'additional_number' => $numberToAdd,
            'max_total' => $maxTotal
        ], 400); // Return 400 Bad Request
    }

    // Calculate the new total for 'scans'
    $newTotal = $currentScans + $numberToAdd;

    // Check if scans are equal to total, and set 'scanned' to 1 (true) if they are
    $scanned = ($newTotal == $maxTotal) ? 1 : 0;

    // Update the 'scans' and 'scanned' value in the database for the relevant rows
    DB::table('event_members')
        ->where('pass_code', $passCode)
        ->update([
            'scans' => $newTotal, // Save the new total in the 'scans' column
            'scanned' => $scanned // Update the 'scanned' column
        ]);

    // Return the result as a JSON response
    return response()->json([
        'success' => true,
        'pass_code' => $passCode,
        'current_scans' => $currentScans,
        'additional_number' => $numberToAdd,
        'total_scans' => $newTotal,
        'max_total' => $maxTotal,
        'scanned' => $scanned, // Return the 'scanned' value
        'message' => 'The total has been successfully updated in the database.'
    ]);
}



}
