<div class="col-md-6">
                <div class="card">
                    <div class="card-header">E</div>
                    <form method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="event_title">Title</label>
                            <input type="text" id="event_title" name="event_title" value="{{ $event['event_title'] }}">
                        </div> 
                        <div class="form-group">
                            <label for="event_code">Event Code</label>
                            <input type="text" id="event_code" name="event_code" value="{{ $event['event_code'] }}">
                        </div>

                        <div class="form-group">
                            <label for="event_category">Category</label>
                            <input type="text" id="event_category" name="event_category" value="{{ $event['event_category'] }}">
                        </div>

                        <div class="form-group">
                            <label for="event_location">Location</label>
                            <input type="text" id="event_location" name="event_location" value="{{ $event['event_location'] }}">
                        </div>

                        <div class="form-group">
                            <label for="event_venue">Venue</label>
                            <input type="text" id="event_venue" name="event_venue" value="{{ $event['event_venue'] }}">
                        </div>

                        <div class="form-group">
                            <label for="event_start_time">Start Time</label>
                            <input type="datetime-local" id="event_start_time" name="event_start_time" value="{{ $event['event_start_time'] }}">
                        </div>

                        <div class="form-group">
                            <label for="event_end_time">End Time</label>
                            <input type="datetime-local" id="event_end_time" name="event_end_time" value="{{ $event['event_end_time'] }}">
                        </div>

                        <div class="form-group">
                            <label for="event_status">Status</label>
                            <select id="event_status" name="event_status">
                                <option value="Upcoming" {{ $event['event_status'] == 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="Ongoing" {{ $event['event_status'] == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="Completed" {{ $event['event_status'] == 'Completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>