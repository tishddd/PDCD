<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Table with Modal</title>
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    
    <link href="../../../assets/adminDashBord/css/allEvents.css" rel="stylesheet">
    <style>
.spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin: 0 auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
</head>
<body>
    <!-- Sidebar -->
    @include('components.newsidebar')

    <!-- Main Content Area -->
    <div class="main-content">
    <h1>Total Events :: 35</h1>
    <h1>Total Revenue :: 9,000,000</h1>

    <!-- Loading Spinner -->
    <div id="loading-spinner" style="display: none; text-align: center; padding: 20px;">
        <div class="spinner"></div>
        <p>Loading events...</p>
    </div>

    <!-- Error Message -->
    <div id="error-message" style="display: none; color: red; text-align: center; padding: 20px;">
        Failed to load events. Please try again later.
    </div>

    <!-- Event Table -->
    <div class="container">
        <table id="event-table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>ID#</th>
                    <th>Category</th>
                    <th>Title</th>
                    <th>Location</th>
                    <th>Venue</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Date</th>
                    <th>Code</th>
                    <th>Status</th>
                    <th>Members</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data rows will be appended here dynamically -->
            </tbody>
        </table>
    </div>
</div>

  <!-- Modal -->
  @include('components.openAllEventModal')

    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    <!-- ///////////////////////////////////////////////////Add new member //////////////////////////////// -->
    <script>
    const membersList = document.getElementById('event-members-list');

    document.getElementById('add-member-btn').addEventListener('click', function () {
        const memberName = document.getElementById('new-member-name').value.trim();

        if (memberName) {
            const listItem = document.createElement('li');
            listItem.textContent = memberName;
            membersList.appendChild(listItem);
            document.getElementById('new-member-name').value = ''; // Clear input
        } else {
            alert('Please enter a member name!');
        }
    });
</script>
<!-- ///////////////////////////////////////////////////add new member end //////////////////////////////////////////////////////////// -->

<!-- ////////////////////////////////////////////////fetchEvents ////////////////////////////////////////////////////////////////// -->
<script>
// Show loading spinner while fetching data
document.getElementById('loading-spinner').style.display = 'block';

// Fetch events from the API
fetch('http://localhost:8000/api/events')
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(events => {
        // Hide loading spinner
        document.getElementById('loading-spinner').style.display = 'none';

        // Sort events in descending order by created_at
        events.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

        const tableBody = document.querySelector('#event-table tbody');

        // Clear existing rows (if any)
        tableBody.innerHTML = '';

        // Populate the table with event data
        events.forEach(event => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${sanitizeHTML(event.event_id)}</td>
                <td>${sanitizeHTML(event.event_category)}</td>
                <td>${sanitizeHTML(event.event_title)}</td>
                <td>${sanitizeHTML(event.event_location)}</td>
                <td>${sanitizeHTML(event.event_venue)}</td>
                <td>${formatDateTime(event.event_start_time)}</td>
                <td>${formatDateTime(event.event_end_time)}</td>
                <td>${sanitizeHTML(event.event_date)}</td>
                <td>${sanitizeHTML(event.event_code)}</td>
                <td class="event-status ${getEventStatusClass(event.event_status)}">${sanitizeHTML(event.event_status)}</td>
                <td class="event-members">
                    ${sanitizeHTML(event.members_count)}
                    <button class="add-member-btn" onclick="addMember(${event.event_id}, event)">Add Member</button>
                </td>
                <td>${formatDateTime(event.created_at)}</td>
            `;

            // Navigate to event details page on row click
            row.onclick = () => navigateToEventPage(event);
            tableBody.appendChild(row);
        });

        // Initialize DataTable with sorting on the "Created At" column (index 11)
        $('#event-table').DataTable({
            order: [[11, 'desc']], // Sort by the last column (created_at) in descending order
            paging: true, // Enable pagination
            pageLength: 10, // Show 10 rows per page
            searching: true, // Enable search functionality
            responsive: true // Make the table responsive
        });
    })
    .catch(error => {
        console.error('Error fetching events:', error);
        document.getElementById('loading-spinner').style.display = 'none';
        document.getElementById('error-message').style.display = 'block';
    });

// Function to sanitize HTML and prevent XSS attacks
function sanitizeHTML(str) {
    const temp = document.createElement('div');
    temp.textContent = str;
    return temp.innerHTML;
}

// Function to format date and time
function formatDateTime(dateTimeString) {
    const date = new Date(dateTimeString);
    return date.toLocaleString(); // Adjust format as needed
}

// Function to get CSS class for event status
function getEventStatusClass(status) {
    switch (status) {
        case 'Upcoming': return 'upcoming';
        case 'Ongoing': return 'ongoing';
        case 'Completed': return 'completed';
        default: return '';
    }
}

// Function to handle adding a member
function addMember(eventId, event) {
    event.stopPropagation(); // Prevent row click event
    const memberName = prompt('Enter the name of the member to add:');
    if (memberName) {
        // Call API to add member (example)
        fetch(`http://localhost:8000/api/events/${eventId}/add-member`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ memberName }),
        })
            .then(response => response.json())
            .then(data => {
                alert(`Member "${memberName}" added successfully to Event ID: ${eventId}`);
                location.reload(); // Refresh the page to reflect changes
            })
            .catch(error => {
                console.error('Error adding member:', error);
                alert('Failed to add member. Please try again.');
            });
    }
}

// Function to navigate to the event details page
function navigateToEventPage(event) {
    const params = new URLSearchParams({
        event_id: event.event_id,
        event_category: event.event_category,
        event_title: event.event_title,
        event_location: event.event_location,
        event_venue: event.event_venue,
        event_start_time: event.event_start_time,
        event_end_time: event.event_end_time,
        event_date: event.event_date,
        event_code: event.event_code,
        event_status: event.event_status,
        created_at: event.created_at
    });

    // Redirect to Laravel route
    window.location.href = `/eventByid?${params.toString()}`;
}
</script>

</body>
</html>
