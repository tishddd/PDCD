<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Event;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Login method
    public function login(Request $request)
{
    try {
        // Validate the incoming request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'event_code' => 'required|exists:events,event_code', // Check event_code exists in the events table
        ]);

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        // If the user does not exist or password is incorrect
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'These credentials do not match our records.',
            ], 401);
        }

        // Verify event_code is active (if additional checks are needed)
        $event = Event::where('event_code', $request->event_code)
            ->where('event_status', '!=', 'Completed') // Example condition for active events
            ->first();

        if (!$event) {
            return response()->json([
                'error' => 'Invalid or inactive event code.',
            ], 400);
        }

        // Create an API token using Sanctum
        $token = $user->createToken('psc')->plainTextToken;

        // Return the token as a JSON response
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'event_details' => $event, // Optional: Include event details in response
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        // Catch validation exceptions
        return response()->json([
            'error' => 'Validation failed.',
            'messages' => $e->errors(),
        ], 422);

    } catch (\Exception $e) {
        // Catch general exceptions
        return response()->json([
            'error' => 'An unexpected error occurred.',
            'message' => $e->getMessage(),
        ], 500);
    }
}

    
    // Logout method
    public function logout(Request $request)
    {
        // Revoke the user's current access token
        $request->user()->currentAccessToken()->delete();

        // Return a success response
        return response()->json(['message' => 'Logged out successfully']);
    }
}
