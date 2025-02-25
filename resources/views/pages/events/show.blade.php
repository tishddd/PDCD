<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <link href="../../../assets/adminDashBord/css/showById.css" rel="stylesheet">
    <title>Event Details and Users</title>
</head>

<body>
    <div class="card-header">Event Details</div>
    <div class="container">
        <div class="row">
            <!-- Event Details Form -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Event Code=> {{ $event['event_code'] }}</div>
                    <form id="updateEventForm">
                        @csrf
                        <input type="hidden" id="event_id" value="{{ $event['event_id'] }}">

                        <div>
                            <label for="event_title">Title</label>
                            <input type="text" id="event_title" name="event_title" value="{{ $event['event_title'] }}" required>
                        </div>

                        <div>
                            <label for="event_category">Category</label>
                            <input type="text" id="event_category" name="event_category" value="{{ $event['event_category'] }}">
                        </div>

                        <div>
                            <label for="event_location">Location</label>
                            <input type="text" id="event_location" name="event_location" value="{{ $event['event_location'] }}">
                        </div>

                        <div>
                            <label for="event_venue">Venue</label>
                            <input type="text" id="event_venue" name="event_venue" value="{{ $event['event_venue'] }}">
                        </div>

                        <div>
                            <label for="event_start_time">Start Time</label>
                            <input type="datetime-local" id="event_start_time" name="event_start_time" value="{{ $event['event_start_time'] }}" required>
                        </div>

                        <div>
                            <label for="event_end_time">End Time</label>
                            <input type="datetime-local" id="event_end_time" name="event_end_time" value="{{ $event['event_end_time'] }}" required>
                        </div>

                        <div>
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
            <!-- User List -->
            @include('components.showById.userlist')


            <!-- Add User Modal -->
            @include('components.showById.addUserModal')

            <!-- Add User Modal -->
            @include('components.showById.edityUserModal')

            <!-- Modal Add Excel -->
            @include('components.showById.addExeclModal')

            <!-- Modal Send Message -->
            @include('components.showById.sendMessageModal')

            <!-- Preview Document -->
            <!-- <div class="col-md-12" style="width: 25%; height: 650px; border: 2px solid green; overflow: hidden; text-align: center; padding: 10px;">
                <h3 style="margin-bottom: 15px;">Preview Document</h3>
                <img
                    id="event_card_img"
                    src=""
                    style="width: 100%; height: 80%; object-fit: cover; margin-bottom: 10px;"
                    alt="Event Card">
                <button
                    onclick="changeCard()"
                    style="width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
                    Change Card
                </button>
            </div> -->
            <div id="event_card_container" style="position: relative; border: 2px solid green; overflow: hidden; text-align: center; padding: 10px;">
                <img id="event_card_img" src="" alt="Event Card" style="width: 100%; max-width: 600px;">
            </div>

            <div id="qrCodeContainer">
                <img id="qrCodeImage" src="" alt="QR Code" style="display: none; width: 200px; height: 200px;">
                <button id="downloadQrCode" style="display: none;">Download QR Code</button>
            </div>



        </div>
    </div>




    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        function sendInvitation(email) {
            // You can replace this with your logic for sending an invitation, like making an API call.
            alert('Invitation sent to ' + email);
        }
    </script>

    <script>
        document.getElementById('downloadQrCode').addEventListener('click', function() {
            const qrCodeImage = document.getElementById('qrCodeImage');
            const link = document.createElement('a');
            link.href = qrCodeImage.src;
            link.download = 'qr-code.png';
            link.click();
        });

        // Show the download button when the QR code is displayed
        const downloadButton = document.getElementById('downloadQrCode');
        downloadButton.style.display = 'block';
    </script>


    <!----------------------------------------------------Fetch Event------------------------------------------------------------------>
    <script>
        // Wait for the DOM content to be fully loaded before executing the script
        document.addEventListener("DOMContentLoaded", function() {
            // Extract URL query parameters using URLSearchParams
            const urlParams = new URLSearchParams(window.location.search);

            // Collect relevant event data from the URL query parameters
            const eventData = {
                event_id: urlParams.get("event_id"),
                event_code: urlParams.get("event_code"), // Retrieve the event ID
                event_category: urlParams.get("event_category"), // Retrieve the event category
                event_title: urlParams.get("event_title"), // Retrieve the event title
                event_location: urlParams.get("event_location"), // Retrieve the event location
                event_venue: urlParams.get("event_venue"), // Retrieve the event venue
                event_start_time: urlParams.get("event_start_time"), // Retrieve the event start time
                event_end_time: urlParams.get("event_end_time"), // Retrieve the event end time
                event_status: urlParams.get("event_status"), // Retrieve the event status
                created_at: urlParams.get("created_at"), // Retrieve the creation timestamp
            };

            // Populate HTML input fields with the extracted query parameter data
            if (eventData.event_title) {
                document.getElementById("event_title").value = eventData.event_title; // Set the event title
                document.getElementById("event_code").value = eventData.event_code;
                document.getElementById("event_category").value = eventData.event_category; // Set the event category
                document.getElementById("event_location").value = eventData.event_location; // Set the event location
                document.getElementById("event_venue").value = eventData.event_venue; // Set the event venue
                document.getElementById("event_start_time").value = eventData.event_start_time; // Set the event start time
                document.getElementById("event_end_time").value = eventData.event_end_time; // Set the event end time
                document.getElementById("event_status").value = eventData.event_status; // Set the event status
            }

            // Log the extracted query parameter data to the console for debugging
            // console.log("Query Params Data:", eventData);

            // Define the API endpoint URL using the event ID from query parameters
            const apiUrl = `http://127.0.0.1:8000/api/events/${eventData.event_id}`;

            // Perform an AJAX request to fetch event details from the API
            $.ajax({
                url: apiUrl, // API endpoint URL
                type: "GET", // Use the GET method to retrieve data
                dataType: "json", // Specify that the response will be in JSON format
                success: function(data) {
                    // Log the data retrieved from the API to the console
                    console.log("Data retrieved:", data);
                },
                error: function(xhr, status, error) {
                    // Log any errors encountered during the request to the console
                    console.error("Error fetching data:", error);
                }
            });
        });
    </script>

    <!-- ------------------------------------------------end fetch Event-------------------------------------------------------------- -->

    <!-- ------------------------------------------------------------retreave card from event data----------------------------------------------- -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const eventData = {
                event_id: urlParams.get("event_id"),
            };

            console.log("Query Params Data:", eventData);

            const apiUrl = `http://127.0.0.1:8000/api/events/${eventData.event_id}`;

            $.ajax({
                url: apiUrl,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log("Data retrieved:", data);

                    // Store event card data in a global variable
                    window.eventCardData = data;

                    // Dynamically set the src attribute of the <img> tag
                    const eventCardImg = document.querySelector("#event_card_img");
                    eventCardImg.src = `http://127.0.0.1:8000/${data.event_card}`;
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
        });
    </script>
    <!-- <script>
        // Wait for the DOM content to be fully loaded before executing the script
        document.addEventListener("DOMContentLoaded", function() {
            // Extract URL query parameters using URLSearchParams
            const urlParams = new URLSearchParams(window.location.search);

            // Collect relevant event data from the URL query parameters
            const eventData = {
                event_id: urlParams.get("event_id"),
            };

            // Log the extracted query parameter data to the console for debugging
            console.log("Query Params Data:", eventData);

            // Define the API endpoint URL using the event ID from query parameters
            const apiUrl = `http://127.0.0.1:8000/api/events/${eventData.event_id}`;

            // Perform an AJAX request to fetch event details from the API
            $.ajax({
                url: apiUrl, // API endpoint URL
                type: "GET", // Use the GET method to retrieve data
                dataType: "json", // Specify that the response will be in JSON format
                success: function(data) {
                    // Log the data retrieved from the API to the console
                    console.log("Data retrieved:", data);

                    // Get the event_card URL from the API response
                    const eventCardUrl = `http://127.0.0.1:8000/${data.event_card}`;

                    // Dynamically set the src attribute of the <img> tag
                    const eventCardImg = document.querySelector("#event_card_img");
                    eventCardImg.src = eventCardUrl;

          
                },
                error: function(xhr, status, error) {
                    // Log any errors encountered during the request to the console
                    console.error("Error fetching data:", error);
                }
            });
        });

        // Function to fetch the QR code and overlay it on the event card
        // function fetchQRCode(eventId, eventCardImg) {
        //     // Define the API endpoint URL for the QR code
        //     const qrCodeApiUrl = `http://127.0.0.1:8000/api/send-message/whatsapp`;

        //     // Perform an AJAX request to fetch the QR code
        //     $.ajax({
        //         url: qrCodeApiUrl,
        //         type: "POST",
        //         dataType: "json",
        //         data: {
        //             event_id: eventId,
        //             passcode: "123456" // Replace with the actual passcode or fetch it dynamically
        //         },
        //         success: function(qrCodeData) {
        //             // Log the QR code data retrieved from the API to the console
        //             console.log("QR Code Data:", qrCodeData);

        //             // Create a new image element for the QR code
        //             const qrCodeImg = document.createElement("img");
        //             qrCodeImg.src = qrCodeData.response; // Set the Base64-encoded image as the src
        //             qrCodeImg.style.position = "absolute";
        //             qrCodeImg.style.bottom = "10px"; // Position at the bottom
        //             qrCodeImg.style.left = "10px"; // Position at the left
        //             qrCodeImg.style.width = "100px"; // Set the size of the QR code
        //             qrCodeImg.style.height = "100px";

        //             // Append the QR code image to the event card container
        //             const eventCardContainer = document.querySelector("#event_card_container");
        //             eventCardContainer.style.position = "relative"; // Ensure the container is positioned
        //             eventCardContainer.appendChild(qrCodeImg);
        //         },
        //         error: function(xhr, status, error) {
        //             // Log any errors encountered during the request to the console
        //             console.error("Error fetching QR code:", error);
        //         }
        //     });
       // }
    </script> -->
    <!-- ----------------------------------------------------------------en of retreave card from event data -------------------------------------------- -->


    <!-- -------------------------------------------------upload exel ------------------------------------------------------------------ -->
    <script>
        async function submitExcel() {
            // Retrieve the event_id from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const eventId = urlParams.get('event_id');
            console.log(eventId); // Check if this prints the correct event_id

            // Check if the event_id exists
            if (!eventId) {
                alert("Event ID not found.");
                return;
            }

            const excelInput = document.getElementById("excel_file");

            // Check if a file is selected
            if (!excelInput.files || excelInput.files.length === 0) {
                alert("Please select an Excel file to upload.");
                return;
            }

            const formData = new FormData();
            formData.append("excel_file", excelInput.files[0]);
            formData.append("event_id", eventId); // Append the event_id to the form data

            try {
                // Send the file to the API
                const response = await fetch("http://127.0.0.1:8000/api/upload-excel", {
                    method: "POST",
                    body: formData,
                });

                const result = await response.json();

                // Check if the response is successful
                if (response.ok && result.success) {
                    console.log("File uploaded successfully.");
                    alert("File processed and members inserted successfully.");

                    // Refresh the page after a successful upload
                    location.reload(); // This will reload the current page
                } else {
                    console.error("Error uploading file:", result.message || "Unknown error.");
                    alert(result.message || "Error uploading file.");
                }
            } catch (error) {
                console.error("Error communicating with the server:", error.message);
                alert("An error occurred while communicating with the server.");
            }
        }

        function closeAddExelModal() {
            const modal = document.getElementById("addExel");
            modal.style.display = "none";
        }
    </script>

    <!-- //////////////////////////////////////////end Submit/////////////////////////////////////////////////////////////////// -->



    <!-- //////////////////////////////////////////////////start change card////////////////////////////////////////////////////////////////////////////// -->
    <script>
        function changeCard() {
            alert('Change Card functionality goes here!');
            // Add logic here to handle changing the card, e.g., opening a file picker or navigating to another page.
        }
    </script>

    <!-- //////////////////////////////////////////////////change card end////////////////////////////////////////////////////////////////////////////// -->

    <!-- JavaScript -->
    <script>
        function openAddUserModal() {
            document.getElementById('addUserModal').classList.add('active');
        }

        function openAddExelModal() {
            document.getElementById('addExel').classList.add('active');
        }



        function closeAddUserModal() {
            document.getElementById('addUserModal').classList.remove('active');
        }

        function closeAddExelModal() {
            document.getElementById('addExel').classList.remove('active');
        }

        // Close the modal
        function closeEditUserModal() {
            const editModal = document.getElementById('edityUserModal');
            if (editModal) {
                // Hide the modal by setting display to 'none'
                editModal.style.display = 'none';
            }
        }
    </script>
    <!-- ///////////////////////////////////////modal////////////////////////////////////////////// -->

    |
    <!-- ---------------------------------------fetch Event members method-------------------------------------------------->


    <script>
        $(document).ready(function() {
            const API_BASE_URL = 'http://localhost:8000/api';

            // Retrieve the event_id from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const eventId = urlParams.get('event_id');

            if (eventId) {
                fetchUserList(eventId);
            } else {
                alert('Event ID not found.');
            }

            // Fetch user list and populate table
            async function fetchUserList(eventId) {
                const tableBody = $('#userTable tbody');

                // Show loading message
                tableBody.html('<tr><td colspan="6">Loading...</td></tr>');

                try {
                    // Fetch members from API
                    const response = await fetch(`${API_BASE_URL}/event/${eventId}/members`);

                    if (!response.ok) {
                        throw new Error('Failed to fetch members');
                    }

                    const data = await response.json();
                    const members = Array.isArray(data) ? data : data.data;

                    if (members && members.length > 0) {
                        tableBody.empty();

                        // Populate table with member data
                        members.forEach((member) => {
                            tableBody.append(generateMemberRow(member));
                        });

                        // Initialize DataTable (destroy if already initialized)
                        if ($.fn.DataTable.isDataTable('#userTable')) {
                            $('#userTable').DataTable().destroy();
                        }
                        $('#userTable').DataTable();
                    } else {
                        tableBody.html('<tr><td colspan="6">No members found.</td></tr>');
                    }
                } catch (error) {
                    console.error('Error fetching members:', error);
                    tableBody.html('<tr><td colspan="6">Failed to load members.</td></tr>');
                }
            }

            // Generate a table row for a member
            function generateMemberRow(member) {
                return `
        <tr>
            <td>${member.member_id || 'N/A'}</td>
            <td>${member.member_name || 'N/A'}</td>
            <td>${member.status || 'N/A'}</td>
            <td>${member.total || '0'}</td>
            <td>${member.member_phone || 'N/A'}</td>
             <td>${member.scans || 'N/A'}</td>
             <td>${member.scans || 'N/A'}</td>
             <td>${member.scans || 'N/A'}</td>
              <td>${member.pass_code || 'N/A'}</td>
            <td>
                <button class="btn btn-custom-edit btn-sm" onclick="openEditUserModal(${member.member_id}, '${member.member_name}', '${member.member_phone}', '${member.status}', '${member.total}')">Edit</button>
                <button class="btn btn-success" onclick="openSendMessageModal(${member.member_id}, '${member.member_name}', '${member.member_phone}', '${member.rsvp_status}', '${member.total}','${member.pass_code}')">Invite</button>
                <button class="btn btn-danger btn-sm" onclick="openSendMessageModal(${member.member_id}, '${member.member_name}', '${member.member_phone}', '${member.rsvp_status}', '${member.total}','${member.pass_code}' )">call</button>
            </td>
        </tr>
    `;
            }


            window.openEditUserModal = async function(id, memberName, memberPhone, status, total) {
                try {
                    const response = await fetch(`${API_BASE_URL}/members/${id}`);

                    if (!response.ok) {
                        throw new Error('Failed to fetch member details');
                    }

                    const userData = await response.json();
                    console.log("Fetched User Data:", userData);

                    // Populate modal fields
                    document.getElementById('id').value = id;
                    document.getElementById('memberName').value = memberName;
                    document.getElementById('memberPhone').value = memberPhone;
                    document.getElementById('status').value = status;
                    document.getElementById('total').value = total;

                    // Open the modal using plain JavaScript
                    const editModal = document.getElementById('edityUserModal');
                    if (editModal) {
                        editModal.style.display = 'block';
                    }
                } catch (error) {
                    console.error('Error fetching member data:', error);
                    alert(`Failed to load member details for Member ID: ${id}`);
                }
            };


            window.openSendMessageModal = async function(id, memberName, memberPhone, status, total, passCode) {
                try {
                    const response = await fetch(`${API_BASE_URL}/members/${id}`);

                    if (!response.ok) {
                        throw new Error('Failed to fetch member details');
                    }

                    const userData = await response.json();
                    console.log("Fetched User Data:", userData);
                    console.log(passCode);

                    // Populate modal fields
                    document.getElementById('id').value = id;
                    document.getElementById('memberName').value = memberName;
                    document.getElementById('memberPhone').value = memberPhone;
                    document.getElementById('status').value = status;
                    document.getElementById('total').value = total;
                    document.getElementById('passCode').value = passCode;

                    // Open the modal
                    const editModal = document.getElementById('sendMessageModalOpen');
                    if (editModal) {
                        editModal.style.display = 'block';
                    }
                } catch (error) {
                    console.error('Error fetching member data:', error);
                    alert(`Failed to load member details for Member passCode: ${passCode}`);
                }
            };



            // Close modal function (moved outside so it's globally accessible)
            window.closeMessageModal = function() {
                const modal = document.getElementById('sendMessageModalOpen');
                modal.style.display = 'none';
            };



            // ------------------------whatsapp---------------------------------

            window.sendMessageViaWhatsapp = async function() {
                try {
                    const event_id = eventId;
                    const id = document.getElementById('id').value.trim();
                    const passcode = document.getElementById('passCode').value.trim();
                    const memberName = document.getElementById('memberName').value.trim();
                    const memberPhone = document.getElementById('memberPhone').value.trim();
                    const status = document.getElementById('status').value.trim();
                    const total = document.getElementById('total').value.trim();

                    if (!event_id || !id || !passcode || !memberName || !memberPhone || !status || !total) {
                        alert("⚠️ Please fill in all required fields before sending.");
                        return;
                    }

                    const formattedPhone = memberPhone.startsWith('0') ? memberPhone : `0${memberPhone}`;

                    // ✅ Extract only the event card filename (Remove full URL)
                    let eventCardUrl = window.eventCardData ? window.eventCardData.event_card : null;
                    if (eventCardUrl) {
                        eventCardUrl = eventCardUrl.split('/').pop(); // Get only filename (e.g., "1738783082-card.jpg")
                    }

                    console.log("Formatted Event Card Name:", eventCardUrl);

                    const messageData = {
                        event_id: event_id,
                        id: id,
                        name: memberName,
                        phone: formattedPhone,
                        status: status,
                        total: total,
                        passcode: passcode,
                        event_card: eventCardUrl, // Store only the filename
                    };

                    const response = await fetch('http://localhost:8000/api/send-message/whatsapp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(messageData),
                    });

                    const result = await response.json();
                    console.log("API Response:", result);

                    if (response.ok) {
                        const qrCodeImage = document.getElementById('qrCodeImage');

                        // Use the stored QR image path from Laravel
                        if (result.qr_card_path) {
                            qrCodeImage.src = result.qr_card_path; // Set image from storage
                        } else {
                            qrCodeImage.src = result.response; // Fallback to Base64 if needed
                        }

                        qrCodeImage.style.display = 'block';
                        alert(`✅ ${result.message}`);
                    } else {
                        alert(`❌ Error: ${result.message || 'Something went wrong.'}`);
                    }
                } catch (error) {
                    console.error('❌ Error sending WhatsApp message:', error);
                    alert('⚠️ An unexpected error occurred while sending the WhatsApp message.');
                }
            };

            // ==================================end via whatsapp=================================

            // -----------------------------------------------sms--------------------------------------

            window.sendMessageViaSmS = async function() {

                const event_id = eventId;
                const id = document.getElementById('id').value;
                const passcode = document.getElementById('passCode').value;
                const memberName = document.getElementById('memberName').value;
                const memberPhone = document.getElementById('memberPhone').value;
                const status = document.getElementById('status').value;
                const total = document.getElementById('total').value;


                console.log(passcode)


                const formattedPhone = memberPhone.startsWith('0') ? memberPhone : `0${memberPhone}`;

                const messageData = {
                    event_id: event_id,
                    id: id,
                    name: memberName,
                    phone: formattedPhone,
                    status: status,
                    total: total,
                    passcode: passcode,
                };

                console.log("Sending SMS with data:", messageData);

                try {
                    const response = await fetch('/api/send-message/text', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(messageData),
                    });

                    const result = await response.json();
                    console.log("Server Response:", result); // Log the full response

                    if (response.ok) {
                        alert(`✅ ${result.message}\n📩 SMS Response: ${JSON.stringify(result.response)}`);
                    } else {
                        alert(`❌ Error: ${result.message}`);
                    }
                } catch (error) {
                    console.error('Error sending message:', error);
                    alert('An error occurred while sending the message.');
                }
            };


            // ====================================end via sms ============================================


            // ----------------------------------- send-both -------------------------------------

            window.sendBoth = async function() {
                const id = document.getElementById('id').value;
                const memberName = document.getElementById('memberName').value;
                const memberPhone = document.getElementById('memberPhone').value;
                const status = document.getElementById('status').value;
                const total = document.getElementById('total').value;

                const formattedPhone = memberPhone.startsWith('+') ? memberPhone : `+${memberPhone}`;

                const messageData = {
                    id: id,
                    name: memberName,
                    phone: formattedPhone,
                    status: status,
                    total: total,
                };

                try {
                    const response = await fetch('http://localhost:8000/api/send-message/both', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(messageData),
                    });

                    const result = await response.json();

                    alert(result.message); // Display the response message
                } catch (error) {
                    console.error('Error sending message:', error);
                    alert('An error occurred while sending the message.');
                }
            };

            window.sendToAllMember = async function() {

                const id = document.getElementById('id')?.value || null;
                const memberName = document.getElementById('memberName')?.value || null;
                const memberPhone = document.getElementById('memberPhone')?.value || null;
                const status = document.getElementById('status')?.value || null;
                const total = document.getElementById('total')?.value || null;

                const eventId = eventId;
                const eventCategory = document.getElementById('eventCategory')?.value || null;
                const eventTitle = document.getElementById('eventTitle')?.value || null;
                const eventDateTime = document.getElementById('eventDateTime')?.value || null;
                const eventVenue = document.getElementById('eventVenue')?.value || null;

                console.log({
                    id,
                    memberName,
                    memberPhone,
                    status,
                    total,
                    eventId,
                    eventCategory,
                    eventTitle,
                    eventDateTime,
                    eventVenue
                });

                if (!id || !memberName || !memberPhone || !eventId || !eventCategory || !eventTitle || !eventDateTime || !eventVenue) {
                    alert("Some fields are missing. Check the console for details.");
                    return;
                }

                const formattedPhone = memberPhone.startsWith('+') ? memberPhone : `+${memberPhone}`;

                const messageData = {
                    id: id,
                    name: memberName,
                    phone: formattedPhone,
                    status: status,
                    total: total,
                    event: {
                        id: eventId,
                        category: eventCategory,
                        title: eventTitle,
                        dateTime: eventDateTime,
                        venue: eventVenue
                    }
                };

                try {
                    const response = await fetch('http://localhost:8000/api/send-message/allmember', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(messageData),
                    });

                    const result = await response.json();
                    alert(result.message);
                } catch (error) {
                    console.error('Error sending message:', error);
                    alert('An error occurred while sending the message.');
                }
            };




            // window.sendToAllMember = async function () {
            // const id = document.getElementById('id').value;
            // const memberName = document.getElementById('memberName').value;
            // const memberPhone = document.getElementById('memberPhone').value;
            // const status = document.getElementById('status').value;
            // const total = document.getElementById('total').value;

            // const formattedPhone = memberPhone.startsWith('+') ? memberPhone : `+${memberPhone}`;

            // const messageData = {
            // id: id,
            // name: memberName,
            // phone: formattedPhone,
            // status: status,
            // total: total,
            // };

            // try {
            // const response = await fetch('http://localhost:8000/api/send-message/allmember', {
            // method: 'POST',
            // headers: {
            // 'Content-Type': 'application/json',
            // },
            // body: JSON.stringify(messageData),
            // });

            // const result = await response.json();

            // alert(result.message); // Display the response message
            // } catch (error) {
            // console.error('Error sending message:', error);
            // alert('An error occurred while sending the message.');
            // }
            // };


            window.inviteMember = function(id, memberName, memberPhone, status, total) {
                // Ensure the phone number starts with '+'
                const formattedPhone = memberPhone.startsWith('+') ? memberPhone : `+${memberPhone}`;

                // Prepare the message
                const messageCaption = `Code: ${id}, Name: ${memberName}, Phone: ${formattedPhone}, Status: ${status}, Total: ${total}`;

                // Generate QR code with member details
                const qrData = `Card Number: ${id}, Name: ${memberName}, Phone: ${formattedPhone}, Status: ${status}, Total: ${total}`;

                // Clear the QR code container before adding a new QR code
                const qrCodeContainer = document.getElementById('qrCodeContainer');
                qrCodeContainer.innerHTML = ''; // Clear previous QR codes if any

                // Create a QR code with member details
                const qrCode = new QRCode(qrCodeContainer, {
                    text: qrData,
                    width: 128,
                    height: 128,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });

                // UltraMsg API Configuration
                const instanceId = 'instance104695';
                const token = 'm8ei2zpoj5j755ib';
                const apiUrl = `https://api.ultramsg.com/${instanceId}/messages/image`;

                // Image URL
                const imageUrl = "https://file-example.s3-accelerate.amazonaws.com/images/test.jpg"; // Replace with your image URL

                // WhatsApp message payload
                const payload = {
                    token: token,
                    to: `${formattedPhone.replace('+', '')}@c.us`, // Format required by UltraMsg API
                    image: imageUrl, // Image URL
                    caption: messageCaption // Caption for the image
                };

                // Send the message via UltraMsg API
                fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(payload),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.sent === "true") {
                            alert('Message sent successfully!');
                        } else {
                            alert(`Failed to send message: ${data.message}`);
                        }
                    })
                    .catch(error => {
                        console.error('Error sending message:', error);
                        alert('An error occurred while sending the message.');
                    });

                // Optional: Show the WhatsApp message link
                const whatsappLink = `https://wa.me/${formattedPhone}?text=${encodeURIComponent(messageCaption)}`;
                const messagePreview = document.getElementById('messagePreview');
                messagePreview.innerHTML = `
    <strong>WhatsApp Message (Manual):</strong><br>
    <a href="${whatsappLink}" target="_blank">Click here to send a WhatsApp message</a>
    `;
            };
        });
    </script>
    <!-- ===============================================end fetch event member method =================================== -->

    <!-- ----------------------------------------------------------FECH EVENT BY ID ------------------------------------------------ -->
    <script>
        $(document).ready(function() {
            // Function to get query parameters from URL
            function getQueryParam(param) {
                let urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(param);
            }

            // Retrieve event_id from the URL
            let eventId = getQueryParam("event_id");

            // Function to fetch event details from the API
            function fetchEventDetails(eventId) {
                if (!eventId) {
                    $("#eventDetails").html("<p style='color: red;'>Event ID not found in URL.</p>");
                    return;
                }

                $.ajax({
                    url: `http://127.0.0.1:8000/api/events/${eventId}`, // Dynamic API endpoint based on event ID
                    type: "GET", // HTTP method to request data
                    dataType: "json", // Expected response format
                    success: function(response) { // Callback function on success
                        console.log(response); // Log response for debugging

                        // Populate event details into the HTML element with id 'eventDetails'
                        $("#eventDetails").html(
                            `<h3>${response.event_title}</h3>
                    <p><strong>Category:</strong> ${response.event_category}</p>
                    <p><strong>Location:</strong> ${response.event_location}</p>
                    <p><strong>Venue:</strong> ${response.event_venue}</p>
                    <p><strong>Start Time:</strong> ${response.event_start_time}</p>
                    <p><strong>End Time:</strong> ${response.event_end_time}</p>
                    <p><strong>Date:</strong> ${response.event_date}</p>
                    <p><strong>Description:</strong> ${response.event_description}</p>
                    <p><strong>Card:</strong> ${response.event_card}</p>
                    <p><strong>Code:</strong> ${response.event_code}</p>
                    <p><strong>QR Code:</strong> ${response.event_qrcode}</p>
                    <p><strong>Status:</strong> ${response.event_status}</p>
                    <p><strong>WhatsApp Notifications:</strong> ${response.notifications_whatsapp ? 'Enabled' : 'Disabled'}</p>
                    <p><strong>Email Notifications:</strong> ${response.notifications_email ? 'Enabled' : 'Disabled'}</p>
                    <p><strong>SMS Notifications:</strong> ${response.notifications_sms ? 'Enabled' : 'Disabled'}</p>
                    <p><strong>Created At:</strong> ${response.created_at}</p>
                    <p><strong>Updated At:</strong> ${response.updated_at}</p>`
                        );
                    },
                    error: function(xhr, status, error) { // Callback function on error
                        console.error("Error fetching event details:", error); // Log error for debugging
                        $("#eventDetails").html("<p style='color: red;'>Failed to load event details.</p>"); // Display error message
                    }
                });
            }

            fetchEventDetails(eventId); // Call function to fetch event details on page load
        });
    </script>

    <!-- ===================================================================================================================================== -->


    <!-- ----------------------------------------------update event --------------------------------------------------- -->
    <script>
        $(document).ready(function() {
            $('#updateEventForm').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                let eventId = $('#event_id').val().trim(); // Trim whitespace

                // Validate if event ID exists
                if (!eventId) {
                    alert("Error: Event ID is missing!");
                    return;
                }

                let formData = {
                    _token: $('input[name="_token"]').val(),
                    event_id: eventId, // Ensure event_id is included
                    event_title: $('#event_title').val().trim(),
                    event_category: $('#event_category').val(),
                    event_location: $('#event_location').val().trim(),
                    event_venue: $('#event_venue').val().trim(),
                    event_start_time: $('#event_start_time').val(),
                    event_end_time: $('#event_end_time').val(),
                    event_status: $('#event_status').val()
                };

                $.ajax({
                    url: `/api/events/update/new/${eventId}`,
                    type: "POST",
                    data: formData,
                    dataType: "json", // Ensure response is treated as JSON
                    success: function(response) {
                        if (response.success) {
                            alert("✅ Event updated successfully!");
                            location.reload();
                        } else {
                            alert("⚠️ " + (response.message || "Update failed!"));
                        }
                    },
                    error: function(xhr) {
                        console.error("AJAX Error: ", xhr); // Debugging output

                        let errorMessage = "❌ An error occurred!";
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).join("\n");
                        }
                        alert(errorMessage);
                    }
                });
            });
        });
    </script>

    <!-- =============================================================end update event ============================================ -->


    <!-- //////////////////////////////////////////////////////////////////////////////////// -->

    <!-- /////////////////////////////update user //////////////////////////////////////////////////////// -->
    <!-- <script>
        async function updateUser() {
  // User ID
    const memberName = document.getElementById('userName').value; // Name
    const memberPhone = document.getElementById('userPhone').value; // Phone
    const memberEmail = document.getElementById('userEmail').value; // Email (new field)
    const rsvpStatus = document.getElementById('userRSVP').value; // RSVP Status (new field)
    const userStatus = document.getElementById('userStatus').value; // Status
    const userTotal = document.getElementById('userTotal').value; // Total

    const API_URL = `http://localhost:8000/api/members/${id}`;

    const data = {
        member_name: memberName,
        member_phone: memberPhone,
        member_email: memberEmail,
        rsvp_status: rsvpStatus,
        status: userStatus,
        total: userTotal,
    };

    try {
        const response = await fetch(API_URL, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        if (response.ok) {
            const result = await response.json();
            alert('Member updated successfully!');
            console.log(result);

            $('#edityUserModal').modal('hide'); // Close the modal
            fetchUserList(); // Refresh the user list if applicable
        } else if (response.status === 422) {
            const errorData = await response.json();
            alert('Validation failed: ' + JSON.stringify(errorData.errors));
        } else {
            alert('An error occurred while updating the member.');
        }
    } catch (error) {
        console.error('Error updating member:', error);
        alert('A network error occurred. Please try again.');
    }
} -->

    </script>

</body>

</html>