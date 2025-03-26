<div class="modal-content common-popup" id="otp-modal">
    <div class="modal-header">
        <h1 class="modal-title" id="exampleModalLabel">verify Otp</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form action="javascript:;" id="verify_otp_with_phone">
        <div class="modal-body">
            <div class="otp-popup">
                <input type="hidden" name="otp_phone" value="{{ $phone }}">
                <input type="text" name="otp_value" value="" id="otp_value" class="form-control">
                <button type="submit" class="btn" id="otp_form">Verify</button>
            </div>
        </div>
    </form>
</div>
