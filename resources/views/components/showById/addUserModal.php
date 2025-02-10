<style>
    @keyframes glowing {
        0% { box-shadow: 0 0 10px green; }
        50% { box-shadow: 0 0 20px limegreen; }
        100% { box-shadow: 0 0 10px green; }
    }

    .glow-border {
        border: 4px solid white;
        border-radius: 12px;
        animation: glowing 1.5s infinite alternate;
        padding: 20px;
        max-width: 450px;
        margin: auto;
        position: relative;
    }

    .modal-header {
        background-color: #007bff;
        color: white;
        padding: 10px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 20px;
        color: white;
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .modal-footer {
        display: flex;
        justify-content: space-between;
        padding-top: 10px;
    }

    .btn {
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .form-control {
        width: 100%;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
</style>

<div id="addUserModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="glow-border">
            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="modal-close" data-dismiss="modal" aria-label="Close" onclick="closeAddUserModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for adding user -->
                <form id="addUserForm">
                    <div class="form-group">
                        <label for="userName">Name</label>
                        <input type="text" class="form-control" id="userName" placeholder="Enter name">
                    </div>
                    <div class="form-group">
                        <label for="userPhone">Phone</label>
                        <input type="text" class="form-control" id="userPhone" placeholder="Enter phone number">
                    </div>
                    <div class="form-group">
                        <label for="userStatus">Status</label>
                        <select class="form-control" id="userStatus">
                            <option value="active">Double</option>
                            <option value="inactive">Single</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="userTotal">Total</label>
                        <input type="number" class="form-control" id="userTotal" placeholder="Enter total">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeAddUserModal()">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveUser()">Save User</button>
            </div>
        </div>
    </div>
</div>
