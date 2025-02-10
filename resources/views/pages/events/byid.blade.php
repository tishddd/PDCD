@extends('pages.events.app')

@section('content')
<div class="event-details">
    <h1>Event Details</h1>
    <p><strong>Event ID:</strong> {{ request('event_id') }}</p>
    <p><strong>Event ID:</strong> {{ request('event_id') }}</p>
    <p><strong>Category:</strong> {{ request('event_category') }}</p>
    <p><strong>Title:</strong> {{ request('event_title') }}</p>
    <p><strong>Location:</strong> {{ request('event_location') }}</p>
    <p><strong>Venue:</strong> {{ request('event_venue') }}</p>
    <p><strong>Start Time:</strong> {{ request('event_start_time') }}</p>
    <p><strong>End Time:</strong> {{ request('event_end_time') }}</p>
    <p><strong>Status:</strong> {{ request('event_status') }}</p>
    <p><strong>Members Count:</strong> {{ request('members_count') }}</p>
    <p><strong>Created At:</strong> {{ request('created_at') }}</p>
</div>
@endsection
