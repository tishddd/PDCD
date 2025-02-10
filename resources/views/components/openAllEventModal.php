<div id="event-modal" class="modal">
    <div class="modal-content">
        <!-- Event Details Section -->
        <form id="event-form" class="event-details-grid">
    <div>
        <label for="event-code"><strong>Event Code:</strong></label>
        <input type="text" id="event-code" name="eventCode" readonly />
    </div>
    <div>
        <label for="event-title"><strong>Title:</strong></label>
        <input type="text" id="event-title" name="eventTitle" />
    </div>
    <div>
        <label for="event-category"><strong>Category:</strong></label>
        <input type="text" id="event-category" name="eventCategory" />
    </div>
    <div>
        <label for="event-location"><strong>Location:</strong></label>
        <input type="text" id="event-location" name="eventLocation" />
    </div>
    <div>
        <label for="event-venue"><strong>Venue:</strong></label>
        <input type="text" id="event-venue" name="eventVenue" />
    </div>
    <div>
        <label for="event-start-time"><strong>Start Time:</strong></label>
        <input type="datetime-local" id="event-start-time" name="eventStartTime" />
    </div>
    <div>
        <label for="event-end-time"><strong>End Time:</strong></label>
        <input type="datetime-local" id="event-end-time" name="eventEndTime" />
    </div>
    <div>
        <label for="event-status"><strong>Status:</strong></label>
        <select id="event-status" name="eventStatus">
            <option value="Scheduled">Scheduled</option>
            <option value="Ongoing">Ongoing</option>
            <option value="Completed">Completed</option>
        </select>
    </div>
    <div>
        <label for="event-members"><strong>Members:</strong></label>
        <input type="number" id="event-members" name="eventMembers"  />
    </div>
</form>

        <!-- Additional Event Information -->
        <div>
            <p><strong>Created At:</strong> <span id="event-created-at"></span></p>
        </div>

        <!-- Members List Section -->
        <h3>Members</h3>
        <ul id="event-members-list" class="members-list">
            <!-- Dynamically add members here -->
        </ul>

        <!-- Add Member Section -->
        <div class="add-member-section">
            <label for="new-member-name">Add New Member:</label>
            <input type="text" id="new-member-name" placeholder="Enter member name" class="member-input" />
            <button id="add-member-btn" class="add-member-btn">Add Member</button>
        </div>

        <!-- Close Button -->
        <div class="modal-actions">
            <button onclick="closeModal()" class="close-modal-btn">Close</button>
        </div>
    </div>
</div>
