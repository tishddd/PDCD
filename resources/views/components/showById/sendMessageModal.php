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
    }
</style>

<div id="sendMessageModalOpen" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered glow-border" role="document" style="max-width: 500px;">
        <div class="modal-content shadow-lg border-0" style="border-radius: 10px; padding: 5px;">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                <h5 class="modal-title" style="font-weight: bold; font-size: 18px;">Send Invitation</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="font-size: 20px; margin-left: auto;" onclick="closeMessageModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- WhatsApp Message Section -->
            <div class="modal-footer d-flex justify-content-between align-items-center" style="padding: 15px;">
                <button type="button" class="btn btn-primary" disabled>WhatsApp</button>
                <button type="button" class="btn btn-success" onclick="sendMessageViaWhatsapp()">Send</button>
            </div>

            <!-- SMS Message Section -->
            <div class="modal-footer d-flex justify-content-between align-items-center" style="padding: 15px;">
                <button type="button" class="btn btn-primary" disabled>SMS</button>
                <button type="button" class="btn btn-success" onclick="sendMessageViaSmS()">Send</button>
            </div>

            <!-- Send Both (WhatsApp & SMS) -->
            <div class="modal-footer d-flex justify-content-center" style="padding: 15px;">
                <button type="button" class="btn btn-purple text-white" style="background-color:#6610f2;" onclick="sendBoth()">Send Both</button>
            </div>
        </div>
    </div>
</div>
