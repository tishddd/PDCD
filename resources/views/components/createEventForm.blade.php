
<!-- Modal -->
<div id="eventModal" class="modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal()">&times;</span>

        <div class="form-container">
            <h2 style="
    color: #fff;
    background-color: #007BFF;
    padding: 10px;
    border-radius: 5px;
    text-align: center;
    font-size: 24px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    text-transform: uppercase;
    letter-spacing: 1px;">
    Event Registration
</h2>

            <form id="event-registration-form">
                <!-- Event Details Section -->
                <section>
                    <div class="grid-container">
                        <div class="grid-item">
                            <label for="event-code" class="white-label">Event Code:</label>
                            <input type="text" id="event-code" name="event_code" readonly required>
                        </div>

                        <div class="grid-item">
                            <label for="event-title" class="white-label">Event Title:</label>
                            <input type="text" id="event-title" name="event_title"  placeholder="Enter event title" required>
                        </div>

                        <div class="grid-item">
                            <label for="event-category" class="white-label">Event Category:</label>
                            <select id="event-category" name="event_category" required>
                                <option value="" disabled>Select category</option>
                                <option value="conference" selected>Conference</option>
                                <option value="webinar">Webinar</option>
                                <option value="workshop">Workshop</option>
                                <option value="wedding">Wedding</option>
                            </select>
                        </div>

                        <div class="grid-item">
                            <label for="event-location" class="white-label">Event Location:</label>
                            <input type="text" id="event-location" name="event_location"  placeholder="Enter location" required>
                        </div>

                        <div class="grid-item">
                            <label for="event-venue" class="white-label">Event Venue:</label>
                            <input type="text" id="event-venue" name="event_venue"  placeholder="Enter venue" required>
                        </div>

                        <div class="grid-item">
                            <label for="event-date" class="white-label">Event Date:</label>
                            <input type="date" id="event-date" name="event_date"  required>
                        </div>

                        <div class="grid-item">
                            <label for="event-start-time" class="white-label">Event Start Time:</label>
                            <input type="time" id="event-start-time" name="event_start_time"  required>
                        </div>

                        <div class="grid-item">
                            <label for="event-end-time" class="white-label">Event End Time:</label>
                            <input type="time" id="event-end-time" name="event_end_time"  required>
                        </div>
                    </div>
                </section>

                <!-- Event Description and Event Card Upload Section -->
                <section class="two-column-grid">
                    <div class="grid-item">
                        <h3>Event Description</h3>
                        <textarea id="event-description" name="event_description" placeholder="Write about the event" required>This is an amazing conference for developers.</textarea>
                    </div>

                    <div class="grid-item">
                        <h3>Event Card</h3>
                        <label for="event-card" class="white-label">Upload Event Card:</label>
                        <input type="file" id="event-card" name="event_card" accept="image/*">
                    </div>
                </section>

                <!-- Submit Button -->
                <button type="submit">Register Event</button>
            </form>

        </div>
    </div>
</div>
