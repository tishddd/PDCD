<div id="edityUserModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for editing user -->
                <form id="editUserForm">
                <div class="form-group">
                        <label for="ifd">id</label>
                        <input type="text" class="form-control" id="id" placeholder="Enter name">
                    </div>
                    <div class="form-group">
                        <label for="memberName">Name</label>
                        <input type="text" class="form-control" id="memberName" placeholder="Enter name">
                    </div>
                    <div class="form-group">
                        <label for="memberPhone">Phone</label>
                        <input type="text" class="form-control" id="memberPhone" placeholder="Enter phone number">
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <input type="text" class="form-control" id="status" placeholder="Enter status">
                    </div>
                   
                    <div class="form-group">
                        <label for="total">Total</label>
                        <input type="number" class="form-control" id="total" placeholder="Enter total">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeEditUserModal()">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateUser()">Update User</button>
            </div>
        </div>
    </div>
</div>
