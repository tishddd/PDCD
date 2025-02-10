<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SendMessageController;



//////////////////////////////////////////AUTH///////////////////////////
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

///////////////////////////*******EVENT*********//////////////////////

Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);
Route::put('/events/update/{id}', [EventController::class, 'update']);
Route::post('/events/update/new/{id}', [EventController::class, 'newUpdateMethod']);


Route::middleware('api')->group(function () {
    Route::post('/events', [EventController::class, 'createEvent']);
});
 // Create event
// Route::post('/events/{eventId}/members', [MemberController::class, 'addMember']); // Add members
// Route::post('/events/{eventId}/notify', [MemberController::class, 'sendNotifications']); // Send notifications
// Route::post('/events/authenticate', [MemberController::class, 'authenticateMember']); // Authenticate member

///////////////////////////*******MEMBER*********//////////////////////

// Routes for member actions
 Route::get('/members', [MemberController::class, 'index']);
 Route::post('/members', [MemberController::class, 'store']);
 Route::post('/members/sum/scans', [MemberController::class, 'sum']);
 Route::get('/members/{id}', [MemberController::class, 'show']); // Get member by ID
 Route::get('/event/{event_id}/members', [MemberController::class, 'showByEvent']); 
Route::put('/members/{id}', [MemberController::class, 'update']);
Route::delete('/members/{id}', [MemberController::class, 'remove']);


//---------------------------------------------------------upload excel  ------------------------------------------------------------
Route::post('/upload-excel', [EventController::class, 'uploadExcel']);

// ---------------------------------------------------------SEND MESSAGE ----------------------------------------------------
Route::post('/send-message/whatsapp', [SendMessageController::class, 'whatsappMethod']);
Route::post('/send-message/text', [SendMessageController::class, 'smsMethod']);
Route::post('/send-message/both', [SendMessageController::class, 'sendBoth']);
Route::post('/send-message/allmember', [SendMessageController::class, 'sendToAllMember']);
Route::post('/send-message/whatxapp', [SendMessageController::class, 'store']);






