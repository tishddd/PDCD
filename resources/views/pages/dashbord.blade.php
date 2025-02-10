<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home page</title>

    <!-- CSS Files -->
    <link href="../../../assets/adminDashBord/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="../../../assets/adminDashBord/css/style.css" rel="stylesheet" type="text/css">
    <link href="../../../assets/adminDashBord/css/font-awesome.css" rel="stylesheet">
    <link href="../../../assets/adminDashBord/css/new_dashbord.css" rel="stylesheet">
	<link href="../../../assets/adminDashBord/css/new-page-container.css" rel="stylesheet">
    <link href='//fonts.googleapis.com/css?family=Carrois+Gothic|Work+Sans:400,500,600' rel='stylesheet'>

    <!-- Modernizr for geo chart -->
    <script src="//cdn.jsdelivr.net/modernizr/2.8.3/modernizr.min.js"></script>
</head>
<body>
    <div class="newpage-conatiner">

        <div class="left-content">
            <div class="mother-grid-inner">
                <!-- Header -->
                @include('components.header')

                <!-- Event Creation Button and Form -->
                <button class="styled-button" onclick="openModal()">Create Event Here</button>
				@include('components.createEventForm')
                <!-- Event Cards and Status Table -->
                @include('components.eventCards')
                @include('components.eventStatusTable')
            </div>
        </div>

        <!-- Sidebar Menu -->
        @include('components.aside')
    </div>



    <script>
// Function to open the modal and set the event code dynamically
function openModal() {
    const modal = document.getElementById('eventModal');
    const eventCodeInput = document.getElementById('event-code');
    eventCodeInput.value = generateEventCode(); // Set the generated code
    modal.style.display = 'flex'; // Show the modal
}

// Function to close the modal
function closeModal() {
    const modal = document.getElementById('eventModal');
    modal.style.display = 'none'; // Hide the modal
}

// Function to generate a unique event code with two letters and two numbers
function generateEventCode() {
    const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    const randomLetters = letters.charAt(Math.floor(Math.random() * letters.length)) + 
                          letters.charAt(Math.floor(Math.random() * letters.length));
    const randomNumbers = Math.floor(10 + Math.random() * 90); // Two-digit number
    return `${randomLetters}${randomNumbers}`;
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('eventModal');
    if (event.target === modal) {
        closeModal();
    }
};
</script>

<!-- //////////////////////////////////////////////////////////NEW EVENT FORM ////////////////////////////////////////////////// -->
<script>
    document.getElementById('event-registration-form').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent the form from submitting normally

        // Collect form data
        const formData = new FormData();
        formData.append('event_code', document.getElementById('event-code').value);
        formData.append('event_title', document.getElementById('event-title').value);
        formData.append('event_category', document.getElementById('event-category').value);
        formData.append('event_location', document.getElementById('event-location').value);
        formData.append('event_venue', document.getElementById('event-venue').value);

        // Format the date and datetime fields
        const eventDate = document.getElementById('event-date').value;
        const startTime = document.getElementById('event-start-time').value;
        const endTime = document.getElementById('event-end-time').value;

        // Combine date and time for datetime fields
        const formattedStartTime = `${eventDate} ${startTime}`;
        const formattedEndTime = `${eventDate} ${endTime}`;

        formData.append('event_date', eventDate); // Ensure `YYYY-MM-DD`
        formData.append('event_start_time', formattedStartTime); // Ensure `YYYY-MM-DD HH:MM:SS`
        formData.append('event_end_time', formattedEndTime); // Ensure `YYYY-MM-DD HH:MM:SS`

        formData.append('event_description', document.getElementById('event-description').value);

        const eventCard = document.getElementById('event-card').files[0];
        if (eventCard) {
            formData.append('event_card', eventCard);
        }

        // Make AJAX request
        fetch('http://127.0.0.1:8000/api/events', {
            method: 'POST',
            body: formData,
        })
            .then(response => {
                if (!response.ok) {
                    // Extract and log the full response details for debugging
                    return response.json().then(errorData => {
                        console.error('Full Error Response:', errorData);
                        throw new Error(`Server Error: ${response.status} - ${errorData.message || 'Unknown error'}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                // Handle success response
                console.log('Server Response:', data); // Log the server response for debugging
                alert('Event registered successfully!');

                // Clear the form
                document.getElementById('event-registration-form').reset();

                // Close the modal
                closeModal(); 
            })
            .catch(error => {
                // Handle error response
                console.error('Error:', error);
                alert(`An error occurred while registering the event: ${error.message}`);
            });
    });
</script>

<!-- //////////////////////////////////////////////////////END///////////////////////////////////////////////////////////////// -->

    <!-- External JS -->
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/bootstrap.js"></script>
</body>
</html>
