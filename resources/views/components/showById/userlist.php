<div class="col-md-6">
    <div class="card" style="padding: 10px; font-family: Arial, sans-serif;">
        <div class="card-header" style="font-size: 14px; padding: 5px 10px; display: flex; justify-content: space-between; align-items: center;">
            User List
            <div>
                <button class="btn btn-success" onclick="openAddUserModal()" style="padding: 3px 8px; font-size: 12px;">Add User</button>
                <button class="btn btn-success" onclick="openAddExelModal()" style="padding: 3px 8px; font-size: 12px;">Upload Excel</button>
                <button class="btn btn-message" onclick="sendToAllMember()" style="padding: 3px 8px; font-size: 12px;">Send Message to all</button>
            </div>
        </div>
        <table id="userTable" class="table" style="width: 100%; font-size: 12px; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                    <th style="padding: 5px; border: 1px solid #dee2e6;">Id</th>
                    <th style="padding: 5px; border: 1px solid #dee2e6;">Name</th>
                    <th style="padding: 5px; border: 1px solid #dee2e6;">Status</th>
                    <th style="padding: 5px; border: 1px solid #dee2e6;">Total</th>
                    <th style="padding: 5px; border: 1px solid #dee2e6;">Phone</th>
                    <th style="padding: 5px; border: 1px solid #dee2e6;">scans</th>
                    <th style="padding: 5px; border: 1px solid #dee2e6;">WhatsApp</th>
                    <th style="padding: 5px; border: 1px solid #dee2e6;">sms</th>
                    <th style="padding: 5px; border: 1px solid #dee2e6;">PassCode</th>
                    <th style="padding: 5px; border: 1px solid #dee2e6;">Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dynamically populated rows -->
            </tbody>
        </table>
    </div>
</div>

<div id="qrCodeContainer"></div>
<p id="messagePreview"></p>