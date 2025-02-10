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
        max-width: 400px;
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
</style>

<div id="addExel" class="modal">
    <div class="glow-border">
        <div class="modal-header">
            <h5>Add Excel</h5>
            <button class="modal-close" onclick="closeAddExelModal()">&times;</button>
        </div>
        <form id="excelForm">
            <div class="form-group">
                <label for="excel_file">Upload Excel File</label>
                <input type="file" id="excel_file" name="excel_file" accept=".xls,.xlsx" required>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeAddExelModal()">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitExcel()">Upload</button>
            </div>
        </form>
    </div>
</div>
